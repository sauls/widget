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

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Sauls\Component\Collection\Collection;
use Sauls\Component\Widget\Exception\NotAWidgetException;
use Sauls\Component\Widget\Stubs\ConfigurableWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\DynamicDataViewWidget;
use Sauls\Component\Widget\Stubs\FaultyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\View\StringView;
use stdClass;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WidgetTest extends WidgetTestCase
{
    /**
     * @test
     */
    public function should_render_widget(): void
    {
        $dummyWidget = new DummyWidget();
        $this->assertStringContainsString('hello', $dummyWidget->__toString());
        $this->assertEquals('w0', $dummyWidget->getId());
    }

    /**
     * @test
     */
    public function should_assign_custom_widget_id(): void
    {
        $dummyWidget = new DummyWidget();
        $dummyWidget->setId('dummy-widget');
        $this->assertSame('dummy-widget', $dummyWidget->getId());
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_create_widget_with_default_values(): void
    {
        $configurableWidget = (new ConfigurableWidget())->widget();

        $this->assertSame(30, $configurableWidget->getOption('interval'));
        $this->assertSame('top', $configurableWidget->getOption('position'));
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_create_widget_with_custom_values(): void
    {
        $configurableWidget = (new ConfigurableWidget())->widget(
            [
                'interval' => 15,
                'position' => 'bottom',
            ]
        );

        $this->assertSame(15, $configurableWidget->getOption('interval'));
        $this->assertSame('bottom', $configurableWidget->getOption('position'));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function should_return_all_options(): void
    {
        $configurableWidget = (new ConfigurableWidget())->widget();
        $this->assertSame(
            [
                'interval' => 30,
                'position' => 'top',
            ],
            $configurableWidget->getOptions()
        );
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_return_options_collection(): void
    {
        $configurableWidget = (new ConfigurableWidget())->widget();

        $this->assertInstanceOf(Collection::class, $configurableWidget->getOptionsCollection());
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_throw_exception_on_option_that_does_not_exists(): void
    {
        $this->expectException(UndefinedOptionsException::class);

        (new ConfigurableWidget())->widget(
            [
                'interval' => 15,
                'position' => 'bottom',
                'unknown' => 14,
            ]
        );
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function should_render_error_message(): void
    {
        $faultyWidget = (new FaultyWidget())->widget();

        $this->assertEquals('I shall not render!', (string)$faultyWidget);
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_render_view_widget_with_string_view_and_default_view_value(): void
    {
        $widget = $this->createViewWidget(
            SimpleViewWidget::class,
            [
                'viewData' => [
                    'name' => 'John',
                ],
            ],
            new StringView()
        );

        $this->assertStringContainsString('Hello my name is John', (string)$widget);
    }

    /**
     * @test
     * @throws Exception
     */
    public function should_render_view_widget_with_string_view_and_custom_view_value(): void
    {
        $widget = $this->createViewWidget(
            SimpleViewWidget::class,
            [
                'viewFile' => 'Hello {name}. And welcome to {place}!',
                'viewData' => [
                    'name' => 'John',
                    'place' => 'World of PHP',
                ],
            ],
            new StringView()
        );

        $this->assertStringContainsString('Hello John. And welcome to World of PHP', (string)$widget);
    }

    /**
     * @test
     */
    public function should_render_dynamyic_values(): void
    {
        $widget = $this->createViewWidget(
            DynamicDataViewWidget::class,
            [
                'viewFile' => 'Hello {name}. And welcome to {place}!',
                'viewData' => [
                    'name' => 'Sally',
                    'place' => 'Paradise',
                ],
            ],
            new StringView()
        );

        $this->assertStringContainsString('Hello Sally. And welcome to Hell', (string)$widget);
    }

    /**
     * @test
     */
    public function should_throw_exception_that_widget_does_not_implement_widget_interface(): void
    {
        $cache = $this->createCacheMock();
        $cachedWidget = new CacheableWidget($cache);

        $this->expectException(NotAWidgetException::class);
        $this->expectExceptionMessage('Given object must implement Sauls\Component\Widget\WidgetInterface interface');

        $cache
            ->expects($this->never())
            ->method('get');

        $cachedWidget->widget(['widget' => new stdClass()]);
    }

    /**
     * @return MockObject|CacheInterface
     */
    private function createCacheMock()
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn('Cached');

        return $cache;
    }

    /**
     * @test
     */
    public function should_cache_widget_output(): void
    {
        CacheableWidget::$total = 0;
        $widget = $this->createViewWidget(
            SimpleViewWidget::class,
            [
                'viewFile' => 'I want to be cached',
            ],
            new StringView()
        );

        $cachedWidget = (new CacheableWidget(new ArrayAdapter()))
            ->widget(
                ['widget' => $widget, 'namespace' => '__something_awesome__', 'ttl' => 1337]
            );

        $this->assertEquals('I want to be cached', (string)$cachedWidget);
    }

    /** @test */
    public function should_get_widget_cached_value(): void
    {
        CacheableWidget::$total = 0;
        $cache = new ArrayAdapter();
        $cache->get(
            '____something_awesome__cwsimple_view_widget__cw0__',
            function (ItemInterface $item) {
                return 'This is cached value for the widget';
            }
        );

        $widget = $this->createViewWidget(
            SimpleViewWidget::class,
            [
                'viewFile' => 'I want to be cached',
            ],
            new StringView()
        );

        $cachedWidget = (new CacheableWidget($cache))
            ->widget(
                ['widget' => $widget, 'namespace' => '__something_awesome__', 'ttl' => 1337]
            );

        $this->assertEquals('This is cached value for the widget', (string)$cachedWidget);
    }
}
