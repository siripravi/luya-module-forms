<?php

namespace siripravi\forms\blocks;

use Yii;
use luya\cms\base\PhpBlock;
use siripravi\forms\blockgroups\FormCollectionGroup;
use siripravi\forms\FieldBlockTrait;
use luya\helpers\ArrayHelper;

/**
 * Text Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class CheckboxesBlock extends PhpBlock
{
    use FieldBlockTrait { config as parentConfig; }

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return FormCollectionGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return Yii::t('app', 'Checkboxes');
    }
    
    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'radio_button_checked';
    }

    public function config()
    {
        return ArrayHelper::merge([
            'vars' => [
                [
                    'var' => 'values',
                    'label' => Yii::t('app', 'Values'),
                    'type' => self::TYPE_LIST_ARRAY
                ],
            ],
        ], $this->parentConfig());
    }

    public function getFieldHelp()
    {
        return [
            'values' => Yii::t('app', 'checkboxes_values_help'),
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">'.Yii::t('app', 'Checkboxes').'</span></div>';
    }

    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute(
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

        $values = ArrayHelper::combine(ArrayHelper::getColumn($this->getVarValue('values', []), 'value'));

        $options = [];


        // list of checkboxes
        if (count($values) > 1) {
            return $activeField->checkboxList($values);
        }

        // if required, when not checked the submited value should be empty otherwise the required validator will not
        // catch the value `0` as required.
        if ($this->getVarValue($this->varIsRequired)) {
            $options['uncheck'] = null;
        }

        // its a single checkbox, set the value as label
        $label = reset($values);
        if (!empty($label)) {
            $options['label'] = $label;
        }

        return $activeField->checkbox($options);
    }
}
