<?php

namespace siripravi\forms\blocks;

use luya\cms\base\PhpBlock;
use siripravi\forms\blockgroups\FormCollectionGroup;
use siripravi\forms\FieldBlockTrait;
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
class RadioListBlock extends PhpBlock
{
    use FieldBlockTrait {
        config as parentConfig;
    }

    public $radListData = [];

    public function setup()
    {
        // Yii::$app->forms->model->

        Yii::trace("RADIO LIST INIT DATA");
        Yii::$app->forms->loadModel();
        $model = Yii::$app->forms->model;
        if($model){
        $this->radListData = $model->Features;
        //ArrayHelper::getColumn($model->Features, 'rList');
       // $names =  ArrayHelper::getColumn($model->Features, 'name'); 

        }
        /* [
            1 =>[
            1 => 'One', 2 => 'Two', 3 => 'Three'
            ]
        ];*/
        //   Yii::trace("Features:".print_r($this->radListData),true);
       // echo "<pre>";print_r($names); die;
    }

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
        return Yii::t('app', 'Radio List');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'list';
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
        return '<div>{{vars.label}} <span class="badge badge-secondary float-right">' . Yii::t('app', 'Radio List') . '</span></div>';
    }

    public function frontend()
    {
        Yii::$app->forms->loadModel();
        $model = Yii::$app->forms->model;
        if($model){
        $this->radListData = $model->Features;
       
        }
        //print_r($this->radListData); die;
        \Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            $this->getVarValue($this->varRule, $this->defaultRule),
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint)
        );

        $varName = $this->getVarValue($this->varAttribute);
        if (!$varName) {
            return;
        }
       
        $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName);

        $values = ArrayHelper::combine(ArrayHelper::getColumn($this->getVarValue('values', []), 'value'));
        $output = "";

        foreach ($this->radListData as $id => $feature) {
             $output .='<span>'.$feature['name'].'</spn>';
            $values = $feature['rList'];
            $activeField = Yii::$app->forms->form->field(Yii::$app->forms->model, $varName . '[' . $id . ']');
            $output .= $activeField->radioList($values, [ 'separator' => $this->getCfgValue('separator', "\n")
            ])->label(false);
        }
        return $output;
        /* return $activeField->widget(DatePicker::class,[
            'model' => Yii::$app->forms->model,
            'attribute' => $varName,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'options' => ['placeholder' => 'Enter Delivery date ...'],
            'pluginOptions' => [
               // 'orientation' => 'top right',
                'format' => 'mm/dd/yyyy',
                'autoclose' => true,
            ]
        ]);*/
    }
}
