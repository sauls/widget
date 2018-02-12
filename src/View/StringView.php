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


class StringView implements ViewInterface
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
        return strtr(
            $viewFile,
            array_combine(
                array_map(
                    function ($key) {
                        return '{'.$key.'}';
                    },
                    array_keys($viewData)
                ),
                array_values($viewData)
            )
        );
    }

    public function getName(): string
    {
        return 'string';
    }
}
