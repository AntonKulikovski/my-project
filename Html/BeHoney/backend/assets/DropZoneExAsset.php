<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * DropZoneExAsset
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class DropZoneExAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets/dropzone';
    public $js = [
        'init-dropzone.js',
    ];
    public $css = [
        'dropzone.css',
    ];
    public $depends = [
        'kato\assets\DropZoneAsset',
        'yii\web\JqueryAsset',
    ];
}
