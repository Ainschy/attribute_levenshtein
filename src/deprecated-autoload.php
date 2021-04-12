<?php

/**
 * This file is part of MetaModels/attribute_levensthein.
 *
 * (c) 2012-2021 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_levensthein
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2021 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_levensthein/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

use MetaModels\AttributeLevenshteinBundle\Attribute\AttributeLevenshtein;
use MetaModels\AttributeLevenshteinBundle\Attribute\LevenshteinAttributeTypeFactory;
use MetaModels\AttributeLevenshteinBundle\EventListener\DcGeneral\Table\FilterSetting\TypeRendererListener;

// This hack is to load the "old locations" of the classes.
spl_autoload_register(
    function ($class) {
        static $classes = [
            'MetaModels\Attribute\Levensthein\AttributeLevensthein'            => AttributeLevenshtein::class,
            'MetaModels\Attribute\Levensthein\LevenstheinAttributeTypeFactory' => LevenshteinAttributeTypeFactory::class,
            'MetaModels\AttributeLevenshteinBundle\EventListener\DcGeneral\Table\FilterSetting\TypeRendererListener' => TypeRendererListener::class
        ];

        if (isset($classes[$class])) {
            // @codingStandardsIgnoreStart Silencing errors is discouraged
            @trigger_error('Class "' . $class . '" has been renamed to "' . $classes[$class] . '"', E_USER_DEPRECATED);
            // @codingStandardsIgnoreEnd

            if (!class_exists($classes[$class])) {
                spl_autoload_call($class);
            }

            class_alias($classes[$class], $class);
        }
    }
);
