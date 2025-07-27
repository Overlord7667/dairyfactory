<?php

namespace app\models;

use yii\db\ActiveRecord;

class Tank extends ActiveRecord
{
    public static function tableName()
    {
        return 'tank'; // название таблицы в базе данных
    }
}