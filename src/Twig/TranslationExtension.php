<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{

    protected $translator;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $request
    ){
        $this->translator = $translator;
        if($request->getCurrentRequest() != null && $request->getCurrentRequest()->getLocale() != null)
            $this->translator->setlocale($request->getCurrentRequest()->getLocale());
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