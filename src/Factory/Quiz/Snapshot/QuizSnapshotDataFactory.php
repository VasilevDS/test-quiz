<?php

declare(strict_types=1);

namespace App\Factory\Quiz\Snapshot;

use App\DTO\Quiz\Request\VoteQuizRequest;
use App\DTO\Quiz\Snapshot\QuizSnapshotData;
use App\Entity\Quiz\Quiz;

final readonly class QuizSnapshotDataFactory
{
    public function __construct(private QuestionSnapshotDtoFactory $questionSnapshotDtoFactory) {
    }

    public function fromVoteQuizRequest(Quiz $quiz, VoteQuizRequest $quizRequest): QuizSnapshotData
    {
        $questionSnapshots = $this->questionSnapshotDtoFactory->fromQuestionCollectionAndVoteQuestions(
            $quiz->getQuestions(),
            $quizRequest->questions,
        );

        return new QuizSnapshotData(
            $quiz->getId(),
            $quiz->getName(),
            $questionSnapshots,
        );
    }
}
