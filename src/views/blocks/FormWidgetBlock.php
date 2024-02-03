<?php

/**
 * View file for block: FormBlock
 *
 * File has been created with `block/create` command.
 *
 * @param $this->placeholderValue('content');
 * @param $this->varValue('formId');
 *
 * @var \luya\cms\base\PhpBlockView $this
 */
use Yii;

use kartik\builder\Form;
?>
<?php
$modelClass = $this->cfgValue('modelClass');
$model = Yii::createObject($modelClass);
echo Form::widget([
    'model'=>$model,
    'form'=>Yii::$app->forms->form,
    'columns'=>1,
    'attributes'=>
        $this->varValue('attributes')
    ]);?>
    <?= $this->placeholderValue('wgtcontent'); ?>   

<?php Yii::$app->forms->form->end(); ?>