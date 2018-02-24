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
            [
                ['linksCount'],
                'safe',
            ],
        ];
    }

    public static function getInstance()
    {
        return static::find()->one();
    }
}