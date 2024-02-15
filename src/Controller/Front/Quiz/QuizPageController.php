<?php

declare(strict_types=1);

namespace App\Controller\Front\Quiz;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuizPageController extends AbstractController
{
    #[Route('/quiz/view/{id}', name: 'homepage')]
    public function viewPage(int $id): Response
    {
        return $this->render('quiz/view.html.twig', ['quizId' => $id]);
    }

    #[Route('/quiz/result/quiz_results.html', name: 'result')]
    public function resultPage(): Response
    {
        return $this->render('quiz/quiz_results.html');
    }

    #[Route('/quiz/list', name: 'list')]
    public function listPage(): Response
    {
        return $this->render('quiz/list.html');
    }
}
