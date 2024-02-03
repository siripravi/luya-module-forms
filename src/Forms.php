<?php

namespace siripravi\forms;

use luya\helpers\ArrayHelper;

use Yii;

//use yii\widgets\ActiveForm;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use luya\forms\Model;
use yii\base\Component;

/**
 * Forms Component
 *
 * @property ActiveForm $form
 * @property Model $model
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class Forms extends Component
{
    const EVENT_AFTER_SAVE = 'afterSave';
    //const EVENT_AFTER_VALID = 'afterValidate';

    /**
     * @var string The session variable name
     */
    public $sessionFormDataName = '';

    /**
     * @var string The Active Form class, for configurations options see {{$activeFormClassOptions}}.
     */
    public $activeFormClass = 'yii\widgets\ActiveForm'; //'yii\widgets\ActiveForm';
    public $pjaxFormClass = "yii\widgets\Pjax";
    public $isPjax = false;
    /**
     * @var array A configuration array which will be passed to ActiveForm::begin($options). Example usage `['enableClientValidation' => false]`
     */
    public $activeFormClassOptions = [];
    public $pjaxClassOptions = [];
    /**
     * @var array An array of options which will be passed to {{ Html::submitButton(..., $options)}} submit buttons.
     */
    public $submitButtonsOptions = ['class' => 'btn btn-success btn-buy px-2'];

    /**
     * @var array An array of options which will be passed to {{ Html::submitButton(..., $options)}} back buttons.
     */
    public $backButtonOptions = [];

    /**
     * @var boolean Indicates whether the current model has been loaded or not. This does not say anything about whether loading was successfull
     * or not.
     * @since 1.3.0
     */
    public $isModelLoaded = false;

    /**
     * @var boolean Indicates whether the curent model is loaded AND sucessfull validated.
     * @since 1.4.2
     */
    public $isModelValidated = false;

    /**
     * @var ActiveForm
     */
    private $_form;

    private $_pjax;

    /**
     * @var Model
     */
    private $_model;

    public function beginForm(ActiveForm $form, String $model = 'siripravi\forms\Model')
    {
        $this->_form = $form;
        $this->_model = new $model();
        $this->sessionFormDataName = "__" . basename($model);
    }
