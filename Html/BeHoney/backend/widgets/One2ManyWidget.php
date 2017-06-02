<?php

namespace backend\widgets;

use common\base\behaviors\multiplier\One2Many;
use common\base\db\ActiveRecord;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\InvalidValueException;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\JuiAsset;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * One2ManyWidget
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 * 
 * @property-read One2Many $link
 * @property-read string $keyPlaceholder
 */
class One2ManyWidget extends InputWidget
{
    const TEMPLATE_KEY_PLACEHOLDER = '___KEY_{widgetId}__PLACEHOLDER___';
    const CSS_CLASS_MODEL_WRAPPER = 'one2many-model';
    const CSS_CLASS_MODELS_WRAPPER = 'one2many-models';
    const CSS_CLASS_MODEL_REMOVE = 'one2many-model-remove';
    const CSS_CLASS_MODEL_ADD = 'one2many-add-model';
    const NEW_MODEL_KEY_PREFIX = 'new_';

    /**
     * @var boolean whether widget must to check max nesting level config.
     * But it will be checked only on using infinitely nested submodels.
     * This checking will be skipped in `YII_DEBUG` mode.
     */
    public static $checkNestingLevel = true;

    /**
     * @var ActiveField
     */
    public $field;
    

    /**
     * @var string|\Closure the name of view for rendering subform for each linked model.
     * You may use [[\Closure]] without view name too.
     * The following params will be passed:
     * - $form instance of ActiveForm
     * - $model the linked submodel
     * - $widget this widget
     * - $data value of [[self::$viewData]]
     */
    public $submodelView;

    /**
     * @var mixed data that will be passed to [[self::$submodelView]].
     */
    public $viewData;

    /**
     * @var string the template of current widget. You may use the following placeholders:
     * - {models}, models wrappers will be rendered here,
     * - {add-button}, add button will be rendered here
     */
    public $template = "{models}\n{add-button}";

    /**
     * @var array html options of model wrapper.
     */
    public $modelWrapper = [];

    /**
     * @var array html options of models list wrapper.
     */
    public $modelsWrapper = [];

    /**
     * @var string the template for rendering content inside model wrapper.
     * You may use the following placeholders:
     * - {model}, rendered model's content
     * - {remove-button}, rendered remove button
     */
    public $modelTemplate = "{model}\n{remove-button}\n<hr/>";

    /**
     * @var array|\Closure HTML attributes of remove button. Has special options:
     * - tag, string, default 'button', also default type is 'button'
     * - label, string, will be insert as NOT HTML ENCODED, default '&times;'
     * If [[\Closure]] will be passed it will be executed with ActiveRecord param for getting array.
     */
    public $removeOptions = [];

    /**
     * @var array HTML attributes of add button. Has special options:
     * - tag, string, default 'button', also default type is 'button'
     * - label, string, will be insert as NOT HTML ENCODED, default '+'
     */
    public $addOptions = [];

    /**
     * @var boolean|null|array whether models are sortable. Null meaning the widget
     * see if [[One2Many::$targetPositionAttribute]] is not null than enables plugin.
     */
    public $sortable = null;

    /**
     * @var array of jQuery `flexibuildOne2Many` plugin.
     */
    public $clientOptions = [];

    /**
     * @var One2ManyWidget|null parent widget.
     */
    public $parentWidget;

