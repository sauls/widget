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

use Sauls\Component\Widget\Exception\CollectionItemNotFoundException;
use Sauls\Component\Widget\View\NullView;
use Sauls\Component\Widget\View\PhpFileView;
use Sauls\Component\Widget\View\StringView;
use Sauls\Component\Widget\View\TwigView;

class ViewCollectionTest extends CollectionTestCase
{
    /**
     * @test
     */
    public function should_set_views()
    {
        $viewCollection = new WidgetCollection();

        $viewCollection->set(null, new NullView());
        $this->assertTrue($viewCollection->keyExists('null'));

        $viewCollection->set(null, new StringView());
        $this->assertTrue($viewCollection->keyExists('string'));

        $viewCollection->set(null, new PhpFileView());
        $this->assertTrue($viewCollection->keyExists('php'));
    }

    /**
     * @test
     */
    public function should_get_views()
    {
        $viewCollection = $this->createViewCollection();

        $this->assertInstanceOf(NullView::class, $viewCollection->get('null'));
        $this->assertInstanceOf(StringView::class, $viewCollection->get('string'));
        $this->assertInstanceOf(PhpFileView::class, $viewCollection->get('php'));
        $this->assertInstanceOf(TwigView::class, $viewCollection->get('twig'));
    }

    /**
     * @test
     */
    public function should_throw_collection_item_not_found_exception()
    {
        $this->expectException(CollectionItemNotFoundException::class);
        $viewCollection = $this->createViewCollection();
        $viewCollection->get('doc');
    }
}
