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
class Globals
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

    public $request;
    public $videos;
    public $sponsors;
    public $designs;
    public $current_design;

    public function __construct(RequestStack $rs, ContainerInterface $ci)
    {
        $this->requestStack = $rs;
        $this->container = $ci;

        $this->request =  $this->requestStack->getCurrentRequest();

        $this->videos = self::load_yaml_file($this->container->getParameter('file_videos'));
        $this->sponsors = self::load_yaml_file($this->container->getParameter('file_sponsors'));
        $this->designs = self::load_yaml_file($this->container->getParameter('file_designs'));

        $this->current_design = $this->request == null ?
            $this->container->getParameter("default_design") :
            $this->request->cookies->get('design') ?: 
                $this->container->getParameter("default_design");

        
    }

    public function get_parameter($name)
    {
        return $this->container->getParameter($name);
    }
}
