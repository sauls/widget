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

use Sauls\Component\Widget\View\Traits\ViewAwareTrait;
use Sauls\Component\OptionsResolver\OptionsResolver;

abstract class ViewWidget extends Widget implements ViewWidgetInterface
{
    use ViewAwareTrait;

    /**
     * @throws \Exception
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(
                [
                    'viewFile', 'viewData'
                ]
            )
            ->addAllowedTypes('viewFile', ['string'])
            ->addAllowedTypes('viewData', ['array'])
            ->setDefaults([
                'viewFile' => '',
                'viewData' => [],
            ]);
    }

    public function render(): string
    {
        return $this->view->render($this->getOption('viewFile'), $this->getOption('viewData'));
    }
}
