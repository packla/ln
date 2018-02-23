<?php
/**
 * Created by PhpStorm.
 * User: Jah
 * Date: 28.11.2017
 * Time: 21:13
 */

namespace app\assets;


use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin.css'
    ];
    public $js = [
        'js/admin.js'
    ];
    public $depends = [];
}