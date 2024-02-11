<?php

declare(strict_types=1);

namespace App\Controller\Quiz\View;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ViewQuizController extends ApiController
{
    #[Route(
        path: '/api/quiz/{quizId}',
        name: 'api_quiz_view',
        requirements: ['quizId' => '\d+'],
        methods: ['GET'],
    )]
    public function view(int $quizId, Handler $handler): JsonResponse
    {
        $quizDto = $handler->handle($quizId);

        return $this->json($quizDto, context: ['groups' => 'view']);
    }
}
