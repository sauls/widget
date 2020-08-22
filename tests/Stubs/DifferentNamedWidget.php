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

use Sauls\Component\Widget\Named;
use Sauls\Component\Widget\Widget;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DifferentNamedWidget extends Widget implements Named
{
    public function getName(): string
    {
        return 'different_widget';
    }

    public function render(): string
    {
        return sprintf('Different widget with value %s!', $this->getOption('value'));
    }

    /***
     * @throws \Exception
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['value'])
            ->addAllowedTypes('value', ['string', 'int'])
            ->setDefaults(['value' => 'one hundred']);
    }
}
