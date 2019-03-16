<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 
 * Utils that provides cached propertys like cookies and translations
 * 
 * @Autor Linus Dierheimer
 * 
 */
class Util
{

    public static function get_workspace_path()
    {
        static $workspace_path = null;
        if($workspace_path == null)
            $workspace_path = __DIR__ . '/../';
        return $workspace_path;
    }

    public static function get_request()
    {
        static $request = null;
        if($request == null)
            $request = Request::createFromGlobals();

        return $request;
    }

    public static function get_cookies()
    {
        static $cookies = null;
        if($cookies == null)
            $cookies = self::get_request()->cookies;
        return $cookies;
    }

    public static function has_cookie($name)
    {
        return self::get_cookies()->has($name);
    }

    public static function get_language_code()
    {
        static $language_code = null;
        if($language_code == null)
        {
            $language_code_temp = self::get_cookies()->get("language");
            $language_code = $language_code_temp == null ? 'de' : $language_code_temp;
        }
        return $language_code;
    }

    public static function get_languages()
    {
        static $languages = null;
        if($languages == null)
            $languages = Yaml::parseFile(self::get_workspace_path() . 'public/translations/languages.yaml');
        return $languages;
    }

    public static function get_language()
    {
        static $language = null;
        if($language == null)
        {
            $language = self::get_languages()[self::get_language_code()];
        }
        return $language;
    }

    public static function get_translations()
    {
        static $translations = null;
        if($translations == null)
            $translations = Yaml::parseFile(self::get_workspace_path() . self::get_language()['file']);
        return $translations;
    }

    public static function array_get($array, $indexes, $seperator = '.')
    {
        $index = strpos($indexes, $seperator);

        if($index === FALSE)
            return $array[$indexes];

        return self::array_get($array[substr($indexes, 0, $index)], substr($indexes, $index + 1), $seperator);
    }

    public static function trans($key)
    {
        try{
            return self::array_get(self::get_translations(), $key);
        }catch(\Throwable $e){
            return "{Couldn't translate key: " . $key . "}";
        }
    } 

    public static function get_finder()
    {
        $finder = new Finder();
        return $finder;
    }

    public static function get_classes_path()
    {
        return self::get_workspace_path() . '/public/videos/' . self::get_language_code();
    }

    public static function get_classes()
    {
        static $classes = null;
        if($classes == null)
            $classes = Yaml::parseFile(self::get_classes_path() . '/classes.yaml');
        return $classes;
    }

    public static function get_file_system()
    {
        static $filesystem = null;
        if($filesystem == null)
            $filesystem = new Filesystem();
        return $filesystem;
    }

    public static function class_exist($class)
    {
        return array_key_exists($class, self::get_classes());
    }

    public static function load_videos_by_path($path)
    {
        $videos = null;
        if($videos == null)
            $videos = Yaml::parseFile($path);
        return $videos;
    }

    public static function load_videos($class)
    {
        return self::load_videos_by_path(self::get_classes_path() . self::get_classes()[$class]['path']);
    }

    public static function get_class_name($class)
    {
        return self::get_classes()[$class]['name'];
    }

    public static function get_informations()
    {
        static $informations = null;
        if($informations == null)
            $informations = Yaml::parseFile(self::get_workspace_path() . 'public/information/information.yaml');
        return $informations;
    }

    public static function get_default_class(){
        return self::get_classes()[5]['name'];
    }

    public static function get_sponsors(){
        static $sponsors = null;
        if($sponsors == null)
            $sponsors = Yaml::parseFile(self::get_workspace_path() . 'public/information/sponsors.yaml');
        return $sponsors;
    }

    public static function get_globals(){
        return [
            "classes"          => self::get_classes(),
            "informations"     => self::get_informations(),
            "sponsors"         => self::get_sponsors(),
            "languages"        => self::get_languages(),
            "current_language" => self::get_language(),
            "language_code"    => self::get_language_code()
        ];
    }
}
