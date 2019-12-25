<?php

namespace App;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;

class Globals
{
    public $request;
    public $videos;
    public $designs;
    public $current_design;

    public function __construct(
        RequestStack $request_stack,
        ContainerInterface $container
    ) {
        $this->request =  $request_stack->getCurrentRequest();
        $this->videos = Yaml::parseFile($container->getParameter("file_videos"));
        $this->designs = Yaml::parseFile($container->getParameter("file_designs"));
        $this->current_design = $container->getParameter("default_design");

        if($this->request != null)
        {
            $cookie_design = $this->request->cookies->get("design");
            if($cookie_design != null ?? array_key_exists($cookie_design, $this->designs))
                $this->current_design = $cookie_design;
        }     
    }
}
