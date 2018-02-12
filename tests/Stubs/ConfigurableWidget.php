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

use Sauls\Component\Widget\Widget;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurableWidget extends Widget
{
    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['interval', 'position'])
            ->addAllowedTypes('interval', ['int'])
            ->addAllowedTypes('position', 'string')
            ->addAllowedValues('position', ['top', 'right', 'bottom', 'left'])
            ->setDefaults([
                'interval' => 30,
                'position' => 'top',
            ]);
    }

    public function render(): string
    {
        return sprintf(
            'Widget refresh interval is %s at position %s',
            $this->getOption('interval'),
            $this->getOption('position')
        );
    }
}
