<?php

namespace App;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 * Utils that provides propertys like cookies and translations
 * 
 * @Autor Linus Dierheimer
 * 
 */
class Util
{

    public static function array_get($array, $indexes, $seperator = '.')
    {
        $index = strpos($indexes, $seperator);

        if($index === FALSE)
            return $array[$indexes];

        return self::array_get($array[substr($indexes, 0, $index)], substr($indexes, $index + 1), $seperator);
    }

    public static function load_yaml_file($path, int $flags = 0)
    {
        return Yaml::parseFile($path, $flags);
    }

    /***********/
    /* Service */
    /***********/

    protected $requestStack;
    protected $container;

    protected $videoconfig;
    protected $languages;

    public function __construct(RequestStack $rs, ContainerInterface $ci)
    {
        $this->requestStack = $rs;
        $this->container = $ci;
    }

    public function get_parameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function get_request()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function get_cookie($name, $request = null)
    {
        $request = $request ?: $this->get_request();
        return $request == null ? "" : $request->cookies->get($name);
    }

    //Also used by Translation Twig extensions
    public function get_languages()
    {
        if($this->languages == null)
            $this->languages = self::load_yaml_file($this->get_parameter('languages_file'));
        return $this->languages;
    }

    //Also used by Translation Twig extensions
    public function get_current_language()
    {
        $locale = $this->get_cookie('language');

        $request = $this->get_request();
        if($locale == null || $locale == "")
            if($request != null)
                $locale = $request->getLocale();

        $languages = $this->get_languages();
        if(\array_key_exists($locale, $languages))
            return $languages[$locale];
        else
            return $languages[$this->get_parameter("default_locale")];
    }

    //Also used by Video controller
    public function get_videos()
    {
        if($this->videoconfig == null)
            $this->videoconfig = self::load_yaml_file($this->get_parameter('video_config_file'));
        return $this->videoconfig;
    }
    
    public function get_globals()
    {
        return [
            "videos"           => $this->get_videos(),
            "informations"     => self::load_yaml_file($this->get_parameter('information_file')),
            "sponsors"         => self::load_yaml_file($this->get_parameter('sponsors_file')),
            "languages"        => $this->get_languages(),
            "current_language" => $this->get_current_language(),
            "designs"          => self::load_yaml_file($this->get_parameter('designs_file')),
            "current_design"   => $this->get_cookie("design") ?: $this->get_parameter("default_design")
        ];
    }
}
