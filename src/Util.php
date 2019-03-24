<?php

namespace App;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;

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

    /***********/
    /* Service */
    /***********/

    protected $requestStack;

    public function __construct(RequestStack $rs)
    {
        $this->requestStack = $rs;
    }

    public function load_yaml_file($path, int $flags = 0)
    {
        return Yaml::parseFile($path, $flags);
    }

    public function get_workspace_path()
    {
        return __DIR__ . '/../';
    }

    public function get_request_stack()
    {
        return $this->requestStack;
    }

    public function get_request()
    {
        return $this->get_request_stack()->getCurrentRequest();
    }

    public function get_cookies()
    {
        return $this->get_request()->cookies;
    }

    public function has_cookie($name)
    {
        return $this->get_cookies()->has($name);
    }

    public function get_language_code()
    {
        return $this->get_cookies()->get('language') ?: 'de';
    }

    public function get_languages()
    {
        return $this->load_yaml_file($this->get_workspace_path() . "public/translations/languages.yaml");
    }

    public function get_language()
    {
       return $this->get_languages()[$this->get_language_code()];
    }

    public function get_translations()
    {
        return $this->load_yaml_file($this->get_workspace_path() . $this->get_language()['file']);
    }

    public function trans($key)
    {
        try{
            return self::array_get($this->get_translations(), $key);
        }catch(\Throwable $e){
            return "{Couldn't translate.  key='" . $key . "', error='" . $e->getMessage() . "'}";
        }
    }

    public function get_public_path()
    {
        return $this->get_workspace_path() . 'public/';
    }

    public function get_video_config_path()
    {
        return $this->get_public_path() . 'videos/index.yaml';
    }

    public function get_video_config()
    {
        return $this->load_yaml_file($this->get_video_config_path());
    }

    public function get_video_config_content()
    {
        return file_get_contents($this->get_video_config_path());
    }

    public function get_video_files()
    {
        return self::get_finder()
            ->files()
            ->in($this->get_public_path() . 'videos/')
            ->name('*.mp4', '*.ogg')
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

    public function load_videos($class)
    {
        return $this->get_class($class)['chapters'];
    }

    public function get_class_name($class)
    {
        return $this->get_class($class)['name'];
    }

    public function get_informations()
    {
        return $this->load_yaml_file($this->get_public_path() . 'information/information.yaml');
    }

    public function get_default_class()
    {
        return self::get_classes()[5]['name'];
    }

    public function get_sponsors()
    {
        return $this->load_yaml_file($this->get_public_path() . 'information/sponsors.yaml');
    }

    public function delete_action($file)
    {
        return unlink(
            $this->get_public_path() . 'videos/' . $file
        );
    }

    public function rename_action($from, $to)
    {
        rename(
            $this->get_public_path() . 'videos/' . $from,
            $this->get_public_path() . 'videos/' . $to
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
