<?php

namespace backend\widgets;

use backend\assets\DropZoneExAsset;
use common\base\web\UploadAction;
use flexibuild\file\File;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class DropZone extends \kato\DropZone
{
    const UPLOAD_MORE_BUTTON_CLASS = 'dropzone-upload-more-button';

    /**
     * @var string
     */
    public $uploadUrl = null;

    /**
     * @var array
     */
    public $uploadMoreButtonOptions = [
        'class' => 'btn btn-default',
    ];
    /**
     * Note! This content will NOT be encoded.
     * @var string
     */
    public $uploadMoreButtonContent = 'Load more';

    /**
     * @var File[]|null
     */
    public $initFiles;

    /**
     * @var callabale|null [[UploadAction::convertFileToArray()]] will be used by default.
     */
    public $fileToArrayConverter;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (isset($this->options['init'])) {
            throw new InvalidConfigException('You cannot define init option if $initFiles defined.');
        }

        if (!preg_match('/^[a-z\_][a-z0-9\_]*$/i', $this->id)) {
            throw new InvalidConfigException('Widget ' . static::className() . ' has invalid characters in $id property.');
        }
        $this->dropzoneContainer = $this->id;
        $this->previewsContainer = "{$this->id}Previews";

        if ($this->uploadUrl === null) {
            throw new InvalidConfigException('Property $uploadUrl is required.');
        }

        $this->initOptions();
        $this->initEventHandlers();

        if (!isset($this->options['clickable'])) {
            $this->options['clickable'] = [
                "#$this->dropzoneContainer",
                "#$this->previewsContainer",
            ];
        }

        $converter = $this->fileToArrayConverter ?: [UploadAction::className(), 'convertFileToArray'];
        $files = [];
        if (is_array($this->initFiles)) {
            foreach ($this->initFiles as $file) {
                $files[] = call_user_func($converter, $file);
            }
        }
        $this->options['init'] = new JsExpression('function () {
            return window.dzr.initDropZone(this, ' . Json::htmlEncode($files) . ');
        }');

        parent::init();
    }

    /**
     * Initializes Dropzone js plugin options.
     */
    protected function initOptions()
    {
        $defaultOptions = [
            'addRemoveLinks' => true,
        ];

        foreach ($defaultOptions as $key => $value) {
            if (!isset($this->options[$key]) && !array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            }
        }
    }

    /**
     * @return string
     */
    protected function renderUploadMoreButton()
    {
        $options = $this->uploadMoreButtonOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'button');

        if (!strcasecmp($tag, 'button') && !isset($options['type'])) {
            $options['type'] = 'button';
        }

        Html::addCssClass($options, self::UPLOAD_MORE_BUTTON_CLASS);
        return Html::tag($tag, $this->uploadMoreButtonContent, $options);
    }

    /**
     * Initializes client events.
     */
    protected function initEventHandlers()
    {
        $id = Json::htmlEncode($this->id);
        $maxFiles = Json::htmlEncode(isset($this->options['maxFiles']) ? (int) $this->options['maxFiles'] : null);

        $uploadMoreButton = Json::htmlEncode($this->renderUploadMoreButton());
        $uploadMoreClass = Json::htmlEncode(self::UPLOAD_MORE_BUTTON_CLASS);

        $defaultEventHandlers = [
            'sending' => '(function () {
                window.yii && yii.refreshCsrfToken();
                return function (file, xhr, formData) {
                    if (/*!crossDomain && */ window.yii && yii.getCsrfParam()) {
                        xhr.setRequestHeader("X-CSRF-Token", yii.getCsrfToken());
                    }
                };
            })()',
            'error' => 'function (file, message) {
                if (message.success === false) {
                    message = message.message || message.error || "Internal server error";
                }
                return Dropzone.prototype.defaultOptions.error(file, message);
            }',
            'addedfile' => "(function () {
                var \$dropzone, \$uploadMore, \$message,
                    inited = false;
                    init = function () {
                        if (inited) {
                            return;
                        }
                        \$dropzone = $('#' + $id);
                        \$message = \$dropzone.find('.dz-default.dz-message');
                        \$dropzone.append($uploadMoreButton);
                        \$uploadMore = \$dropzone.find('.' + $uploadMoreClass).hide().click(function () {
                            $(this).parent().click();
                            $(this).blur();
                            return false;
                        });
                        inited = true;
                    };
                return function () {
                    init();
                    \$message.hide();
                    ($maxFiles === null || $maxFiles > this.files.length)
                        ? \$uploadMore.show()
                        : \$uploadMore.hide();
                };
            })()",
            'removedfile' => "(function () {
                var \$dropzone, \$uploadMore,
                    init = function () {
                        \$dropzone = \$dropzone || $('#' + $id);
                        \$uploadMore = (\$uploadMore && \$uploadMore.length) ? \$uploadMore : \$dropzone.find('.' + $uploadMoreClass);
                    };
                return function () {
                    init();
                    ($maxFiles === null || $maxFiles > this.files.length)
                        ? \$uploadMore.show()
                        : \$uploadMore.hide();
                };
            })()",
            'reset' => "(function () {
                var \$dropzone = null, \$message = null, \$uploadMore = null,
                    init = function () {
                        \$dropzone = \$dropzone || $('#' + $id);
                        \$message = \$message || \$dropzone.find('.dz-default.dz-message');
                        \$uploadMore = \$uploadMore || \$dropzone.find('.' + $uploadMoreClass);
                        \$uploadMore = \$uploadMore.length ? \$uploadMore : null;
                    };
                return function () {
                    init();
                    \$message.show();
                    \$uploadMore && \$uploadMore.hide();
                };
            })()",
        ];

        foreach ($defaultEventHandlers as $event => $handler) {
            if (!isset($this->clientEvents[$event])) {
                $this->clientEvents[$event] = $handler;
            } else {
                $this->clientEvents[$event] = "function () {
                    ($handler).apply(this, arguments);
                    return ({$this->clientEvents[$event]}).apply(this, arguments);
                }";
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function registerAssets()
    {
        $result = parent::registerAssets();
        DropZoneExAsset::register($this->getView());
        return $result;
    }
}
