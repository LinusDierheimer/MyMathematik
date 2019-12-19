<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Parsedown;

class MarkdownLoadExtension extends AbstractExtension
{

    protected $request;
    protected $container;
    protected $parsedown;

    public function __construct(RequestStack $requestStack, ContainerInterface $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->container = $container;
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(false);
        $this->parsedown->setMarkupEscaped(true);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('load_markdown', [$this, 'load_markdown'], ["is_safe" => ["html"]]),
            new TwigFunction('load_local_markdown', [$this, 'load_local_markdown'], ["is_safe" => ["html"]])
        ];
    }

    private function load($path)
    {
        return 
            '<div class="markdown">' .
                $this->parsedown->text(
                    file_get_contents($path)
                ) .
            '</div>';
    }

    public function load_markdown($file, $extension = "md")
    {
        $path = $this->container->getParamter("translations_directory") . "markdown/" . $file . "." . $extension;
        return load($path);
    }

    public function load_local_markdown($file, $locale = null, $extension = "md")
    {
        $locale = $locale ?: $this->request->getLocale();

        $base_path = $this->container->getParameter("translations_directory") . "markdown/" . $file . ".";

        $path = $base_path . $locale . "." . $extension;
        if(!file_exists($path))
            $path = $base_path . $this->container->getParameter("locale") . "." . $extension;

        return $this->load($path);
    }
}