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

use Sauls\Component\Widget\ViewWidget;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemoTwigWidget extends ViewWidget
{
    /**
     * @throws \Exception
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'viewFile' => 'demo.html.twig',
                'viewData' => [
                    'text' => 'DEMO',
                ],
            ]
        );
    }

    protected function process(): array
    {
        return [];
    }
}
