<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostAnswer;
use App\Repository\PostAnswerRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends AbstractController
{

    public function forum(
        PostRepository $postRepository
    ){
        return $this->render('site/forum/forum.html.twig', [
            "posts" => $postRepository->findAll()
        ]);
    }

    public function post(
        Request $request,
        PostRepository $postRepository,
        PostAnswerRepository $postAnswerRepository,
        $id
    ){
        $post = $postRepository->find($id);

        if(!$post)
        {
            return $this->render('site/forum/post404.html.twig', [
                "id" => $id
            ]);
        }

        if(
            $request->isMethod('post') &&
            $request->request->has('post_text')
        ) {

            if($this->getUser() == null)
                return $this->redirectToRoute('route_account_register');
        
            if(!$this->getUser()->getEmailVerified())
            {
                $request->getSession()->set("account_errors", ["> Bitte bestätige deine Email zuerst"]);
                return $this->redirectToRoute("route_account_me");
            }

            $answer = new PostAnswer();
            $answer->setDate(new \DateTime("now"));
            $answer->setPost($post);
            $answer->setText($request->request->get('post_text'));
            $answer->setUser($this->getUser());
            $answer->setAccepted(false);

            $postAnswerRepository->save($answer);

            return $this->redirectToRoute('route_forum_post', [
                'id' => $id
            ]);
        }

        return $this->render('site/forum/post.html.twig', [
            "post" => $post
        ]);
    }

    public function create(
        Request $request,
        PostRepository $postRepository
    ){

        if($this->getUser() == null)
            return $this->redirectToRoute('route_account_register');
        
        if(!$this->getUser()->getEmailVerified())
        {
            $request->getSession()->set("account_errors", ["> Bitte bestätige deine Email zuerst"]);
            return $this->redirectToRoute("route_account_me");
        }

        if(
            $request->isMethod('post') &&
            $request->request->has('post_title') &&
            $request->request->has('post_text') &&
            $request->request->has('post_type')
        ) {
            $post = new Post();
            $post->setDate(new \DateTime());
            $post->setTitle($request->request->get('post_title'));
            $post->setText($request->request->get("post_text"));
            $post->setType($request->request->get('post_type'));
            $post->setUser($this->getUser());

            $postRepository->save($post);

            return $this->redirectToRoute('route_forum');
        }

        return $this->render('site/forum/createpost.html.twig');
    }
}