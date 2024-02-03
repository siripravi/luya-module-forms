<?php

namespace siripravi\forms;

use luya\cms\helpers\BlockHelper;
use Yii;

/**
 * A trait for form field blocks.
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
trait FieldBlockTrait
{
    public $defaultRule = 'safe';

    public $varAttribute = 'attribute';

    public $varLabel = 'label';

    public $varHint = 'hint';

    public $varRule = 'rule';

    public $varIsRequired = 'isRequired';

    public $varFormatAs = 'formatAs';

    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                ['var' => $this->varAttribute, 'label' => Yii::t('app', 'Attribute'), 'required' => true, 'type' => self::TYPE_TEXT],
                ['var' => $this->varLabel, 'label' => Yii::t('app', 'Label'), 'required' => true, 'type' => self::TYPE_TEXT],
                ['var' => $this->varHint, 'label' => Yii::t('app', 'Hint'), 'type' => self::TYPE_TEXTAREA],
                ['var' => $this->varIsRequired, 'label' => Yii::t('app', 'Required'), 'type' => self::TYPE_CHECKBOX],
                ['var' => $this->varRule, 'label' => Yii::t('app', 'Validation Rule'), 'required' => true, 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    'safe' => Yii::t('app', 'Everything allowed'),
                    'string' => Yii::t('app', 'String'),
                    'number' => Yii::t('app', 'Number'),
                    'email' => Yii::t('app', 'Email'),
                    'boolean' => Yii::t('app', 'Boolean'),
                    'date' => Yii::t('app', 'Date'),
                 ])],
                ['var' => $this->varFormatAs, 'label' => Yii::t('app', 'Formatting'), 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption([
                    null => Yii::t('app', 'Automatically'),
                    'boolean' => Yii::t('app', 'Boolean (Yes/No)'),
                    'date' => Yii::t('app', 'Date'),
                    'datetime' => Yii::t('app', 'Date & Time'),
                    'ntext' => Yii::t('app', 'Multiline Text'),
                    'url' => Yii::t('app', 'URL/Link'),
                    'image' => Yii::t('app', 'Image'),
                ])]
            ],
        ];
    }
}
