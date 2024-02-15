<?php

declare(strict_types=1);

namespace App\Query\Quiz;

use App\DTO\Quiz\QuizShortDto;
use App\Repository\Quiz\QuizRepository;

final readonly class QuizQueryExecutor
{
    public function __construct(private QuizRepository $quizRepository)
    {
    }

    /**
     * @return QuizShortDto[]
     */
    public function getQuizListDto(): array
    {
        $qb = $this->quizRepository->createQueryBuilder('quiz');
        $qb->select('NEW '. QuizShortDto::class . '(quiz.id, quiz.name)');

        return $qb->getQuery()->getResult();
    }
}
