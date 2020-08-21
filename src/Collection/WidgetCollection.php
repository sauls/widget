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
use Sauls\Component\Widget\Named;
use Sauls\Component\Widget\WidgetInterface;
use Throwable;

use function get_class;
use function is_string;
use function sprintf;

class WidgetCollection extends ArrayCollection
{
    public function set($key, $value): void
    {
        if (is_subclass_of($value, Named::class)) {
            parent::set($value->getName(), $value);
        }

        parent::set(get_class($value), $value);

        if (is_string($key)) {
            parent::set($key, $value);
        }
    }

    /**
     * @throws CollectionItemNotFoundException
     */
    public function get($key, $default = null): WidgetInterface
    {
        try {
            return parent::get($key, $default);
        } catch (Throwable $e) {
            throw new CollectionItemNotFoundException(
                sprintf('`%s` collection does not have item with name `%s`', get_class($this), $key)
            );
        }
    }
}
