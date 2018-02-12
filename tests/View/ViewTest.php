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

class ViewTest extends ViewTestCase
{
    /**
     * @test
     */
    public function should_render_null_view()
    {
        $nullView = new NullView();
        $this->assertSame('null', $nullView->getName());
        $this->assertSame('', $nullView->render(''));
    }

    /**
     * @test
     */
    public function should_render_string_view()
    {
        $stringView = new StringView();
        $this->assertSame('string', $stringView->getName());
        $this->assertSame('Hello World!', $stringView->render('Hello World!', []));
        $this->assertSame('Hello MAGIC World!', $stringView->render('Hello {variable} World!', ['variable' => 'MAGIC']));
        $this->assertSame('Hello MAGIC World! {no_var}', $stringView->render('Hello {variable} World! {no_var}', ['variable' => 'MAGIC']));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Missing template directories for Sauls\Component\Widget\View\PhpFileView
     */
    public function should_render_php_file_view_with_template_directories_exception()
    {
        $phpFileView = new PhpFileView();
        $phpFileView->render('test.php');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Template test.html.php was not found.
     * @throws \Exception
     */
    public function should_render_php_file_view_with_template_file_not_found_exception()
    {
        $phpFileView = new PhpFileView([__DIR__, __DIR__.'/templates']);
        $phpFileView->render('test.html.php');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_render_php_file_view()
    {
        $phpFileView = new PhpFileView([__DIR__.'/../Stubs/templates',]);
        $this->assertSame('php', $phpFileView->getName());
        $this->assertEquals('Test template', $phpFileView->render('test.php'));
        $this->assertEquals(
            'This template variable $var value is NONE',
            $phpFileView->render('test_with_params.php', ['var' => 'NONE'])
        );
        $this->assertSame('Secret', $phpFileView->render(__DIR__.'/../Stubs/templates/subdir/secret-file.php'));
        $this->assertSame('Secret', $phpFileView->render('subdir/secret-file.php'));
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function should_render_twig_file_view()
    {
        $twigView = $this->createTwigView(
            $this->createTemplateDirectoryPath(),
            $this->createTemplateDirectoryPath()
        );

        $this->assertSame('twig', $twigView->getName());
        $this->assertContains('This is a twig demo widget.', $twigView->render('demo.html.twig', ['text' => 'demo']));
    }
}
