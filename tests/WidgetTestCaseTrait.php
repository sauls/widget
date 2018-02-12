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

namespace Sauls\Component\Widget;

use function Sauls\Component\Helper\array_merge;
use Sauls\Component\Widget\Stubs\ConfigurableWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\FaultyWidget;
use Sauls\Component\Widget\Stubs\NamedDummyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\Stubs\templates\DemoTwigWidget;
use Sauls\Component\Widget\View\ViewInterface;
use Sauls\Component\Widget\View\ViewTestCaseTrait;

trait WidgetTestCaseTrait
{
    use ViewTestCaseTrait;

    public function createViewWidget(string $widgetClass, array $options = [], ViewInterface $view): ViewWidgetInterface
    {
        $widget = (new $widgetClass)->widget($options);
        $widget->setView($view);
        return $widget;
    }

    public function getDefaultWidgets(array $widgets = []): array
    {
        return array_merge(
            [
                new ConfigurableWidget(),
                new DummyWidget(),
                new FaultyWidget(),
                new NamedDummyWidget(),
                new SimpleViewWidget(),
                new DemoTwigWidget(),
            ],
            $widgets
        );
    }
}
