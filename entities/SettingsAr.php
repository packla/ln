<?php

namespace app\entities;

use yii\db\ActiveRecord;

class SettingsAr extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%settings}}';
    }

    public function rules()
    {
        return [
            [
                ['companyName'],
                'required',
            ],
        ];
    }

    public static function getInstance()
    {
        return static::find()->one();
    }
}