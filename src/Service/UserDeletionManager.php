<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\PostAnswerRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserDeletionManager
{

    protected $userRepository;
    protected $postRepository;
    protected $postAnswerRepository;
    protected $tokenStorage;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        PostAnswerRepository $postAnswerRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->postAnswerRepository = $postAnswerRepository;
        $this->tokenStorage = $tokenStorage;
    }

    private function getDeletedUser()
    {
        $deletedUser = $this->userRepository->findOneByEmail("deleted");

        if($deletedUser == null)
        {
            $deletedUser = new User();
            $deletedUser->setEmail("deleted");
            $deletedUser->setRoles(["ROLE_DELETED"]);
            $deletedUser->setPassword("");
            $deletedUser->setShowName("[deleted user]");
            $deletedUser->setEmailVerified(true);

            $this->userRepository->save($deletedUser);
        }

        return $deletedUser;
    }

    public function delete(User $user)
    {

        $deletedUser = $this->getDeletedUser();

        $postAnswers = $user->getPostAnswers();
        foreach($postAnswers as $postAnswer)
        {
            $postAnswer->setUser($deletedUser);
            $this->postAnswerRepository->persist($postAnswer);
        }
        $this->postAnswerRepository->flush();

        $posts = $user->getPosts();
        foreach($posts as $post)
        {
            $post->setUser($deletedUser);
            $this->postRepository->persist($post);
        }
        $this->postRepository->flush();

        $this->tokenStorage->setToken(null);
        $this->userRepository->delete($user);
    }

}