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
use Sauls\Component\Widget\Collection\CollectionTestCaseTrait;
use Sauls\Component\Widget\Factory\WidgetFactory;
use Sauls\Component\Widget\Factory\WidgetFactoryTestCaseTrait;
use Sauls\Component\Widget\Stubs\ConfigurableWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\DynamicDataViewWidget;
use Sauls\Component\Widget\Stubs\FaultyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\View\StringView;
use Sauls\Component\Widget\Widgets\CacheableWidget;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WidgetTest extends WidgetTestCase
{
    use WidgetFactoryTestCaseTrait, CollectionTestCaseTrait;

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
    public function should_cache_widget_output(): void
    {
        CacheableWidget::$total = 0;

        $cachedWidget = $this
            ->createCacheableWidget()
            ->widget(
                [
                    'widget' => [
                        'id' => SimpleViewWidget::class,
                        'options' => [
                            'viewFile' => 'I want to be cached',
                        ],
                    ],
                    'namespace' => '__something_awesome__',
                    'ttl' => 1337,
                ]
            );

        $this->assertEquals('I want to be cached', (string)$cachedWidget);
    }

    private function createCacheableWidget(CacheInterface $cache = null): CacheableWidget
    {
        $widget = (new CacheableWidget(
            $cache ?? new ArrayAdapter()
        ));
        $widget->setWidgetFactory(new WidgetFactory($this->createWidgetCollection(), $this->createViewCollection()));
        return $widget;
    }

    /** @test */
    public function should_get_widget_cached_value(): void
    {
        CacheableWidget::$total = 0;
        $cache = new ArrayAdapter();
        $cache->get(
            '50d8e152000e5153b99a9a22047197b1',
            function (ItemInterface $item) {
                return 'This is cached value for the widget';
            }
        );

        $cachedWidget = $this
            ->createCacheableWidget($cache)
            ->widget(
                ['widget' => [
                    'id' => SimpleViewWidget::class,
                    'options' => [
                        'viewFile' => 'I want to be cached',
                    ]
                ], 'namespace' => '__something_awesome__', 'ttl' => 1337]
            );

        $this->assertEquals('This is cached value for the widget', (string)$cachedWidget);
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
}
