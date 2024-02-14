<?php

declare(strict_types=1);

namespace App\Factory\Quiz\Snapshot;

use App\DTO\Quiz\Request\VoteAnswer;
use App\DTO\Quiz\Request\VoteQuestion;
use App\DTO\Quiz\Snapshot\AnswerSnapshotDto;
use App\DTO\Quiz\Snapshot\QuestionSnapshotDto;
use App\Entity\Quiz\Question;
use Doctrine\Common\Collections\Collection;

final readonly class QuestionSnapshotDtoFactory
{
    public function __construct(private AnswerSnapshotDtoFactory $answerSnapshotDtoFactory) {
    }

    /**
     * @param Collection<int, Question> $questions
     * @param VoteQuestion[] $questionsRequest
     * @return QuestionSnapshotDto[]
     */
    public function fromQuestionCollectionAndVoteQuestions(Collection $questions, array $questionsRequest): array
    {
        $questionsSnapshot = [];

        foreach ($questionsRequest as $questionRequest) {
            $questionEntity = $questions->get($questionRequest->id);

            $questionsSnapshot[] = $this->fromQuestionAndVoteAnswers($questionEntity, $questionRequest->answers);
        }

        return $questionsSnapshot;
    }

    /**
     * @param VoteAnswer[] $answersRequest
     */
    public function fromQuestionAndVoteAnswers(Question $question, array $answersRequest): QuestionSnapshotDto
    {
        $answersSnapshot = $this->answerSnapshotDtoFactory->fromVoteAnswers($question->getAnswers(), $answersRequest);

        $isRight = $this->isRightChoice($answersSnapshot);

        return new QuestionSnapshotDto(
            $question->getId(),
            $question->getText(),
            $answersSnapshot,
            $isRight,
        );
    }

    /**
     * @param AnswerSnapshotDto[] $answerSnapshots
     */
    private function isRightChoice(array $answerSnapshots): bool
    {
        foreach ($answerSnapshots as $answerSnapshot) {
            if (false === $answerSnapshot->isCorrect && true === $answerSnapshot->isSelected) {
                return false;
            }
        }

        return true;
    }
}
