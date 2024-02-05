<?php

namespace siripravi\forms\blockgroups;

use luya\cms\base\BlockGroup;

/**
 * Form Group
 *
 * @author Basil Suter <git@nadar.io>
 * @since 1.0.0
 */
class FormCollectionGroup extends BlockGroup
{
    /**
     * {@inheritDoc}
     */
    public function identifier()
    {
        return 'forms-collection-group';
    }

    /**
     * {@inheritDoc}
     */
    public function label()
    {
        return 'Forms';
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return 100;
    }
}