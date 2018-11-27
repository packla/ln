<?php

namespace app\models;

use Yii;

class UserIp
{
    public static function getUserCity()
    {
        try {
            $ip = Yii::$app->request->getUserIP();
            if ($ip == '127.0.0.1') {
                $ip = '217.118.83.216';
            }
            $url = 'http://ip-api.com/json/' . $ip;
            $res = file_get_contents($url);
            $obj = json_decode($res);
            if ($obj->city) {
                return $obj->city;
            }
            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}