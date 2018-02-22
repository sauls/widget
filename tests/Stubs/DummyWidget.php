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
use Sauls\Component\OptionsResolver\OptionsResolver;

class DummyWidget extends Widget
{

    /**
     * Widget allowed properties are defined here
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        // do nothing?
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return 'hello from test widget';
    }
}
