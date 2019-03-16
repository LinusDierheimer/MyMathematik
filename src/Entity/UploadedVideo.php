<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UploadedVideo
{
    /* 
     * @Assert\File(mimeTypes={ "video/*" } 
     */
    private $video;

    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo($video)
    {
        $this->video = $video;
        return $this;
    }
}
