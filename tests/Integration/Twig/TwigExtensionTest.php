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

namespace Sauls\Component\Widget\Integration\Twig;


use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;

class TwigExtensionTest extends TwigExtensionTestCase
{
    /**
     * @test
     *
     * @throws PropertyNotAccessibleException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function should_render_twig()
    {
        $twig = $this->createTwigEnvironment([
            'templatesDir' => $this->createTemplateDirectoryPath(),
            'cacheDir' => $this->createTemplateDirectoryPath(),
            'extensions' => [
                new TwigExtension($this->createWidgetFactory()),
            ],
        ]);


        $this->assertContains('15', $twig->render('integration/widget.html.twig'));
    }
}
