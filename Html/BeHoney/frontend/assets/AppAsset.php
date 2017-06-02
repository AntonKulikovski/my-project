<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'js/owl/owl.carousel.css',
        'js/lightbox/css/lightbox.css',
        'js/malihu/jquery.mCustomScrollbar.min.css',
        'css/main.min.css',
    ];
    public $js = [
        'js/owl/owl.carousel.min.js',
        'js/lightbox/js/lightbox.js',
        'js/malihu/jquery.mCustomScrollbar.min.js',
        'js/front-end.min.js',
        'js/basket.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
