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

use Exception;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Widget\Factory\WidgetFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function Sauls\Component\Helper\array_get_value;

class TwigExtension extends AbstractExtension
{
    private $widgetFactory;

    public function __construct(WidgetFactoryInterface $widgetFactory)
    {
        $this->widgetFactory = $widgetFactory;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'widget',
                [$this, 'widget'],
                [
                    'is_safe' => ['html'],
                ]
            )
        ];
    }

    /**
     * @throws Exception
     * @throws PropertyNotAccessibleException
     */
    public function widget(string $name, array $options = [], array $extenstionOptions = []): string
    {
        $outputErrors = array_get_value($extenstionOptions, 'outputErrors', true);

        try {
            return $this->widgetFactory->create($name, $options);
        } catch (Exception $e) {
            if ($outputErrors) {
                return $e->getMessage();
            }

            return '';
        }
    }
}
