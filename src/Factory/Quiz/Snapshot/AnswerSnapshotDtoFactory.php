<?php

declare(strict_types=1);

namespace App\Factory\Quiz\Snapshot;

use App\DTO\Quiz\Request\VoteAnswer;
use App\DTO\Quiz\Snapshot\AnswerSnapshotDto;
use App\Entity\Quiz\Answer;
use Doctrine\Common\Collections\Collection;

final class AnswerSnapshotDtoFactory
{
    /**
     * @param Collection<int, Answer> $answers
     * @param VoteAnswer[] $answersRequest
     * @return AnswerSnapshotDto[]
     */
    public function fromVoteAnswers(Collection $answers, array $answersRequest): array
    {
        $selectedAnswerIds = $this->getSelectedAnswerIds($answersRequest);

        $result = [];
        foreach ($answers as $answer) {
            $isSelected = in_array($answer->getId(), $selectedAnswerIds, true);

            $result[] = new AnswerSnapshotDto(
                $answer->getId(),
                $answer->getText(),
                $answer->isCorrect(),
                $isSelected,
            );
        }

        return $result;
    }

    /**
     * @param VoteAnswer[] $answers
     * @return int[]
     */
    private function getSelectedAnswerIds(array $answers): array
    {
        $result = [];
        foreach ($answers as $answer) {
            if ($answer->selected) {
                $result[] = $answer->id;
            }
        }

        return $result;
    }
}
