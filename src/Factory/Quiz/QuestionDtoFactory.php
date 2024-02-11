<?php

declare(strict_types=1);

namespace App\Factory\Quiz;

use App\DTO\Quiz\QuestionDto;
use App\Entity\Quiz\Question;

final readonly class QuestionDtoFactory
{
    public function __construct(
        private AnswerDtoFactory $answerDtoFactory,
    ) {
    }

    public function fromQuestion(Question $question): QuestionDto
    {
        $answers = $question->getAnswers();

        $answerDtoList = $this->answerDtoFactory->fromCollectionAnswer($answers->toArray());

        return new QuestionDto($question->getId(), $question->getText(), $answerDtoList);
    }

    /**
     * @param Question[] $questions
     * @return QuestionDto[]
     */
    public function fromCollectionQuestion(array $questions): array
    {
        shuffle($questions);

        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->fromQuestion($question);
        }

        return $result;
    }
}
