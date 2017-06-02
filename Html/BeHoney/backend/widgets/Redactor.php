<?php

namespace backend\widgets;

use vova07\imperavi\Widget;
use Yii;
use yii\helpers\Url;

class Redactor extends Widget
{

    public function init()
    {
        $this->defaultSettings = [
            'imageUpload' => Url::to(['/redactor/upload-image']),
            'fileUpload' => Url::to(['/redactor/upload-file']),
            'buttons' => [
                'html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist',
                'outdent', 'indent', 'image', 'file', 'link', 'alignment', 'horizontalrule', 'underline'
            ],
            'plugins' => ['fontsize', 'table', 'fontfamily'],
        ];

        parent::init();
    }
}
