<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RequestListener
{

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if($request->cookies->has("language"))
        {
            $request->setLocale(
                $request->cookies->get("language")
            );
        }

        //Return error site for Internet Explorer users, to make the web a better place
        $userAgent = $request->headers->get("User-Agent");
        if(
            strpos($userAgent, "MSIE") !== false ||
            strpos($userAgent, "Trident") !== false
        ){
            $content = $this->container->get("twig")->render("site/ie.html.twig");
            $event->setResponse(new Response($content));
        }

    }
}