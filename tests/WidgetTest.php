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

use PHPUnit\Framework\TestCase;
use Sauls\Component\Collection\Collection;
use Sauls\Component\Widget\Stubs\ConfigurableWidget;
use Sauls\Component\Widget\Stubs\DummyWidget;
use Sauls\Component\Widget\Stubs\FaultyWidget;
use Sauls\Component\Widget\Stubs\SimpleViewWidget;
use Sauls\Component\Widget\View\StringView;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class WidgetTest extends WidgetTestCase
{
    /**
     * @test
     */
    public function should_render_widget()
    {
        $dummyWidget = new DummyWidget();
        $this->assertContains('hello', $dummyWidget->__toString());
        $this->assertEquals('w0', $dummyWidget->getId());
    }

    /**
     * @test
     */
    public function should_assign_custom_widget_id()
    {
        $dummyWidget = new DummyWidget();
        $dummyWidget->setId('dummy-widget');
        $this->assertSame('dummy-widget', $dummyWidget->getId());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_create_widget_with_default_values()
    {
        $configurableWidget = (new ConfigurableWidget())->widget();

        $this->assertSame(30, $configurableWidget->getOption('interval'));
        $this->assertSame('top', $configurableWidget->getOption('position'));
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_create_widget_with_custom_values()
    {
        $configurableWidget = (new ConfigurableWidget())->widget([
            'interval' => 15,
            'position' => 'bottom',
        ]);

        $this->assertSame(15, $configurableWidget->getOption('interval'));
        $this->assertSame('bottom', $configurableWidget->getOption('position'));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function should_return_all_options()
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
     * @throws \Exception
     */
    public function should_return_options_collection()
    {
        $configurableWidget = (new ConfigurableWidget())->widget();

        $this->assertInstanceOf(Collection::class, $configurableWidget->getOptionsCollection());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_throw_exception_on_option_that_does_not_exists()
    {
        $this->expectException(UndefinedOptionsException::class);

        (new ConfigurableWidget())->widget([
            'interval' => 15,
            'position' => 'bottom',
            'unknown' => 14,
        ]);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function should_render_error_message()
    {
        $faultyWidget = (new FaultyWidget())->widget();

        $this->assertEquals('I shall not render!', (string)$faultyWidget);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_render_view_widget_with_string_view_and_default_view_value()
    {
        $widget = $this->createViewWidget(SimpleViewWidget::class, [
            'viewData' => [
                'name' => 'John'
            ],
        ], new StringView);

        $this->assertContains('Hello my name is John', (string)$widget);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_render_view_widget_with_string_view_and_custom_view_value()
    {
        $widget = $this->createViewWidget(SimpleViewWidget::class, [
            'viewFile' => 'Hello {name}. And welcome to {place}!',
            'viewData' => [
                'name' => 'John',
                'place' => 'World of PHP',
            ],
        ], new StringView);

        $this->assertContains('Hello John. And welcome to World of PHP', (string)$widget);
    }
}
