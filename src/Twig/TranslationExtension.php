<?php

namespace App\Twig;

use App\Globals;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{

    protected $util;
    protected $translations;

    public function __construct(Globals $util)
    {
        $this->util = $util;
        $this->translations = Globals::load_yaml_file(
            $util->get_parameter('translations_directory') . $util->current_language['file']
        );
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

        try{
            return Globals::array_get($this->translations, $key);
        }catch(\Throwable $e){
            return "{Couldn't translate.  key='" . $key . "', error='" . $e->getMessage() . "'}";
        }
    }
}