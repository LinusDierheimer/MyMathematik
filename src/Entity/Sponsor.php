<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SponsorRepository")
 */
class Sponsor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     */
    private $money;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function setMoney(float $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
