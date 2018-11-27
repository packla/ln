<?php
/**
 * Created by PhpStorm.
 * User: Jah
 * Date: 09.12.2017
 * Time: 15:19
 */

namespace app\entities;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class DomainsAr extends ActiveRecord
{
    const MAIN_DOMAIN = '.';

    protected $arrayData = [];

    public static function tableName()
    {
        return '{{%domains}}';
    }

    public function rules()
    {
        return [
            [
                ['domain', 'attributes'],
                'required',
            ],
        ];
    }

    public static function getMainDomain()
    {
        return static::getDomain(static::MAIN_DOMAIN);
    }

    /**
     * @param $domain
     *
     * @return static
     */
    public static function getDomain($domain)
    {
        return static::find()->where(['domain' => $domain])->one();
    }

    public static function getDomainByCity($city)
    {
        return static::find()->where(['like', 'LOWER(domain)', strtolower($city)])->one();
    }

    public function isMain()
    {
        return $this->domain === static::MAIN_DOMAIN;
    }

    public function getParam($name)
    {
        $data = $this->getArrayData();
        $key  = '%' . $name . '%';
        if (!array_key_exists($key, $data)) {
            return '';
        }
        return $data[$key];
    }

    public function getArrayData()
    {
        if (empty($this->arrayData)) {
            $this->arrayData = ArrayHelper::toArray(json_decode($this->data));
        }
        return $this->arrayData;
    }
}