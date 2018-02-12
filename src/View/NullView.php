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

namespace Sauls\Component\Widget\View;

class NullView implements ViewInterface
{
    /**
     * @param string $viewFile
     * @param array  $viewData
     *
     * @return string
     *
     */
    public function render(string $viewFile, array $viewData = []): string
    {
        return '';
    }

    public function getName(): string
    {
        return 'null';
    }
}
