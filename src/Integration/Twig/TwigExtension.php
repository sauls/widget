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
use Sauls\Component\Widget\Factory\WidgetFactoryInterface;

class TwigExtension extends \Twig_Extension
{
    private $widgetFactory;

    public function __construct(WidgetFactoryInterface $widgetFactory)
    {
        $this->widgetFactory = $widgetFactory;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('widget', [$this, 'widget'], [
                'is_safe' => ['html'],
            ])
        ];
    }

    /**
     * @throws \Exception
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    public function widget(string $name, array $options = [], array $extenstionOptions = []): string
    {
        $outputErrors = array_get_value($extenstionOptions, 'outputErrors', true);

        try {
            return $this->widgetFactory->create($name, $options);
        } catch (\Exception $e) {
            if (false === $outputErrors) {
                return $e->getMessage();
            }

            return '';
        }

    }

}
