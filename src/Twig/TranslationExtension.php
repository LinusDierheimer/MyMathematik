<?php

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{

    protected $translator;

    public function __construct(
        TranslatorInterface $translator
    ){
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
        ];
    }

    public function trans($key)
    {
        if(\substr($key, 0, 2) == "> ")
            return \substr($key, 2);

        return $this->translator->trans($key);
    }
}