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

use function Sauls\Component\Helper\create_directory_path;
use function Sauls\Component\Helper\array_merge;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Widget\Integration\Twig\TwigTestCaseTrait;

trait ViewTestCaseTrait
{
    use TwigTestCaseTrait;

    /***
     * @throws PropertyNotAccessibleException
     */
    public function createTwigView(string $templateDir, string $cacheDir)
    {
        return new TwigView($this->createTwigEnvironment([
            'cacheDir' => $cacheDir,
            'templateDir' => $templateDir,
        ]));
    }

    public function createTemplateDirectoryPath(array $path = []): string
    {
        if (empty($path)) {
            $path = [__DIR__, '..', 'Stubs', 'templates'];
        }

        return create_directory_path($path);
    }

    /**
     * @throws PropertyNotAccessibleException
     */
    public function getDefaultViews(array $views = []): array
    {
        return array_merge(
            [
                new NullView(),
                new StringView(),
                new PhpFileView(),
                $this->createTwigView(
                    $this->createTemplateDirectoryPath(),
                    $this->createTemplateDirectoryPath()
                )
            ],
            $views
        );
    }
}
