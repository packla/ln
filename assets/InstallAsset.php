<?php

namespace app\assets;

use yii\web\AssetBundle;

class InstallAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/install.css'
    ];
    public $js = [
        //'js/install.js'
    ];
    public $depends = [];
}