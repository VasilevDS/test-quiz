<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\Vote;

use App\DTO\Quiz\Request\VoteQuizRequest;
use App\DTO\Quiz\Snapshot\QuizSnapshotData;
use App\Exception\Api\ApiValidationRequestException;
use App\Service\Query\QuizResultService;
use App\Validator\Quiz\Vote\VoteQuizConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class Handler
{
    public function __construct(
        private ValidatorInterface $validator,
        private QuizResultService $quizResultService,
    ) {
    }

    public function handle(VoteQuizRequest $voteQuizRequest): QuizSnapshotData
    {
        $violationList = $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
        if (0 !== $violationList->count()) {
            throw new ApiValidationRequestException($violationList);
        }

        return $this->quizResultService->calculateAndSaveResult($voteQuizRequest);
    }
}
