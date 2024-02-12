<?php

declare(strict_types=1);

namespace App\Factory\Quiz;

use App\DTO\Quiz\Response\AnswerDto;
use App\Entity\Quiz\Answer;

final class AnswerDtoFactory
{
    public function fromAnswer(Answer $answer): AnswerDto
    {
        return new AnswerDto($answer->getId(), $answer->getText(), $answer->isCorrect());
    }

    /**
     * @param Answer[] $answers
     * @return AnswerDto[]
     */
    public function fromCollectionAnswer(array $answers): array
    {
        shuffle($answers);

        $result = [];
        foreach ($answers as $answer) {
            $result[] = $this->fromAnswer($answer);
        }

        return $result;
    }
}
