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

    public static function get_finder()
    {
        $finder = new Finder();
        return $finder;
    }

    public static function get_file_system()
    {
        static $filesystem = null;
        if($filesystem == null)
            $filesystem = new Filesystem();
        return $filesystem;
    }

    public static function load_yaml_file($path, int $flags = 0)
    {
        return Yaml::parseFile($path, $flags);
    }

    public static function load_file_content($path)
    {
        return \file_get_contents($path);
    }

    /***********/
    /* Service */
    /***********/

    protected $requestStack;
    protected $container;

    protected $videoconfig;
    protected $languages;
    protected $translations;
    protected $informations;
    protected $sponsors;

    public function __construct(RequestStack $rs, ContainerInterface $ci)
    {
        $this->requestStack = $rs;
        $this->container = $ci;
    }

    public function get_container()
    {
        return $this->container;
    }

    public function get_parameter($name)
    {
        return $this->get_container()->getParameter($name);
    }

    public function get_request_stack()
    {
        return $this->requestStack;
    }

    public function get_request()
    {
        return $this->get_request_stack()->getCurrentRequest();
    }

    public function get_cookies($request = null)
    {
        $request = $request ?: $this->get_request();
        return $request->cookies;
    }

    public function get_languages()
    {
        if($this->languages == null)
            $this->languages = self::load_yaml_file($this->get_parameter('languages_file'));
        return $this->languages;
    }

    public function get_language_code()
    {
        $request = $this->get_request();

        $locale = 
            $this->get_cookies($request)->get('language') ?:
                $request->getLocale();

        if(\array_key_exists($locale, $this->get_languages()))
            return $locale;

        return $this->get_parameter('default_locale');

    }

    public function get_languages_content()
    {
        return self::load_file_content($this->get_parameter('languages_file'));
    }

    public function get_language()
    {
       return $this->get_languages()[$this->get_language_code()];
    }

    public function get_all_translations_content()
    {
        $content = [];
        foreach($this->get_languages() as $language)
            $content[$language['code']] = self::load_file_content($this->get_parameter('translations_directory') . $language['file']);
        return $content;
    }

    public function get_translations()
    {
        if($this->translations == null)
            $this->translations = self::load_yaml_file($this->get_parameter('translations_directory') . $this->get_language()['file']);
        return $this->translations;
    }

    public function trans($key)
    {
        try{
            return self::array_get($this->get_translations(), $key);
        }catch(\Throwable $e){
            return "{Couldn't translate.  key='" . $key . "', error='" . $e->getMessage() . "'}";
        }
    }

    public function get_video_config()
    {
        if($this->videoconfig == null)
            $this->videoconfig = self::load_yaml_file($this->get_parameter('video_config_file'));
        return $this->videoconfig;
    }

    public function get_video_config_content()
    {
        return self::load_file_content($this->get_parameter('video_config_file'));
    }

    public function get_video_files()
    {
        return self::get_finder()
            ->files()
            ->in($this->get_parameter('videos_directory'))
            ->name('*mp4*', '*ogg*', '*video*')
            ->sortByName();
    }

    public function get_classes()
    {
        return $this->get_video_config()[$this->get_language_code()];
    }

    public function class_exist($class)
    {
        return array_key_exists($class, $this->get_classes());
    }

    public function get_class($class)
    {
        return $this->get_classes()[$class];
    }

    public function get_videos($class)
    {
        return $this->get_class($class)['chapters'];
    }

    public function get_informations()
    {
        if($this->informations == null)
            $this->informations = self::load_yaml_file($this->get_parameter('information_file'));
        return $this->informations;
    }

    public function get_default_class()
    {
        return $this->get_classes()[$this->get_parameter('default_class')]['name'];
    }

    public function get_sponsors()
    {
        if($this->sponsors == null)
            $this->sponsors = self::load_yaml_file($this->get_parameter('sponsors_file'));
        return $this->sponsors;
    }

    public function delete_action($file)
    {
        return unlink(
            $this->get_parameter('videos_directory') . $file
        );
    }

    public function rename_action($from, $to)
    {
        rename(
            $this->get_parameter('videos_directory') . $from,
            $this->get_parameter('videos_directory') . $to
        );
        return $to;
    }

    public function doaction($query)
    {
        $action = $query->get("action");
        switch($action)
        {
            case "rename":
                return $this->rename_action($query->get("from"), $query->get("to"));
            case "delete":
                return $this->delete_action($query->get("file"));
            default:
                return [
                    "error" => "Command not found",
                    "query" => $query
            ];
        }
    }
    
    public function get_globals()
    {
        return [
            "classes"          => $this->get_classes(),
            "informations"     => $this->get_informations(),
            "sponsors"         => $this->get_sponsors(),
            "languages"        => $this->get_languages(),
            "current_language" => $this->get_language(),
            "language_code"    => $this->get_language_code()
        ];
    }
}
