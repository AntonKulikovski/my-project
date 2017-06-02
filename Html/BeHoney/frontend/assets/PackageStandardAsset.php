<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PackageStandardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/package-standard.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
