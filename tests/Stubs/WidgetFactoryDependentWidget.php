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

namespace Sauls\Component\Widget\Stubs;

use Sauls\Component\Widget\Factory\Traits\WidgetFactoryAwareTrait;
use Sauls\Component\Widget\Widget;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidgetFactoryDependentWidget extends Widget
{
    use WidgetFactoryAwareTrait;

    private $differentNamedWidget;

    /**
     * @throws \Exception
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['other_widget_options.value']);
        $resolver->setDefaults([
            'other_widget_options' => [
                'value' => 'Default other widget value',
            ]
        ]);
    }

    /**
     * @throws \Sauls\Component\Widget\Exception\WidgetNotFoundException
     */
    protected function initialize(): void
    {
        $this->differentNamedWidget = $this->widgetFactory->create(
            DifferentNamedWidget::class,
            $this->getOption('other_widget_options')
        );
    }

    public function render(): string
    {
        return sprintf('Widget factory dependent widget output: dependent widget value: %s', $this->differentNamedWidget);
    }
}
