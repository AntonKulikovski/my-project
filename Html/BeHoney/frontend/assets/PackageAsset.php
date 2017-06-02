<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PackageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/package.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
