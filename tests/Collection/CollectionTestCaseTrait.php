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

use Sauls\Component\Collection\Collection;
use Sauls\Component\Widget\View\ViewTestCaseTrait;
use Sauls\Component\Widget\WidgetTestCaseTrait;

trait CollectionTestCaseTrait
{
    use WidgetTestCaseTrait;

    public function createViewCollection(array $views = []): Collection
    {
        return new ViewCollection($this->getDefaultViews($views));
    }

    public function createWidgetCollection(array $widgets = []): Collection
    {
        return new WidgetCollection($this->getDefaultWidgets($widgets));
    }
}