    /**
     * @var boolean whether the widget is rendering template right now.
     */
    protected $isRenderingTemplate = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->hasModel()) {
            // hard coded logic of generating id, because it has sense in nested infinite submodels
            $this->id = $this->options['id'] = Html::getInputId($this->model, $this->attribute);
        } else {
            throw new InvalidConfigException(static::className() . ' works with model & attribute only.');
        }

        parent::init();

        if (!$this->model->hasMethod('getLink') || !$this->model->getLink($this->attribute) instanceof One2Many) {
            throw new InvalidConfigException(get_class($this->model) . ' must to use ' . MultiplierBehavior::className() . ' with ' . One2Many::className() . " link named as '$this->attribute'.");
        } elseif ($this->submodelView === null) {
            throw new InvalidConfigException('Property $submodelView is required.');
        } elseif (!$this->field instanceof ActiveField) {
            throw new InvalidConfigException('Property $field must be an instance of ' . ActiveField::className() . '.');
        }
        if ($this->parentWidget !== null) {
            if (!$this->parentWidget instanceof self) {
                throw new InvalidConfigException('Property $parentWidget must be an instance of ' . self::className() . '.');
            } elseif (!YII_DEBUG && self::$checkNestingLevel) {
                self::$checkNestingLevel = false;
                
                if (ini_get('xdebug.max_nesting_level')) {
                    throw new InvalidConfigException(implode(' ', [
                        'You must turn off "xdebug.max_nesting_level" php ini config,',
                        'because it value may be reached very quickly,',
                        'when you use nesting One2Many widget.',
                    ]));
                }
            }
        }
        if ($this->sortable === null) {
            $this->sortable = $this->getLink()->targetPositionAttribute !== null;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = $this->link->models;
        $clientOptions = [];
        
        if ($this->parentWidget !== null && !count($models) && $this->parentWidget->isRenderingTemplate) {
            $clientOptions['nestedParentId'] = $this->parentWidget->getId();
            $clientOptions['nestedModelKey'] = $this->parentWidget->getKeyPlaceholder();
            $clientOptions['nestedAdditionalFormNamePart'] = $this->fetchAdditionFormNamePart();
        } else {
            $clientOptions['template'] = $this->renderTemplate($attributes);
            $clientOptions['attributes'] = array_values($attributes);
        }

        $newId = 0;
        $modelsContent = '';
        
        foreach ($models as $model) {
            $id = $model->isNewRecord ? self::NEW_MODEL_KEY_PREFIX . ++$newId : null;
            $modelsContent .= $this->renderModel($model, $id);
        }

        $content = strtr($this->template, [
            '{models}' => $this->renderModelsWrapper($modelsContent),
            '{add-button}' => $this->renderAddButton(),
        ]);
        $hiddenInput = $this->renderHiddenInput();
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        $result = Html::tag($tag, $hiddenInput . $content, $options);

        $this->registerClientScripts($newId, $clientOptions);
        
        return $result;
    }

    /**
     * Register all necessary client scripts.
     * @param integer $lastId
     * @param array $options additional options.
     */
    protected function registerClientScripts($lastId, $options = [])
    {
        One2ManyAsset::register($this->getView());
        
        if ($this->sortable) {
            JuiAsset::register($this->getView());
        }

        $jsonedId = Json::htmlEncode($this->getId());
        $jsonedOptions = Json::htmlEncode(array_merge([
            'lastId' => $lastId,
            'formName' => $this->generateFormNameForTemplate(),
            'sortable' => (bool) $this->sortable,
        ], $options, $this->clientOptions));
        $js = "jQuery('#' + $jsonedId).flexibuildOne2Many($jsonedOptions)";

        $this->getView()->registerJs($js);
    }

    /**
     * @param string $content inner content
     * @return string rendered content
     */
    protected function renderModelsWrapper($content)
    {
        $options = $this->modelsWrapper;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        
        Html::addCssClass($options, [
            self::CSS_CLASS_MODELS_WRAPPER,
            self::CSS_CLASS_MODELS_WRAPPER . "__$this->id",
        ]);
        
        return Html::tag($tag, $content, $options);
    }

    /**
     * Renders template, this template will be used for inserting new submodels by JS.
     * @param array $attributes
     * @return string
     */
    protected function renderTemplate(&$attributes = [])
    {
        $this->isRenderingTemplate = true;
        $oldCss = $this->getView()->css ?: [];
        $oldJs = $this->getView()->js ?: [];
        $oldAttributes = $this->extractFormAttributes();

        $model = $this->getLink()->createTargetModel();
        $content = $this->renderModel($model, $this->getKeyPlaceholder());

        foreach (array_diff_key($oldCss, $this->getView()->css ?: []) as $key) {
            unset($oldCss[$key]);
        }
        
        $newCss = array_diff_key($this->getView()->css ?: [], $oldCss);
        $headJs = $endJs = [];
        $currentJs = $this->getView()->js ?: [];
        $getNewJs = function ($pos) use (&$oldJs, $currentJs) {
            $old = ArrayHelper::getValue($oldJs, $pos, []) ?: [];
            $current = ArrayHelper::getValue($currentJs, $pos, []) ?: [];
            
            foreach (array_keys(array_diff_key($old, $current)) as $key) {
                unset($oldJs[$pos][$key]);
            }
            
            $new = array_diff_key($current, $old);
            
            return $new ? implode("\n", $new) : null;
        };

        foreach ([View::POS_HEAD, View::POS_BEGIN] as $pos) {
            if ($new = $getNewJs($pos)) {
                $headJs[] = $new;
            }
        }
        
        if ($new = $getNewJs(View::POS_END)) {
            $endJs[] = $new;
        }
        
        foreach ([View::POS_READY, View::POS_LOAD] as $pos) {
            if ($new = $getNewJs($pos)) {
                $endJs[] = "(function () {\n$new\n})();";
            }
        }

        $attributes = array_diff_key($this->extractFormAttributes(), $oldAttributes);
        $before = $after = '';
        
        if (!empty($newCss)) {
            $before .= implode("\n", $newCss);
        }
        if (!empty($headJs)) {
            $before .= Html::script(implode("\n", $headJs), [
                'type' => 'text/javascript',
            ]);
        }
        if (!empty($endJs)) {
            $after .= Html::script(implode("\n", $endJs), [
                'type' => 'text/javascript',
            ]);
        }

        $this->getView()->css = $oldCss;
        $this->getView()->js = $oldJs;
        $this->field->form->attributes = array_values($oldAttributes);
        $this->isRenderingTemplate = false;
        
        return $before . $content . $after;
    }

    /**
     * @param ActiveRecord $model
     * @param scalar|null $id
     * @return string
     * @throws InvalidParamException
     */
    protected function renderModel($model, $id = null)
    {
        if (!$model instanceof ActiveRecord) {
            throw new InvalidParamException('Param $model must be an instance of ' . ActiveRecord::className() . '.');
        } elseif (!$model->{$this->link->targetKey} && empty($id)) {
            throw new InvalidParamException('Param $id must be defined for models that is not have primary key.');
        }

        if (!empty($id)) {
            $oldId = [$model->{$this->link->targetKey}];
            $model->{$this->link->targetKey} = $id;
        }

        $this->populateFormName($model, $this->generateFormName($model));

        $options = $this->modelWrapper;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        
        Html::addCssClass($options, [
            self::CSS_CLASS_MODEL_WRAPPER,
            self::CSS_CLASS_MODEL_WRAPPER . "__$this->id",
        ]);
        
        $options['data-key'] = $model->{$this->link->targetKey};
        $result = Html::tag($tag, strtr($this->modelTemplate, [
            '{model}' => $this->renderView($model),
            '{remove-button}' => $this->renderRemoveButton($model),
        ]), $options);
        
        if (isset($oldId)) {
            $model->{$this->link->targetKey} = $oldId[0];
        }
        
        return $result;
    }

    /**
     * Renders remove button
     * @param ActiveRecord $model
     * @return string rendered HTML content.
     */
    protected function renderRemoveButton($model)
    {
        $options = $this->removeOptions;
        
        if ($options instanceof \Closure) {
            $options = call_user_func($options, $model);
        }

        $tag = ArrayHelper::remove($options, 'tag', 'button');
        
        if (!strcasecmp($tag, 'button') && !isset($options['type'])) {
            $options['type'] = 'button';
        }
        
        $label = ArrayHelper::remove($options, 'label', '&times;');
        $options['data-key'] = $model->{$this->link->targetKey};
        
        Html::addCssClass($options, [
            self::CSS_CLASS_MODEL_REMOVE,
            self::CSS_CLASS_MODEL_REMOVE . "__$this->id",
        ]);

        return Html::tag($tag, $label, $options);
    }

    /**
     * Renders add button
     * @return string rendered HTML content.
     */
    protected function renderAddButton()
    {
        $options = $this->addOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'button');
        
        if (!strcasecmp($tag, 'button') && !isset($options['type'])) {
            $options['type'] = 'button';
        }
        
        $label = ArrayHelper::remove($options, 'label', '+');

        Html::addCssClass($options, [
            self::CSS_CLASS_MODEL_ADD,
            self::CSS_CLASS_MODEL_ADD . "__$this->id",
        ]);

        return Html::tag($tag, $label, $options);
    }

    /**
     * Render hidden input.
     * @return string rendered content
     */
    protected function renderHiddenInput()
    {
        $name = $this->model->formName() . "[$this->attribute]";
        
        return Html::hiddenInput($name, '');
    }

    /**
     * @param ActiveRecord $model
     * @throws InvalidParamException
     */
    protected function generateFormName($model)
    {
        if (!$model instanceof ActiveRecord) {
            throw new InvalidParamException('Param $model must be an instance of ' . ActiveRecord::className() . '.');
        }
        
        $key = $model->{$this->link->targetKey};
        
        return $this->model->formName() . "[$this->attribute][$key]";
    }

    /**
     * @return string
     */
    protected function generateFormNameForTemplate()
    {
        $model = $this->getLink()->createTargetModel();
        $model->{$this->link->targetKey} = $this->getKeyPlaceholder();
        
        return $this->generateFormName($model);
    }

    /**
     * @return string
     */
    protected function fetchAdditionFormNamePart()
    {
        if (!$this->parentWidget instanceof self) {
            throw new InvalidCallException('Property $parentWidget must be set for calling the ' . __METHOD__ . ' method.');
        }

        $parentFormName = $this->parentWidget->generateFormNameForTemplate();
        $parentLength = strlen($parentFormName);
        $currentFormName = $this->generateFormNameForTemplate();

        if (strncmp($parentFormName, $currentFormName, $parentLength)) {
            throw new InvalidValueException('Form name of child widget must to start with form name of parent.');
        }

        return substr($currentFormName, $parentLength);
    }

    /**
     * @param ActiveRecord $model
     * @param string $formName
     * @throws InvalidParamException
     * @throws NotSupportedException
     */
    protected function populateFormName($model, $formName)
    {
        if (!$model instanceof ActiveRecord) {
            throw new InvalidParamException('Param $model must be an instance of ' . ActiveRecord::className() . '.');
        } elseif (YII_DEBUG) {
            static $hasMethod = null;
            
            if ($hasMethod === null) {
                $hasMethod = (bool) $model->hasMethod('populateFormName', false);
            }
            if (!$hasMethod && !$model->hasMethod('populateFormName', true)) {
                throw new NotSupportedException('You must to override & to implement your own logic of ' . __METHOD__ . '.');
            }
        }
        return $model->populateFormName($formName);
    }

    /**
     * @param ActiveRecord $model
     * @return string rendered content.
     * @throws InvalidParamException
     */
    protected function renderView($model)
    {
        if (!$model instanceof ActiveRecord) {
            throw new InvalidParamException('Param $model must be an instance of ' . ActiveRecord::className() . '.');
        } elseif ($this->submodelView instanceof \Closure) {
            return call_user_func($this->submodelView, $this->field->form, $model, $this, $this->viewData);
        } else {
            return $this->renderFile($this->submodelView, [
                'form' => $this->field->form,
                'model' => $model,
                'widget' => $this,
                'data' => $this->viewData,
            ]);
        }
    }

    /**
     * @return One2Many
     */
    public function getLink()
    {
        return $this->model->getLink($this->attribute);
    }

    /**
     * @return array in serialized => attribute data format.
     */
    protected function extractFormAttributes()
    {
        $result = [];
        
        foreach ($this->field->form->attributes ?: [] as $attribute) {
            $result[Json::encode($attribute)] = $attribute;
        }
        
        return $result;
    }

    /**
     * @var string|null
     */
    private $_keyPlaceholder;

    /**
     * @return string
     */
    public function getKeyPlaceholder()
    {
        if ($this->_keyPlaceholder !== null) {
            return $this->_keyPlaceholder;
        }
        
        return $this->_keyPlaceholder = strtr(self::TEMPLATE_KEY_PLACEHOLDER, [
            '{widgetId}' => $this->getId(),
        ]);
    }
}
