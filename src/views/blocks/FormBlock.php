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

use luya\helpers\Html;
use yii\helpers\Url;
use luya\helpers\StringHelper;
use lav45\widget\AjaxCreate;
use yii\bootstrap5\Modal;
?>
<?php
/*AjaxCreate::begin();
echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
    'data-href' => Url::toRoute(['create']),
    'class' => 'btn btn-success',
]);
AjaxCreate::end();*/
?>
<?php if (Yii::$app->session->getFlash('formDataSuccess')) : ?>
    <?= $this->placeholderValue('success'); ?>
<?php else : ?>
    <?php if ($this->extraValue('isPreview')) : ?>
        <?= $this->placeholderValue('preview'); ?>
        <?= StringHelper::template($this->varValue('previewButtonsTemplate', $this->context->previewButtonsTemplate), [
            'back' => Html::a($this->cfgValue('previewBackButtonLabel', Yii::t('forms', 'Back')), '?reload=' . $this->varValue('formId'), Yii::$app->forms->backButtonOptions),
            'submit' => Html::a($this->cfgValue('previewSubmitButtonLabel', Yii::t('forms', 'Submit')), '?submit=' . $this->varValue('formId'), Yii::$app->forms->submitButtonsOptions),
        ]); ?>
    <?php else : ?>
        <?php if (Yii::$app->forms->model->hasErrors()) : ?>
            <?= Yii::$app->forms->form->errorSummary(Yii::$app->forms->model); ?>
        <?php endif; ?>
        <?= $this->placeholderValue('content'); ?>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-primary btn-lg" type="submit" data-mdb-ripple-init><?=$this->cfgValue('submitButtonLabel', Yii::t('forms', 'Submit'));?></button>
           <!--= Html::submitButton($this->cfgValue('submitButtonLabel', Yii::t('forms', 'Submit')), Yii::$app->forms->submitButtonsOptions); ?-->
        </div>
        
    <?php endif; ?>
<?php endif; ?>

<?php if ((!(get_class(Yii::$app->forms->model) == "Model")) && Yii::$app->forms->model->isPjax) : ?>
    <?= get_class(Yii::$app->forms->model); ?>
    <?php \yii\widgets\Pjax::end(); ?> <!-- end of pjax-->
<?php endif; ?>
<?php Yii::$app->forms->form->end(); ?>
<?php if (empty($this->varValue('formId'))) : ?>
    <div style="background-color:red; color:white; padding:20px;"><?= Yii::t('forms', 'This form block is not properly configured. Select a form from the listing in the block settings.'); ?></div>
<?php endif; ?>