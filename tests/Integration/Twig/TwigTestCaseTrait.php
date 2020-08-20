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
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function Sauls\Component\Helper\array_get_value;
use function Sauls\Component\Helper\array_merge;

trait TwigTestCaseTrait
{
    /**
     * @throws PropertyNotAccessibleException
     */
    public function createTwigEnvironment(array $options): Environment
    {
        $cacheDir = array_get_value($options, 'cacheDir', '');
        $templateDir = array_get_value($options, 'templateDir', '');
        $templateDirectories = array_merge(
            [$templateDir],
            array_get_value($options, 'templateDirectories', [])
        );
        $extensions = array_get_value($options, 'extensions', []);

        $loader = new FilesystemLoader($cacheDir);
        $twig = new Environment($loader, $templateDirectories);

        foreach ($extensions as $extension) {
            $twig->addExtension($extension);
        }

        return $twig;
    }
}
