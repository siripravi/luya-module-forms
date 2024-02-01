<?php

namespace siripravi\forms\blocks;

use luya\cms\base\PhpBlock;
use luya\forms\blockgroups\FormGroup;
use luya\forms\FieldBlockTrait;
use luya\helpers\ArrayHelper;
use kartik\date\DatePicker;

use Yii;
use yii\validators\DateValidator;

/**
 * DatePicker using HTML type "date"
 *
 * The date (value) is always formatted according to ISO8601
 *
 * @since 1.3.0
 * @author Basil Suter <git@nadar.io>
 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/date
 */
class DatepickerBlock extends PhpBlock
{
    use FieldBlockTrait {
        config as parentConfig;
    }

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return FormGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return Yii::t('app', 'My Datepicker');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'date_range';
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        $config = $this->parentConfig();
        // remove validator
        unset($config['vars'][4]);
        return ArrayHelper::merge($config, [
            'cfgs' => [
                ['var' => 'disablePolyfill', 'label' => Yii::t('forms', 'Disable Polyfill'), 'type' => self::TYPE_CHECKBOX],
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldHelp()
    {
        return [
            'disablePolyfill' => Yii::t('forms', 'When enabled, the polyfill which ensures the datepicker works on Safari and Internet Explorer is disabled.'),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param {{vars.field}}
     * @param {{vars.hint}}
     * @param {{vars.label}}
     */
    public function admin()
    {
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">' . Yii::t('app', 'My Datepicker') . '</span></div>';
    }

    public function frontend()
    {
        if (!$this->getCfgValue('disablePolyfill', false)) {
            //  Yii::$app->view->registerJsFile('//cdn.jsdelivr.net/npm/better-dom@4.1.0/dist/better-dom.min.js');
            //   Yii::$app->view->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/better-dateinput-polyfill/3.3.1/better-dateinput-polyfill.min.js', [], 'nodep-date-input-polyfil');
        }

        Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            $this->getVarValue($this->varRule, $this->defaultRule),
            /* [
                DateValidator::class,
                ['format' => 'dd-MM-yyyy']
            ],*/
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint),
            $this->getVarValue($this->varFormatAs)
        );

        $varName = $this->getVarValue($this->varAttribute);
        if (!$varName) {
            return;
        }

         $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName,['options' => ['class'=>'form-outline mb-4 datepicker']]);

        
         return $activeField->widget(DatePicker::class,[
            'model' => Yii::$app->forms->model,
            'attribute' => $varName,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'options' => ['placeholder' => 'Enter Delivery date ...'],
            'pluginOptions' => [
               // 'orientation' => 'top right',
                'format' => 'mm/dd/yyyy',
                'autoclose' => true,
            ]
        ]);
    }
}
