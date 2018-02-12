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


class PhpFileView implements ViewInterface
{
    /**
     * @var array
     */
    private $templatesDirectories;

    /**
     * PhpFileView constructor.
     *
     * @param array $templatesDirectories
     */
    public function __construct(array $templatesDirectories = [])
    {
        $this->templatesDirectories = $templatesDirectories;
    }

    /**
     * @throws \Exception
     */
    public function render(string $viewFile, array $viewData = []): string
    {
        try {
            ob_start();

            extract($viewData, EXTR_OVERWRITE);

            include $this->resolveViewFile($viewFile);

            return ob_get_clean();
        } catch (\Exception $e) {
            ob_get_clean();
            throw $e;
        }
    }

    /**
     * @param string $viewFile
     *
     * @return string
     * @throws \RuntimeException
     */
    private function resolveViewFile(string $viewFile): string
    {
        try {

            if (file_exists($viewFile)) {
                return $viewFile;
            }

            $this->checkTemplatesDirectoryExists();

            return $this->resolveTemplateFile($viewFile);

        } catch (\Exception $e) {
          throw new \RuntimeException($e->getMessage());
        } catch (\Throwable $t) {
            throw new \RuntimeException(
                sprintf(
                    'Template %s was not found. Looked in %s',
                    $viewFile,
                    implode(',', $this->templatesDirectories))
            );
        }
    }

    private function resolveTemplateFile(string $viewFile): string
    {
        foreach ($this->templatesDirectories as $directory) {
            $template = realpath($directory.DIRECTORY_SEPARATOR.$viewFile);
            if (file_exists($template)) {
                return $template;
            }
        }

        return null;
    }

    /**
     * @throws \RuntimeException
     */
    private function checkTemplatesDirectoryExists(): void
    {
        if (empty($this->templatesDirectories)) {
            throw new \RuntimeException(sprintf('Missing template directories for %s', __CLASS__));
        }
    }

    public function getName(): string
    {
        return 'php';
    }
}
