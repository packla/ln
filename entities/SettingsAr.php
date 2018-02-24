<?php

namespace app\entities;

use yii\db\ActiveRecord;

class SettingsAr extends ActiveRecord
{
    protected static $instance;
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
        if (null === static::$instance) {
            static::$instance = static::find()->one();
        }
        return static::$instance;
    }
}