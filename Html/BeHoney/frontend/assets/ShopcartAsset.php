<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ShopcartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/shopcart.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
