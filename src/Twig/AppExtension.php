<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('money', [$this, 'formatMoney']),
        ];
    }

    public function formatMoney(float|string $value): string
    {
        $floatValue = (float) $value;
        return number_format($floatValue, 2, '.', ' ') . ' €';
    }
}


