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
use Sauls\Component\Widget\Widgets\CacheableWidget;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class WidgetCollectionTest extends CollectionTestCase
{
    /**
     * @test
     */
    public function should_set_widgets()
    {
        $widgetCollection = $this->createWidgetCollection(
            [
                new NamedDummyWidget(),
                new ConfigurableWidget(),
                new DummyWidget(),
                'my_own_different_widget_name' => new DifferentNamedWidget(),
            ]
        );

        $this->assertTrue($widgetCollection->keyExists(NamedDummyWidget::class));
        $this->assertTrue($widgetCollection->keyExists('dummy'));
        $this->assertTrue($widgetCollection->keyExists(ConfigurableWidget::class));
        $this->assertTrue($widgetCollection->keyExists(DummyWidget::class));
        $this->assertTrue($widgetCollection->keyExists(DifferentNamedWidget::class));
        $this->assertTrue($widgetCollection->keyExists('different_widget'));
        $this->assertTrue($widgetCollection->keyExists('my_own_different_widget_name'));
    }

    /**
     * @test
     */
    public function should_return_widget()
    {
        $widgetCollection = $this->createWidgetCollection(
            [
                new NamedDummyWidget(),
                new ConfigurableWidget(),
                new DummyWidget(),
                'my_own_different_widget_name' => new DifferentNamedWidget(),
            ]
        );

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

    /**
     * @test
     */
    public function should_return_cacheable_widget_name(): void
    {
        $widgetCollection = $this->createWidgetCollection(
            [
                new CacheableWidget(new ArrayAdapter()),
            ]
        );

        $this->assertInstanceOf(CacheableWidget::class,  $widgetCollection->get('cacheable_widget'));
    }
}
