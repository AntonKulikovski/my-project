<?php

namespace backend\widgets;

use yii\web\AssetBundle;

/**
 * One2ManyAsset
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class One2ManyAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/assets';

    public $js = [
        'one2many.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
