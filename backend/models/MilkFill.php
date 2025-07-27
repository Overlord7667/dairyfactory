<?php

namespace app\models;

use yii\db\ActiveRecord;

class MilkFill extends ActiveRecord
{
    public static function tableName()
    {
        return 'milk_fill';
    }

    public function rules()
    {
        return [
            [['user_name', 'volume', 'tank_id', 'created_at'], 'required'],
            [['volume', 'tank_id', 'created_at'], 'integer'],
            [['user_name'], 'string', 'max' => 255],
        ];
    }
}