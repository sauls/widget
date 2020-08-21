<?php

declare(strict_types=1);

namespace Sauls\Component\Widget;

use Sauls\Component\OptionsResolver\OptionsResolver;
use Sauls\Component\Widget\Exception\NotAWidgetException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

use function Sauls\Component\Helper\object_ucnp;
use function sprintf;

class CacheableWidget extends Widget implements Named
{
    public static $prefix = 'cw';
    public static $total = 0;
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function render(): string
    {
        $self = $this;
        return $this->cache->get(
            $this->createKey(),
            function (ItemInterface $item) use ($self) {
                $item->expiresAfter($self->getOption('ttl'));
                return (string)$self->getOption('widget');
            }
        ) ?? '';
    }

    private function createKey(): string
    {
        return sprintf(
            '__%1$s%2$s__%3$s__',
            $this->getOption('namespace') . self::$prefix,
            strtolower(object_ucnp($this->getOption('widget'))),
            $this->getId()
        );
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
            ->setRequired(['widget'])
            ->addAllowedTypes('widget', ['object'])
            ->addAllowedTypes('namespace', ['string'])
            ->addAllowedTypes('ttl', ['int'])
            ->setDefaults(
                [
                    'namespace' => '__widget__',
                    'ttl' => 3600,
                ]
            )
            ->setNormalizer(
                'widget',
                function (Options $options, $value) {
                    if (!$value instanceof WidgetInterface) {
                        throw new NotAWidgetException(
                            sprintf(
                                'Given object must implement %1$s interface',
                                WidgetInterface::class
                            )
                        );
                    }

                    return $value;
                }
            );
    }

    public function getName(): string
    {
        return 'cacheable_widget';
    }
}
