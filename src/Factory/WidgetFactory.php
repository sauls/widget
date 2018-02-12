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
use function Sauls\Component\Helper\array_merge;
use Sauls\Component\Collection\Collection;
use function Sauls\Component\Helper\array_remove_key;
use Sauls\Component\Helper\Exception\ClassPropertyNotSetException;
use Sauls\Component\Widget\Exception\CollectionItemNotFoundException;
use Sauls\Component\Widget\Exception\WidgetNotFoundException;
use Sauls\Component\Widget\ViewWidgetInterface;
use Sauls\Component\Widget\WidgetInterface;

class WidgetFactory implements WidgetFactoryInterface
{
    private $widgetCollection;
    private $viewCollection;

    public function __construct(Collection $widgetCollection, Collection $viewCollection)
    {
        $this->widgetCollection = $widgetCollection;
        $this->viewCollection = $viewCollection;
    }

    /**
     * @throws WidgetNotFoundException
     * @throws CollectionItemNotFoundException
     */
    public function create(string $name, array $options = []): WidgetInterface
    {
        try {
            $widget = clone $this->widgetCollection->get($name);

            return $this->configureWidget($widget, $options);

        } catch (CollectionItemNotFoundException $e) {
            throw new WidgetNotFoundException(sprintf('Widget `%s` not found or is not registered.', $name));
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    private function configureWidget(WidgetInterface $widget, array $options): WidgetInterface
    {
        $viewName = $this->resolveViewName($options);
        $widget = $widget->widget($options);

        return $this->resolveDependencies($widget, $viewName);
    }

    private function resolveViewName($options): string
    {
        return array_remove_key($options, 'view', '');
    }

    private function resolveDependencies(WidgetInterface $widget, string $viewName): WidgetInterface
    {
        if (is_subclass_of($widget, ViewWidgetInterface::class)) {
            $viewName = $this->viewCollection->has($viewName) ? $viewName : $this->resolveWidgetViewName($widget);
            $widget->setView($this->viewCollection->get($viewName));
        }

        return $widget;
    }

    private function resolveWidgetViewName(WidgetInterface $widget): string
    {
        $info = new \SplFileInfo($widget->getOption('viewFile'));
        $fileExtension = $info->getExtension();

        return $this->viewCollection->has($fileExtension) ? $fileExtension : 'string';

    }
}
