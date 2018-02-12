<?php
/**
 * This file is part of the sauls/widget package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2018
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sauls\Component\Widget\Factory;

use function Sauls\Component\Helper\array_get_value;
use Sauls\Component\Collection\Collection;
use Sauls\Component\Widget\Collection\CollectionTestCaseTrait;

trait WidgetFactoryTestCaseTrait
{
    use CollectionTestCaseTrait;

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    public function createWidgetFactory(
        array $options = [],
        ?Collection $widgetCollection = null,
        ?Collection $viewCollection = null
    ): WidgetFactoryInterface {

        if (null === $widgetCollection) {
            $widgetCollection = $this->createWidgetCollection(
                $this->getDefaultWidgets(array_get_value($options, 'widgets', []))
            );
        }

        if (null === $viewCollection) {
            $viewCollection = $this->createViewCollection(
                $this->getDefaultViews(array_get_value($options, 'views', []))
            );
        }

        return new WidgetFactory($widgetCollection, $viewCollection);
    }
}
