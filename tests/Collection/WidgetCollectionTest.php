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

namespace Sauls\Component\Widget\Collection;

use Sauls\Component\Widget\Exception\CollectionItemNotFoundException;
use Sauls\Component\Widget\Stubs\ConfigurableWidget;
use Sauls\Component\Widget\Stubs\DifferentNamedWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\NamedDummyWidget;
use Sauls\Component\Widget\Stubs\UnknownWidget;

class WidgetCollectionTest extends CollectionTestCase
{
    /**
     * @test
     */
    public function should_set_widgets()
    {
        $widgetCollection = $this->createWidgetCollection([
            new NamedDummyWidget(),
            new ConfigurableWidget(),
            new DummyWidget(),
            'my_own_different_widget_name' => new DifferentNamedWidget(),
        ]);

        $this->assertTrue($widgetCollection->hasKey(NamedDummyWidget::class));
        $this->assertTrue($widgetCollection->hasKey('dummy'));
        $this->assertTrue($widgetCollection->hasKey(ConfigurableWidget::class));
        $this->assertTrue($widgetCollection->hasKey(DummyWidget::class));
        $this->assertTrue($widgetCollection->hasKey(DifferentNamedWidget::class));
        $this->assertTrue($widgetCollection->hasKey('different_widget'));
        $this->assertTrue($widgetCollection->hasKey('my_own_different_widget_name'));
    }

    /**
     * @test
     */
    public function should_return_widget()
    {
        $widgetCollection = $this->createWidgetCollection([
            new NamedDummyWidget(),
            new ConfigurableWidget(),
            new DummyWidget(),
            'my_own_different_widget_name' => new DifferentNamedWidget(),
        ]);

        $this->assertInstanceOf(NamedDummyWidget::class, $widgetCollection->get(NamedDummyWidget::class));
        $this->assertInstanceOf(NamedDummyWidget::class, $widgetCollection->get('dummy'));
        $this->assertInstanceOf(ConfigurableWidget::class, $widgetCollection->get(ConfigurableWidget::class));
        $this->assertInstanceOf(DummyWidget::class, $widgetCollection->get(DummyWidget::class));
        $this->assertInstanceOf(DifferentNamedWidget::class, $widgetCollection->get(DifferentNamedWidget::class));
        $this->assertInstanceOf(DifferentNamedWidget::class, $widgetCollection->get('different_widget'));
        $this->assertInstanceOf(DifferentNamedWidget::class, $widgetCollection->get('my_own_different_widget_name'));

    }

    /**
     * @test
     */
    public function should_throw_exception_if_widget_not_found()
    {
        $this->expectException(CollectionItemNotFoundException::class);

        $widgetCollection = $this->createWidgetCollection();

        $widgetCollection->get(UnknownWidget::class);
    }
}
