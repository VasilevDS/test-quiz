<?php

declare(strict_types=1);

namespace App\Controller\Quiz\View;

use App\Factory\Quiz\QuizDtoFactory;
use App\DTO\Quiz\QuizDto;
use App\Repository\Quiz\QuizRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class Handler
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizDtoFactory $quizDtoFactory,
    ) {
    }

    public function handle(int $quizId): QuizDto
    {
        $quiz = $this->quizRepository->findByIdWithQuestionsAndAnswer($quizId);

        if (null === $quiz) {
            throw new NotFoundHttpException('Quiz not found');
        }

        return $this->quizDtoFactory->fromQuiz($quiz);
    }
}
