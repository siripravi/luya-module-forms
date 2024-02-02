<?php

namespace siripravi\forms\blocks;

use Yii;

use luya\Exception;
use luya\forms\blockgroups\FormGroup;
use luya\forms\Model;
use luya\forms\models\Form;

/**
 * Form Block.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class FormBlock extends \luya\forms\blocks\FormBlock
{
    public $module = "forms";

    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'formId',
                    'label' => Yii::t('forms', 'Form'),
                    //  'type' => self::TYPE_SELECT_CRUD,
                    'type' => self::TYPE_CHECKBOX,
                    'required' => true,
                    'value' => 1
                    //  'options' => ['route' => 'forms/form/index', 'api' => 'admin/api-forms-form', 'fields' => ['title']]
                ],
                [
                    'var' => 'confirmStep',
                    'label' => Yii::t('forms', 'Confirmation Step'),
                    'type' => self::TYPE_CHECKBOX,
                    'value' => 1
                ],
            ],
            'cfgs' => [
                [
                    'var' => 'doNotSaveData',
                    'label' => Yii::t('forms', 'Do not save data'),
                    'type' => self::TYPE_CHECKBOX,
                    'value' => 1
                ],
                //    ['var' => 'submitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_submitButtonLabel'), 'placeholder' => Yii::t('forms', 'Submit')],
                //    ['var' => 'previewSubmitButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewSubmitButtonLabel'), 'placeholder' => Yii::t('forms', 'Submit')],
                //     ['var' => 'previewBackButtonLabel', 'type' => self::TYPE_TEXT, 'label' => Yii::t('forms', 'form_label_previewBackButtonLabel'), 'placeholder' => Yii::t('forms', 'Back')],
                //    ['var' => 'previewButtonsTemplate', 'type' => self::TYPE_TEXTAREA, 'label' => Yii::t('forms', 'form_label_previewButtonsTemplate'), 'placeholder' => $this->previewButtonsTemplate],
            ],
            'placeholders' => [
                ['var' => 'content', 'label' => Yii::t('forms', 'Form')],
                ['var' => 'preview', 'label' => Yii::t('forms', 'Preview')],
                ['var' => 'success', 'label' => Yii::t('forms', 'Success')],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function extraVars()
    {
        return [
            'isSubmit' => $this->isSubmit(),
            'invokeSubmitAndStore' => $this->submitAndStore(),
            'isPreview' => $this->getVarValue('confirmStep') && $this->isLoadedValidModel(),
        ];
    }

    /**
     * Check submit state based on different scenarios
     *
     * @return boolean Whether the form is in submited state or not
     */
    public function isSubmit()
    {

        // when confirmm step is disabled, but review is loaded, this is equals to a submit:
        if (!$this->getVarValue('confirmStep') && $this->isLoadedValidModel()) {
            return true;
        }

        $isSubmit = Yii::$app->request->get('submit', false);
        Yii::debug('Fom submitting Finally...' . $isSubmit . '==' . $this->getVarValue('formId'));
        return $isSubmit && $isSubmit == $this->getVarValue('formId');
    }

    public function name()
    {
        return 'My Forms';
    }
    /**
     * @var boolean Choose whether block is a layout/container/segmnet/section block or not, Container elements will be optically displayed
     * in a different way for a better user experience. Container block will not display isDirty colorizing.
     */
    public $isContainer = true;

    public $review = false;

    //public $previewButtonsTemplate = '<div class="forms-preview-buttons-container">{{back}}<span class="forms-divider"> | </span>{{submit}}</div>';
    public $previewButtonsTemplate = '';

    /**
     * {@inheritDoc}
     */

    public function setup()
    {
        Yii::debug('from block setup invocation', __METHOD__);
        Yii::debug($this->getEnvOption('context', false));
        $object = Yii::$app->forms->activeFormClass;
        $model = 'luya\forms\Model';

        $begin = Yii::$app->forms->activeFormClassOptions;
        Yii::$app->forms->model = new $model();   //Yii::$app->forms->model->isPjax = false;
        if ($this->isFrontendContext()) {
            $model = Yii::$app->menu->current->getPropertyValue('model');
            Yii::$app->forms->model = new $model();
            $begin = Yii::$app->forms->model->activeFormClassOptions;
            Yii::$app->forms->pjaxClassOptions =  Yii::$app->forms->model->pjaxOptions;
        }
        Yii::$app->forms->beginForm($object::begin($begin), $model);
        if ((!(basename($model) == "Model")) && Yii::$app->forms->model->isPjax)
            \yii\widgets\Pjax::begin([]);
    }

    /**
     * Invokes the model submiting process and redirects the browsers if needed
     *
     * @return void
     */
    public function submitAndStore()
    {
        if ($this->isSubmit()) {           
            $data = Yii::$app->forms->getFormData();
            Yii::trace('Session Form: ' . print_r($data, true));
            if (!empty($data)) {
                $model = Yii::$app->forms->model;
                if (Yii::$app->forms->isModelValidated || $model->validate($model->getAttributesWithoutInvisible())) {

                    if (method_exists(get_class($model), 'getAfterSaveEvent')) {
                        $event = $model->getAfterSaveEvent($model);
                        $model->trigger(get_class($model)::EVENT_AFTER_VALID, $event);
                    }
                    Yii::$app->forms->cleanup();
                   
                    Yii::$app->session->setFlash('formDataSuccess');
                    Yii::$app->response->redirect($model->redirectUrl);

                    return Yii::$app->end();
                }
            }
        }
    }
}
