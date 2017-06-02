<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PackageOneAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/package-one.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
