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

use Sauls\Component\Collection\Collection;

interface WidgetInterface
{
    /**
     * @throws \Exception
     */
    public function widget(array $options = []): WidgetInterface;
    public function getOptionsCollection(): Collection;
    public function getOptions(): array;
    public function getOption($key, $default = null);
    public function getId($generate = true): string;
    public function setId(string $value): void;
}
