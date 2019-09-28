<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *      fields={"email"},
 *      message="There is already an account with this email"
 * )
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostAnswer", mappedBy="user")
     */
    private $postAnswers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $showName;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->postAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePosts(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostAnswer[]
     */
    public function getPostAnswers(): Collection
    {
        return $this->postAnswers;
    }

    public function addPostAnswer(PostAnswer $postAnswer): self
    {
        if (!$this->postAnswers->contains($postAnswer)) {
            $this->postAnswers[] = $postAnswer;
            $postAnswer->setUser($this);
        }

        return $this;
    }

    public function removePostAnswer(PostAnswer $postAnswer): self
    {
        if ($this->postAnswers->contains($postAnswer)) {
            $this->postAnswers->removeElement($postAnswer);
            // set the owning side to null (unless already changed)
            if ($postAnswer->getUser() === $this) {
                $postAnswer->setUser(null);
            }
        }

        return $this;
    }

    public function getShowName(): ?string
    {
        return $this->showName;
    }

    public function setShowName(?string $showName): self
    {
        $this->showName = $showName;

        return $this;
    }

    public function getName(): string 
    {
        $showName = $this->getShowName();

        if($showName != null && $showName != "")
            return $showName;

        $email = $this->getEmail();

        return substr(
            str_replace("@", " ", $email),
            0,
            strrpos($email, ".")
        );
    }

}