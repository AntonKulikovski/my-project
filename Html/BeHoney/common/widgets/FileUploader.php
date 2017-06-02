<?php

namespace common\widgets;

use flexibuild\file\widgets\BlueimpJQueryUploader;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class FileUploader
 * @package backend\widgets
 */
class FileUploader extends BlueimpJQueryUploader
{
    public $previewType = self::PREVIEW_TYPE_IMAGE_LINK;

    public $removeContainerOptions = [
        'href' => '#',
        'class' => 'btn btn-danger btn-sm',
    ];

    public $progressBarOptions = [
        'class' => 'progress-bar progress-bar-success',
        'role' => 'progressbar',
    ];

    public function init()
    {
        $this->template = $this->render('file-uploader/template.sphp');
        $this->removeContainerContent = Html::tag('i', '', [
            'class' => 'fa fa-times',
        ]) . ' ' . Html::encode(Yii::t('app', 'Remove'));

        Html::addCssStyle($this->fileInputOptions, [
            'height' => '30px',
            'padding-top' => '5px',
        ]);
        Html::addCssStyle($this->removeContainerOptions, [
            'margin' => '5px 0 0 15px',
        ]);

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function renderProgressBar()
    {
        $result = parent::renderProgressBar();
        $id = Json::encode($this->options['id']);
        $js = "jQuery('#' + $id).on('fileuploadprogressall', function (e, data) {
            var percent = data.total != 0 ? Math.round(data.loaded / data.total * 100) : 100,
                epsilon = 1e-5;
            $(this).find('.progress').toggle(Math.abs(percent - 100) > epsilon);
        })";
        
        $this->getView()->registerJs($js);

        return $result;
    }
}
