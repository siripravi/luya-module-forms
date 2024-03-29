<?php

namespace siripravi\forms\blocks;

use luya\cms\base\PhpBlock;
use siripravi\forms\blockgroups\FormCollectionGroup;
use luya\helpers\StringHelper;
use Yii;

/**
 * Summary
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class SummaryBlock extends PhpBlock
{
    public $template = '<p>{{label}}: {{value}}</p>';

    public $isContainer = false;

    public function blockGroup()
    {
        return FormCollectionGroup::class;
    }

    public function config()
    {
        return [
            'vars' => [
                ['var' => 'template', 'label' => Yii::t('app', 'Row Template'), 'type' => self::TYPE_TEXTAREA, 'placeholder' => $this->template],
            ]
        ];
    }

    public function getFieldHelp()
    {
        return [
            'template' => Yii::t('app', 'The variables {{label}} and {{value}} are available.'),
        ];
    }

    public function admin()
    {
        return '<div class="alert alert-info border-0 text-center">Summary / Preview</div>';
    }

    public function name()
    {
        return Yii::t('app', 'Summary');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'description';
    }

    public function frontend()
    {
        Yii::$app->forms->loadModel();
        $html = null;
        $model = Yii::$app->forms->model;
        foreach ($model->attributes as $k => $v) {
            if ($model->isAttributeInvisible($k)) {
                continue;
            }

            $html .= StringHelper::template($this->getVarValue('template', $this->template), [
                'label' => $model->getAttributeLabel($k),
                'value' => $model->formatAttributeValue($k, $v),
            ]);
        }

        return $html;
    }
}
