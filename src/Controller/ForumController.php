<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{

    public function forum(
        QuestionRepository $questionRepository
    ){
        return $this->render('site/forum/forum.html.twig', [
            "questions" => $questionRepository->findAll()
        ]);
    }
}