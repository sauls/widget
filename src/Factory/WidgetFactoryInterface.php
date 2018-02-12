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

namespace Sauls\Component\Widget\Factory;

use Sauls\Component\Widget\Exception\WidgetNotFoundException;
use Sauls\Component\Widget\WidgetInterface;

interface WidgetFactoryInterface
{
    /**
     * @throws WidgetNotFoundException
     */
    public function create(string $name, array $options = []): WidgetInterface;
}
