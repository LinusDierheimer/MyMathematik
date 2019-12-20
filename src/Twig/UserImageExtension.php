<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserImageExtension extends AbstractExtension
{

    protected $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('user_image_path', [$this, 'getImagePath'])
        ];
    }

    public function getImagePath(User $user)
    {
        $id = $user->getId();
        $pub_dir = $this->params->get("public_directory");

        foreach([".jpg", ".png"] as $ext)
        {
            $path = "/data/images/users/" . $id . $ext;
            if(file_exists($pub_dir . $path))
                return $path;
        }

        return $this->params->get("default_user_image");
    }
}