/**
     * Initialize the form and the model
     *
     * @param ActiveForm $form
     */
    public function startForm(ActiveForm $form)
    {
        $this->_form = $form;
        $this->_model = new Model();
    }
    public function beginPjaxForm(Pjax $pjax)
    {
        $this->_pjax = $pjax;
    }

    public function getPjax()
    {
        return $this->_pjax;
    }
    /**
     * Active Form Getter
     *
     * @return ActiveForm
     */
    public function getForm()
    {
        return $this->_form;
    }

    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Model Getter
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * Clean up the session and destroy model and form
     */
    public function cleanup()
    {
        Yii::$app->session->remove($this->sessionFormDataName);
        $this->_model = null;
        $this->_form = null;
        $this->isModelValidated = false;
        $this->isModelLoaded = false;
    }

    /**
     * Loads the data from the post request into the model, validates it and stores the data in the session.
     *
     * @return boolean Whether loading the model with data was successfull or not (if not a validation error may persists in the $model).
     */
    public function loadModel()
    {
        if ($this->isModelValidated) {
            return true;
        }

        if (!Yii::$app->request->isPost  && method_exists($this->model, 'getBeforeLoadModelEvent')) {
            $event = $this->model->getBeforeLoadModelEvent($this->model);
            $this->model->trigger(get_class($this->model)::EVENT_BEFORE_LOAD, $event);          
        }

        if (!Yii::$app->request->isPost || !$this->model) {
            return false;
        }

        $postData = Yii::$app->request->post();  
       /* $modelClass = basename(get_class($this->model));
        $this->sessionFormDataName = "__" . $modelClass;*/
        Yii::trace("FORM SUBMIT:".print_r($postData,true));
        $postKeys = array_keys($postData[basename(get_class($this->model))]);
       // Yii::$app->session->set($this->sessionFormDataName, $this->model->getAttributes($postKeys));
        $this->isModelLoaded = $this->model->load($postData);
        Yii::$app->session->set($this->sessionFormDataName, $this->model->getAttributes($postKeys));
       // Yii::info('SESSION: ' . print_r($this->model->attributes, true));
        if ($this->isModelLoaded && $this->model->validate()) {
       
            $this->isModelValidated = true;
            return true;
        }
        return false;
    }

    /**
     * Get all form values which are stored trough {{loadModel()}}.
     *
     * @return array An array with attribute name and value
     */
    public function getFormData()
    {
        $modelClass = basename(get_class($this->model));
        $this->sessionFormDataName = "__" . $modelClass;
        return ArrayHelper::typeCast(Yii::$app->session->get($this->sessionFormDataName, []));
    }

      /**
     * Auto configures a gien attribute into the model.
     *
     * The following steps will be done when using auto configure attribute:
     *
     * + The $attributeName will be added to the form model with the given $role
     * + When required is enabled, the required rule will be set or not.
     * + If there is already a value from session data, the value will be inject into the model, this is mainly used for preview.
     * + label and hint informations will be assigned
     * + if a specific format type is provided, the formatter will be taken to format the value when previewing or storing the value.
     *
     * @param string $attributeName
     * @param string $rule
     * @param boolean $isRequired
     * @param string $label
     * @param string $hint
     * @param string $formatAs
     */
    public function autoConfigureAttribute($attributeName, $rule, $isRequired, $label = null, $hint = null, $formatAs = null)
    {
        Yii::debug('configure form attribute: ' . $attributeName, __METHOD__);

        $this->createAttribute($attributeName, $rule);

        if ($isRequired) {
            $this->setAttributeRule($attributeName, 'required');
        }

        $value = $this->getFormDataAttributeValue($attributeName);

        if (!empty($value)) {
            $this->setAttributeValue($attributeName, $value);
        }

        $this->setAttributeLabel($attributeName, $label);
        $this->setAttributeHint($attributeName, $hint);
        $this->setAttributeFormat($attributeName, $formatAs);
    }

     /**
     * Create a new attribute with a required default rule
     *
     * @param string $attributeName
     * @param string|array $rule Providing a rule by array means the first element of the array must be the rule, while the second
     * an array with the options. `[RequireValidator::class, ['skipOnEmpty' => true]]`
     */
    public function createAttribute($attributeName, $rule = 'safe')
    {
        $this->model->defineAttribute($attributeName);

        // [RequireValidator::class, ['foo' => 'bar']]
        $options = [];
        if (is_array($rule)) {
            list($rule, $options) = $rule;
        }

        $this->setAttributeRule($attributeName, $rule, $options);
    }

    /**
     * Attribute Rule
     *
     * @param string $attributeName
     * @param string $rule
     * @param array $options
     *
     */
    public function setAttributeRule($attributeName, $rule, $options = [])
    {
        $this->model->addRule([$attributeName], $rule, $options);
    }

    /**
     * Set attribute value
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function setAttributeValue($attribute, $value)
    {
        $this->model->{$attribute} = $value;
    }
    
    /**
     * Attribute Label
     *
     * @param string $attribute
     * @param string $label
     */
    public function setAttributeLabel($attribute, $label)
    {
        $this->model->setAttributeLabel($attribute, $label);
    }

    /**
     * Attribute Hint
     *
     * @param string $attribute
     * @param string $hint
     */
    public function setAttributeHint($attribute, $hint)
    {
        $this->model->_attributeHints[$attribute] = $hint;
    }

    /**
     * Attribute Format
     *
     * @param string $attribute
     * @param string $formatAs
     */
    public function setAttributeFormat($attribute, $formatAs)
    {
        if ($formatAs && !empty($formatAs)) {
            $this->model->formatters[$attribute] = $formatAs;
        }
    }
    /**
     * Return the value for a given attribute form the form data
     *
     * @param string $attributeName
     * @return mixed
     */
    public function getFormDataAttributeValue($attributeName)
    {
        $data = $this->getFormData();

        $value = isset($data[$attributeName]) ? $data[$attributeName] : null;

        // the value is empty and the form is not yet loaded
        // lets try to extract the values from the post data for now
        // because the model loading can only work when all attributes are stored
        // which is after the form attributes are defined!
        if (empty($value) && !$this->isModelLoaded) {
            return $this->postAttributeValue($attributeName);
        }

        return $value;
    }

    /**
     * Get the attribute value for a given value from the post data
     *
     * @param string $attribute
     * @return mixed
     * @since 1.3.0
     */
    public function postAttributeValue($attribute)
    {
        if (!Yii::$app->request->isPost) {
            return [];
        }

        $data = Yii::$app->request->post($this->model->formName(), []);
        
        if (!is_array($data)) {
            return null;
        }

        return array_key_exists($attribute, $data) ? $data[$attribute] : null;
    }

}
