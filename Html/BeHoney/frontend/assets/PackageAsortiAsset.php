<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PackageAsortiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/package-asorti.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}