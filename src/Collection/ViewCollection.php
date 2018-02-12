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

namespace Sauls\Component\Widget\Collection;

use Sauls\Component\Collection\ArrayCollection;
use Sauls\Component\Widget\Exception\CollectionItemNotFoundException;
use Sauls\Component\Widget\View\ViewInterface;

class ViewCollection extends ArrayCollection
{
    public function set($key, $value): void
    {
        parent::set($value->getName(), $value);
    }

    /**
     * @throws CollectionItemNotFoundException
     */
    public function get($key, $default = null): ViewInterface
    {
        try {
            return parent::get($key, $default);
        } catch (\Throwable $e) {
            throw new CollectionItemNotFoundException(
                \sprintf('`%s` collection does not have item with name `%s`', \get_class($this), $key)
            );
        }
    }

}
