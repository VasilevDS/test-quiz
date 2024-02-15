<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\View;

use App\DTO\Quiz\Response\ViewQuizResponse;
use App\Factory\Quiz\View\ViewQuizResponseFactory;
use App\Repository\Quiz\QuizRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class Handler
{
    public function __construct(
        private QuizRepository $quizRepository,
        private ViewQuizResponseFactory $quizDtoFactory,
    ) {
    }

    public function handle(int $quizId): ViewQuizResponse
    {
        $quiz = $this->quizRepository->findByIdWithQuestionsAndAnswer($quizId);

        if (null === $quiz) {
            throw new NotFoundHttpException('Quiz not found');
        }

        return $this->quizDtoFactory->fromQuiz($quiz);
    }
}
