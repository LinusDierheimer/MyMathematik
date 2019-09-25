<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionAnswer;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends AbstractController
{

    public function forum(
        QuestionRepository $questionRepository
    ){
        return $this->render('site/forum/forum.html.twig', [
            "questions" => $questionRepository->findAll()
        ]);
    }

    public function post(
        QuestionRepository $questionRepository,
        Request $request,
        $id
    ){
        $post = $questionRepository->find($id);

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
            $answer = new QuestionAnswer();
            $answer->setQuestion($post);
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
            $question = new Question();
            $question->setDate(new \DateTime("now"));
            $question->setTitle($request->request->get('post_title'));
            $question->setText($request->request->get("post_text"));
            $question->setType($request->request->get('post_type'));
            $question->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('route_forum');
        }

        return $this->render('site/forum/createpost.html.twig');
    }
}