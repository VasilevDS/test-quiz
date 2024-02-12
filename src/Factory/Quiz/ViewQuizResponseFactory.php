<?php

declare(strict_types=1);

namespace App\Factory\Quiz;

use App\DTO\Quiz\Response\ViewQuizResponse;
use App\Entity\Quiz\Quiz;

final readonly class ViewQuizResponseFactory
{
    public function __construct(private QuestionDtoFactory $questionDtoFactory)
    {
    }

    public function fromQuiz(Quiz $quiz): ViewQuizResponse
    {
        $questionDtoList = $this->questionDtoFactory->fromCollectionQuestion($quiz->getQuestions()->toArray());

        return new ViewQuizResponse($quiz->getName(), $questionDtoList);
    }
}
