<?php

declare(strict_types=1);

namespace App\Service\Query;

use App\DTO\Quiz\Request\VoteQuizRequest;
use App\DTO\Quiz\Snapshot\QuizSnapshotData;
use App\Entity\Quiz\Quiz;
use App\Factory\Quiz\Snapshot\QuizSnapshotDataFactory;
use App\Repository\Quiz\QuizRepository;

final readonly class QuizResultService
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizSnapshotDataFactory $quizSnapshotDataFactory,
        private QuizSnapshotCreator $quizSnapshotCreator,
    ) {
    }

    public function calculateAndSaveResult(VoteQuizRequest $quizRequest): QuizSnapshotData
    {
        /** @var Quiz $quiz */
        $quiz = $this->quizRepository->findByIdWithQuestionsAndAnswer($quizRequest->id);

        $quizSnapshotData = $this->quizSnapshotDataFactory->fromVoteQuizRequest($quiz, $quizRequest);

        $this->quizSnapshotCreator->createAndSave($quiz, $quizSnapshotData);

        return $quizSnapshotData;
    }
}
