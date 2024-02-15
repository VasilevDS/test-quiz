<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\Vote;

use App\Controller\Api\ApiController;
use App\DTO\Quiz\Request\VoteQuizRequest;
use App\Infrastructure\RequestMapper\MapRequestPayloadValueResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class VoteController extends ApiController
{
    #[Route(path: '/api/quiz/vote', name: 'api_quiz_vote', methods: ['POST'])]
    public function pass(
        #[MapRequestPayload(resolver: MapRequestPayloadValueResolver::class)]
        VoteQuizRequest $passQuizRequest,
        Handler $handler,
    ): JsonResponse {
        $quizSnapshotData = $handler->handle($passQuizRequest);

        return $this->json($quizSnapshotData, context: ['groups' => 'result']);
    }
}
