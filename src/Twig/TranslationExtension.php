<?php

namespace App\Twig;

use App\Util;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{

    protected $util;

    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
        ];
    }

    public function trans($key)
    {
        return $this->util->trans($key);
    }
}