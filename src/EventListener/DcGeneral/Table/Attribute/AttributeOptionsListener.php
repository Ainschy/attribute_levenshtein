<?php

/**
 * This file is part of MetaModels/attribute_levenshtein.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_levenshtein
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_levenshtein/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeLevenshteinBundle\EventListener\DcGeneral\Table\Attribute;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\Event\AbstractEnvironmentAwareEvent;
use MetaModels\Attribute\IInternal;

/**
 * This provides the attribute name options.
 */
class AttributeOptionsListener extends AbstractListener
{
    /**
     * Retrieve the options for the attributes.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function handle(GetPropertyOptionsEvent $event)
    {
        if (!$this->wantToHandle($event)) {
            return;
        }

        $model       = $event->getModel();
        $metaModelId = $model->getProperty('pid');
        if (!$metaModelId) {
            $metaModelId = ModelId::fromSerialized(
                $event->getEnvironment()->getInputProvider()->getValue('pid')
            )->getId();
        }

        $metaModel = $this->getMetaModel($metaModelId);

        if (!$metaModel) {
            return;
        }

        $result = [];

        // Fetch all attributes except for the current attribute and same type.
        foreach ($metaModel->getAttributes() as $attribute) {
            if ($attribute->get('id') === $model->getId()
            || $attribute->get('type') === $model->getProperty('type')) {
                continue;
            }

            // Hide virtual types.
            if ($attribute instanceof IInternal) {
                continue;
            }

            $result[$attribute->getColName()] = \sprintf(
                '%s [%s, "%s"]',
                $attribute->getName(),
                $attribute->get('type'),
                $attribute->getColName()
            );
        }

        $event->setOptions($result);
    }

    /**
     * Test if the event is for the correct table and in backend scope.
     *
     * @param AbstractEnvironmentAwareEvent $event The event to test.
     *
     * @return bool
     */
    protected function wantToHandle(AbstractEnvironmentAwareEvent $event)
    {
        /** @var GetPropertyOptionsEvent $event */
        $input = $event->getEnvironment()->getInputProvider();
        $type  = $event->getModel()->getProperty('type');

        if ($input->hasValue('type')) {
            $type = $input->getValue('type');
        }

        if (empty($type)) {
            $type = $event->getModel()->getProperty('type');
        }

        return parent::wantToHandle($event)
               && ('levensthein' === $type)
               && ('levensthein_attributes' === $event->getPropertyName());
    }
}
