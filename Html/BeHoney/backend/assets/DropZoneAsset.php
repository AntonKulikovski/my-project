<?php

namespace kato\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for DropZone Widget
 */
class DropZoneAsset extends AssetBundle
{
    public $sourcePath = '@dropzone/bower_components/dropzone/downloads/';
    public $js = [
        'dropzone.js',
    ];
    public $css = [
        'css/dropzone.css'
    ];
    /**
     * @var array
     */
    public $publishOptions = [];
}
