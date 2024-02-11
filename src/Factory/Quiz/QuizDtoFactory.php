<?php

declare(strict_types=1);

namespace App\Factory\Quiz;

use App\Entity\Quiz\Quiz;
use App\DTO\Quiz\QuizDto;

final readonly class QuizDtoFactory
{
    public function __construct(private QuestionDtoFactory $questionDtoFactory)
    {
    }

    public function fromQuiz(Quiz $quiz): QuizDto
    {
        $questionDtoList = $this->questionDtoFactory->fromCollectionQuestion($quiz->getQuestions()->toArray());

        return new QuizDto($quiz->getName(), $questionDtoList);
    }
}
