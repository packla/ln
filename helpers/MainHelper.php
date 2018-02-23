<?php

namespace app\helpers;

use app\entities\DomainsAr;
use Yii;

class MainHelper
{
    public static function getCurrentDomain()
    {
        $host     = 'http://' . Yii::$app->request->hostName;
        $segments = parse_url($host);
        $names    = explode('.', $segments['host']);
        if (3 === count($names)) {
            if (null === $domain = DomainsAr::getDomain($names[0])) {
                $domain = DomainsAr::getMainDomain();
            }
            return $domain;
        }
        return DomainsAr::getMainDomain();
    }
}