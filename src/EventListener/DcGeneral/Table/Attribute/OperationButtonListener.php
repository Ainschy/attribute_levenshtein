<?php

/**
 * This file is part of MetaModels/attribute_levenshtein.
 *
 * (c) 2012-2018 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeLevenshteinBundle
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_levenshtein/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\AttributeLevenshteinBundle\EventListener\DcGeneral\Table\Attribute;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetOperationButtonEvent;
use ContaoCommunityAlliance\DcGeneral\Event\AbstractEnvironmentAwareEvent;

/**
 * This provides the attribute name options.
 */
class OperationButtonListener extends AbstractListener
{
    /**
     * Hide the rebuild button for non levenshtein attributes.
     *
     * @param GetOperationButtonEvent $event The event.
     *
     * @return void
     */
    public function handle(GetOperationButtonEvent $event)
    {
        if (!$this->wantToHandle($event)) {
            return;
        }

        if ('levensthein' !== $event->getModel()->getProperty('type')) {
            $event->setDisabled();
        }
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
        /** @var GetOperationButtonEvent $event */
        return parent::wantToHandle($event)
               && 'rebuild_levensthein' === $event->getCommand()->getName();
    }
}