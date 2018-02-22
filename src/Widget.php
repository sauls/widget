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

namespace Sauls\Component\Widget;

use function Sauls\Component\Helper\define_object;
use Sauls\Component\Collection\ArrayCollection;
use Sauls\Component\Collection\Collection;
use Sauls\Component\OptionsResolver\OptionsResolver;

abstract class Widget implements WidgetInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Collection
     */
    private $options;

    /**
     * @var string
     */
    public static $prefix = 'w';

    /**
     * @var int
     */
    public static $total = 0;

    /**
     *
     * @throws \Exception
     */
    private function configure(array $options = []): void
    {
        try {
            $resolver = new OptionsResolver;
            $this->configureOptions($resolver);
            $this->options = new ArrayCollection($resolver->resolve($this->resolveOptions($options)));
            define_object($this, $this->options->all());
            $this->initialize();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * This method is called after all options and variables are configured
     * You can safely initialise your variables, or other logic here
     */
    protected function initialize(): void
    {
    }

    abstract protected function configureOptions(OptionsResolver $resolver): void;

    public function resolveOptions(array $options): array
    {
        return $options;
    }

    public function getId($generate = true): string
    {
        if ($generate && $this->id === null) {
            $this->id = static::$prefix.static::$total++;
        }

        return $this->id;
    }

    public function setId(string $value): void
    {
        $this->id = $value;
    }

    public function getOptionsCollection(): Collection
    {
        return $this->options;
    }


    public function getOptions(): array
    {
        return $this->options->all();
    }

    public function getOption($key, $default = null)
    {
        return $this->options->get($key, $default);
    }

    abstract public function render(): string;


    /**
     * @throws \Exception
     */
    public function widget(array $options = []): WidgetInterface
    {
        $this->configure($options);

        return $this;
    }

    public function __toString()
    {
        try {
            $this->startAndFlushOutputBuffers();
            try {
                $out = $this->render();
            } catch (\Exception $e) {
                $this->closeNotClosedOutputBuffer();
                throw $e;
            }

            return $this->outputOutputBufferAndWidgetContent($out);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function startAndFlushOutputBuffers(): void
    {
        ob_start();
        ob_implicit_flush(false);
    }

    private function closeNotClosedOutputBuffer(): void
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    private function outputOutputBufferAndWidgetContent(string $out): string
    {
        return ob_get_clean().$out;
    }
}
