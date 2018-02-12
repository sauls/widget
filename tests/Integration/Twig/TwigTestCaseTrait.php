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

use function Sauls\Component\Helper\array_get_value;
use function Sauls\Component\Helper\array_merge;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;

trait TwigTestCaseTrait
{
    /**
     * @throws PropertyNotAccessibleException
     */
    public function createTwigEnvironment(array $options): \Twig_Environment
    {
        $cacheDir = array_get_value($options, 'cacheDir', '');
        $templateDir = array_get_value($options, 'templateDir', '');
        $templateDirectories = array_merge(
            [$templateDir],
            array_get_value($options, 'templateDirectories', [])
        );
        $extensions = array_get_value($options, 'extensions', []);

        $loader = new \Twig_Loader_Filesystem($cacheDir);
        $twig = new \Twig_Environment($loader, $templateDirectories);

        foreach ($extensions as $extension) {
            $twig->addExtension($extension);
        }

        return $twig;
    }
}
