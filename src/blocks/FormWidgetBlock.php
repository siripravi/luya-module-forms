<?php

namespace siripravi\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use siripravi\forms\blockgroups\FormGroup;
use siripravi\forms\FieldBlockTrait;
use luya\cms\helpers\BlockHelper;
use kartik\builder\Form;

/**
 * Text Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class FormWidgetBlock extends PhpBlock
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
    public function config()
    {
        return [
            'vars' => [
               
                [
                    'var' => 'attributes', 
                    'label' => 'form attributes', 
                    'type' => self::TYPE_MULTIPLE_INPUTS, 
                    'options' => [
                        [
                            'var' => 'fld_name', 
                            'type' => self::TYPE_TEXT, 
                            'label' => 'Field Name'
                        ],
                        [
                            'var' => 'fld_lbl', 
                            'type' => self::TYPE_TEXT, 
                            'label' => 'Field Title'
                        ],
                       
                        [   
                            'var' => 'fld_type', 
                            'label' => Yii::t('app', 'Type'),
                            'type' => self::TYPE_RADIO,
                            'options' => BlockHelper::radioArrayOption([
                                $this-> getInputTypes()
                            ])
                        ],
                       
                    ]
            ],             
            ],
            'cfgs' => [
                [
                    'var' => 'modelClass',
                    'type' => self::TYPE_TEXT, 
                    'label' => 'Class Name'
               ],
                ],
            'placeholders' => [
                ['var' => 'wgtcontent', 'label' => Yii::t('app', 'Form')],
               // ['var' => 'preview', 'label' => Yii::t('app', 'Preview')],
              //  ['var' => 'success', 'label' => Yii::t('app', 'Success')],
            ],
        ];
    }
    /**
     * @inheritDoc
     */
    public function extraVars()
    {
        return [
            //'isSubmit' => $this->isSubmit(),
            // 'invokeSubmitAndStore' => $this->submitAndStore(),
            // 'isPreview' => $this->getVarValue('confirmStep') && $this->isLoadedValidModel(),
            'formWgt' => $this->getFormWgt(),
            'formMdl' => $this->getFormMdl()
        ];
    }
    /**
     * @inheritDoc
     */
    public function name()
    {
        return Yii::t('app', 'Text');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'message';
    }

    /**
     * @inheritDoc
     */
    /* public function config()
    {
        return ArrayHelper::merge($this->parentConfig(), [
            'vars' => [
                ['var' => 'isTextarea', 'label' => Yii::t('app', 'Multiline Input'), 'type' => self::TYPE_CHECKBOX],
            ],
            'cfgs' => [
                ['var' => 'textareaRows', 'label' => Yii::t('app', 'Multiline Rows'), 'type' => self::TYPE_NUMBER],
                ['var' => 'hiddenInputValue', 'label' => Yii::t('app', 'As Hidden Input Value'), 'type' => self::TYPE_TEXT],
            ]
        ]);
    }*/

    /*  public function getFieldHelp()
    {
        return [
            'hiddenInputValue' => Yii::t('app', 'block_hiddenInputValue_help'),
        ];
    }*/

    /**
     * {@inheritDoc}
     *
     * @param {{vars.field}}
     * @param {{vars.hint}}
     * @param {{vars.label}}
     */
    public function admin()
    {
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">' . Yii::t('app', 'Form Widget') . '</span></div>';
    }
    /**
     * {@inheritDoc}
     */

    public function setup()
    {
       
    }

    public function getInputTypes(){
        return [
            Form::INPUT_TEXT => "Text Box",
            Form::INPUT_TEXTAREA => "Textarea",
            Form::INPUT_PASSWORD => "password",
            Form::INPUT_DROPDOWN_LIST => "Drowdown List",
            Form::INPUT_LIST_BOX => "List Box",
            Form::INPUT_CHECKBOX  => "Checkbox",
            Form::INPUT_RADIO  => "Rado Button",
            Form::INPUT_CHECKBOX_LIST => "Checkbox List",
            Form::INPUT_RADIO_LIST => "Radio List",
            Form::INPUT_MULTISELECT => "Multi Select",
            Form::INPUT_FILE => "File Input",
            Form::INPUT_HTML5 => "HTML Content",
            Form::INPUT_WIDGET => "Input Widget",
            Form::INPUT_STATIC => "Static Content",
            Form::INPUT_HIDDEN => "Hidden field",
            Form::INPUT_HIDDEN_STATIC => "Hidden Static",
            Form::INPUT_RAW => "Raw Input"
        ];
    }


    /**
     * {@inheritDoc}
     */
    public function frontend()
    {
        /*Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            $this->getVarValue($this->varRule, $this->defaultRule),
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint),
            $this->getVarValue($this->varFormatAs)
        );

        $varName = $this->getVarValue($this->varAttribute);
        if (!$varName) {
            return;
        }

        $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName);

        $hiddenInputValue = $this->getCfgValue('hiddenInputValue');

        if (!empty($hiddenInputValue)) {
            return $activeField->hiddenInput(['value' => $hiddenInputValue])->label(false);
        }

        return $this->getVarValue('isTextarea') ? $activeField->textArea(['rows' => $this->getCfgValue('textareaRows', null)]) : $activeField->textInput();
    */

        $formWidget = Form::widget([
            'model' => Yii::$app->forms->model,
            'form' => Yii::$app->forms->form
        ]);
    }

    public function getFormWgt()
    {
    }

    public function getFormMdl()
    {
    }
}
