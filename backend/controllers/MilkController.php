<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\web\Response;
use Yii;
use app\models\Tank;
use app\models\MilkFill;

class MilkController extends Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class'   => \yii\filters\ContentNegotiator::class,
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ];
    }

    public function actionStat()
    {
        try {
            $tanks = Tank::find()->all();
            $fills = MilkFill::find()->orderBy(['created_at' => SORT_DESC])->asArray()->all();
            return ['tanks' => $tanks, 'fills' => $fills];
        } catch (\Throwable $e) {
            Yii::error("MilkController::actionStat error: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function actionFill()
    {
        $data = json_decode(Yii::$app->request->getRawBody(), true);

        $name = trim($data['name'] ?? '');
        $volume = (int)($data['volume'] ?? 0);

        if (!$name || $volume <= 0) {
            return ['success' => false, 'message' => 'Invalid input'];
        }

        // Получаем все цистерны по ID по порядку, в которые можно залить молоко (не полностью заполненные)
        $tanks = Tank::find()
            ->where('current_volume < capacity')
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if (empty($tanks)) {
            return ['success' => false, 'message' => 'Нет доступных цистерн для заливки'];
        }

        $totalVolumeToFill = $volume;
        $fillsToSave = []; // для записи в историю

        foreach ($tanks as $tank) {
            $freeSpace = $tank->capacity - $tank->current_volume;

            if ($freeSpace <= 0) {
                // Если вдруг цистерна уже полная — пропускаем
                continue;
            }

            // Сколько мы можем залить в эту цистерну — минимум из оставшегося объёма и свободного места
            $fillAmount = min($totalVolumeToFill, $freeSpace);

            // Обновляем объём цистерны
            $tank->current_volume += $fillAmount;
            if (!$tank->save()) {
                return ['success' => false, 'message' => 'Ошибка при сохранении цистерны #' . $tank->id];
            }

            // Сохраняем заливку в историю
            $fillsToSave[] = new MilkFill([
                'user_name' => $name,
                'volume' => $fillAmount,
                'tank_id' => $tank->id,
                'created_at' => time(),
            ]);

            // Уменьшаем объём для заливки на уже залитый объём
            $totalVolumeToFill -= $fillAmount;

            // Если весь объём залит — выходим из цикла
            if ($totalVolumeToFill <= 0) {
                break;
            }
        }

        if ($totalVolumeToFill > 0) {
            // Остался объём, но все цистерны заполнены
            return ['success' => false, 'message' => 'Недостаточно места во всех цистернах'];
        }

        // Сохраняем все заливки в историю
        foreach ($fillsToSave as $fillRecord) {
            if (!$fillRecord->save()) {
                return ['success' => false, 'message' => 'Ошибка при сохранении истории заливок'];
            }
        }

        return ['success' => true];
    }

    public function actionReset()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Обнуляем объём во всех цистернах
        \app\models\Tank::updateAll(['current_volume' => 0]);

        // Удаляем все заливки
        \app\models\MilkFill::deleteAll();

        return ['success' => true];
    }
}