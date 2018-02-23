<?php

namespace app\widgets;

use app\entities\DomainsAr;
use app\helpers\MainHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class LinksWidget extends Widget
{
    public function run()
    {
        $domains = $this->getDomainsList();
        $names   = explode('.', Yii::$app->request->serverName);
        if (count($names) === 2) {
            $main = $names[0] . '.' . $names[1];
        } else {
            $main = $names[count($names) - 2] . '.' . $names[count($names) - 1];
        }
        $links = [];
        foreach ($domains as $domain) {
            $host = 'http://';
            if ($domain->domain === DomainsAr::MAIN_DOMAIN) {
                $host .= $main;
            } else {
                $host .= $domain->domain . '.' . $main;
            }
            $domain->domain = $host;
            $links[] = $domain;
        }
        
        return $this->render('links', ['domains' => $links]);
    }

    protected function getDomainsList()
    {
        $current = MainHelper::getCurrentDomain();
        $list    = DomainsAr::find()->where(['>', 'id', $current->id])->limit(2)->all();

        if (2 > count($list)) {
            $need    = 2 - count($list);
            $another = DomainsAr::find()->orderBy('id ASC')->limit($need)->all();
            $list    = ArrayHelper::merge($list, $another);
        }
        return $list;
    }

    public function getViewPath()
    {
        return Yii::getAlias('@app') . '/views/site';
    }
}