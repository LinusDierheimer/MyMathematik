<?php

namespace App\Twig;

use App\Util;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
        ];
    }

    public function trans($key)
    {
        return Util::trans($key);
    }
}