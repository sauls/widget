<?php

declare(strict_types=1);

namespace Sauls\Component\Widget\Stubs;

use Sauls\Component\Widget\ViewWidget;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicDataViewWidget extends ViewWidget
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'viewFile' => 'This is a simple view widget. Hello my name is {name}',
                'viewData' => [
                    'name' => 'noname',
                    'place' => 'noplace',
                ],
            ]
        );
    }

    protected function process(): array
    {
        return [
            'place' => 'Hell',
        ];
    }
}
