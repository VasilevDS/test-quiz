<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\List;

use App\Controller\Api\Quiz\List\Response\QuizListResponse;
use App\Query\Quiz\QuizQueryExecutor;

final readonly class Handler
{
    public function __construct(private QuizQueryExecutor $quizQueryExecutor)
    {
    }

    public function handle(): QuizListResponse
    {
        $models = $this->quizQueryExecutor->getQuizListDto();

        return new QuizListResponse($models);
    }
}
