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

use function Sauls\Component\Helper\convert_to;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Widget\Exception\WidgetNotFoundException;
use Sauls\Component\Widget\Stubs\DifferentNamedWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\Stubs\DemoTwigWidget;
use Sauls\Component\Widget\Stubs\WidgetFactoryDependentWidget;

class WidgetFactoryTest extends WidgetFactoryTestCase
{
    use WidgetFactoryTestCaseTrait;

    /**
     * @test
     * @dataProvider getCreateWidgetData
     */
    public function should_create_widget(string $expected, string $widgetName, array $widgetOptions): void
    {
        $widgetFactory = $this->createWidgetFactory();
        $this->assertContains($expected, (string)$widgetFactory->create($widgetName, $widgetOptions));
    }

    public function getCreateWidgetData(): array
    {
        return [
            [
                'hello from test widget',
                DummyWidget::class,
                [],
            ],
            [
                'Hello PHP',
                SimpleViewWidget::class,
                [
                    'viewFile' => 'Hello {name}',
                    'viewData' => [
                        'name' => 'PHP',
                    ],
                ],
            ],
            [
                'This is a twig DEMO widget',
                DemoTwigWidget::class,
                [],
            ]
        ];
    }

    /**
     * @test
     *
     * @throws WidgetNotFoundException
     * @throws PropertyNotAccessibleException
     */
    public function should_throw_widget_not_found_exception(): void
    {
        $this->expectException(WidgetNotFoundException::class);
        $this->createWidgetFactory()->create('noname');
    }

    /**
     * @test
     *
     * @throws PropertyNotAccessibleException
     * @throws WidgetNotFoundException
     */
    public function should_throw_widget_exception(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->createWidgetFactory()->create(SimpleViewWidget::class, ['unknown_property' => 'no_value']);
    }

    /**
     * @test
     */
    public function should_throw_exception_when_dependent_widget_is_not_registered(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Widget `Sauls\Component\Widget\Stubs\DifferentNamedWidget` not found or is not registered.');

        $widgetFactory = $this->createWidgetFactory([
            'widgets' => [
                new WidgetFactoryDependentWidget(),
            ],
        ]);

        $this->assertSame('', $widgetFactory->create(WidgetFactoryDependentWidget::class, [
            'other_widget_options' => [
                'value' => 'yep widget inside widget ...',
            ]
        ]));
    }

    /**
     * @test
     */
    public function should_create_factory_dependent_widget(): void
    {
        $widgetFactory = $this->createWidgetFactory([
            'widgets' => [
                new DifferentNamedWidget,
                new WidgetFactoryDependentWidget,
            ],
        ]);

        $widget = $widgetFactory->create(WidgetFactoryDependentWidget::class, [
            'other_widget_options' => [
                'value' => 'yep widget inside widget ...',
            ]
        ]);


        $this->assertSame('Widget factory dependent widget output: dependent widget value: Different widget with value yep widget inside widget ...!', (string) $widget);
    }

}
