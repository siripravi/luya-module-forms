<?php

namespace siripravi\forms;

class Module extends \luya\forms\Module{

     /**
     * {@inheritDoc}
     */
    public function getMenu()
    { return true;
        /*return (new \luya\admin\components\AdminMenuBuilder($this))
            ->node('Forms', 'dynamic_form')
                ->group('Setup')
                    ->itemApi('Form', 'forms/form/index', 'dynamic_form', 'api-forms-form')
                ->group('Data')
                    ->itemApi('Submission', 'forms/submission/index', 'send', 'api-forms-submission')
                    ->itemApi('Values', 'forms/submission-value/index', 'label', 'api-forms-submissionvalue', ['hiddenInMenu' => true]);
                    */
    }
}