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

use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Widget\Collection\CollectionTestCaseTrait;
use Sauls\Component\Widget\Exception\WidgetNotFoundException;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\Stubs\templates\DemoTwigWidget;

class WidgetFactoryTest extends WidgetFactoryTestCase
{
    use WidgetFactoryTestCaseTrait;

    /**
     * @test
     * @dataProvider getCreateWidgetData
     *
     * @throws PropertyNotAccessibleException
     * @throws \Sauls\Component\Widget\Exception\WidgetNotFoundException
     */
    public function should_create_widget(string $expected, string $widgetName, array $widgetOptions)
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
    public function should_throw_widget_not_found_exception()
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
    public function should_throw_widget_exception()
    {
        $this->expectException(\RuntimeException::class);
        $this->createWidgetFactory()->create(SimpleViewWidget::class, ['unknown_property' => 'no_value']);
    }

}
