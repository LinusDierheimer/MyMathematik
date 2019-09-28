<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostAnswer;
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
        PostRepository $postRepository,
        Request $request,
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
            $answer = new PostAnswer();
            $answer->setDate(new \DateTime("now"));
            $answer->setPost($post);
            $answer->setText($request->request->get('post_text'));
            $answer->setUser($this->getUser());
            $answer->setAccepted(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('route_forum_post', [
                'id' => $id
            ]);
        }

        return $this->render('site/forum/post.html.twig', [
            "post" => $post
        ]);
    }

    public function create(
        Request $request
    ){
        if(
            $request->isMethod('post') &&
            $request->request->has('post_title') &&
            $request->request->has('post_text') &&
            $request->request->has('post_type')
        ) {
            $post = new Post();
            $post->setDate(new \DateTime("now"));
            $post->setTitle($request->request->get('post_title'));
            $post->setText($request->request->get("post_text"));
            $post->setType($request->request->get('post_type'));
            $post->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('route_forum');
        }

        return $this->render('site/forum/createpost.html.twig');
    }
}