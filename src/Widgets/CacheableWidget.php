<?php
/**
 * This file is part of the sauls/widget package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2020
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sauls\Component\Widget\Widgets;

use Psr\Cache\InvalidArgumentException;
use Sauls\Component\Widget\Factory\Traits\WidgetFactoryAwareTrait;
use Sauls\Component\Widget\Factory\WidgetFactoryInterface;
use Sauls\Component\Widget\Named;
use Sauls\Component\Widget\Widget;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

use function sprintf;

class CacheableWidget extends Widget implements Named
{
    use WidgetFactoryAwareTrait;

    public static $prefix = 'cw';
    public static $total = 0;
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function render(): string
    {
        $self = $this;

        return $this->cache->get(
                $this->createKey(),
                function (ItemInterface $item) use ($self) {
                    $item->expiresAfter($self->getOption('ttl'));

                    return $self->resolveContentToCache();
                }
            ) ?? '';
    }

    private function createKey(): string
    {
        return md5(
            sprintf(
                '__%1$s%2$s__%3$s__',
                $this->getOption('namespace') . self::$prefix,
                $this->getOption('widget.id'),
                $this->getId()
            )
        );
    }

    private function resolveContentToCache(): string
    {
        return (string)$this->widgetFactory->create(
                $this->getOption('widget.id') ?? '',
                $this->getOption('widget.options') ?? []
            ) ?? '';
    }

    public function getName(): string
    {
        return 'cacheable_widget';
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(
                [
                    'widget',
                    'namespace',
                    'ttl',
                ]
            )
            ->addAllowedTypes('widget', ['array'])
            ->addAllowedTypes('namespace', ['string'])
            ->addAllowedTypes('ttl', ['int'])
            ->setDefaults(
                [
                    'namespace' => '__widget__',
                    'ttl' => 3600,
                    'widget' => [
                        'id' => '',
                        'options' => [],
                    ],
                ]
            );
    }
}
