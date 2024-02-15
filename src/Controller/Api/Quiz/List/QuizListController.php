<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\List;

use App\Controller\Api\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class QuizListController extends ApiController
{
    #[Route(
        path: '/api/quiz/list',
        name: 'api_quiz_list',
        methods: ['GET'],
    )]
    public function __invoke(Handler $handler): JsonResponse
    {
        return $this->json($handler->handle());
    }
}
