<?php

declare(strict_types=1);

namespace App\Validator\Quiz\Vote;

use App\DTO\Quiz\Request\VoteAnswer;
use App\DTO\Quiz\Request\VoteQuizRequest;
use App\Entity\Quiz\Question;
use App\Repository\Quiz\QuizRepository;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class VoteQuizConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly QuizRepository $quizRepository,
    ) {
    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof VoteQuizConstraint) {
            throw new UnexpectedTypeException($constraint, VoteQuizConstraint::class);
        }

        if (!$value instanceof VoteQuizRequest) {
            throw new UnexpectedTypeException($value, VoteQuizRequest::class);
        }

        $quiz = $this->quizRepository->findByIdWithQuestionsAndAnswer($value->id);

        if (null === $quiz) {
            $this->context
                ->buildViolation('Quiz not found')
                ->atPath('id')
                ->addViolation();

            return;
        }

        $questionCollection = $quiz->getQuestions();

        $questionCount = $questionCollection->count();
        if ($questionCount !== count($value->questions)) {
            $this->context
                ->buildViolation('Not all questions have been submitted')
                ->atPath('questions')
                ->addViolation();
        }


        foreach ($value->questions as $questionPosition => $requestQuestion) {
            $question = $questionCollection->get($requestQuestion->id);

            if (null === $question) {
                $this->context
                    ->buildViolation('Invalid question ID passed')
                    ->atPath(sprintf('questions[%d].id', $questionPosition))
                    ->addViolation();

                continue;
            }

            $this->validateAnswers($requestQuestion->answers, $question, $questionPosition);
        }
    }

    /**
     * @param VoteAnswer[] $requestAnswers
     */
    private function validateAnswers(array $requestAnswers, Question $question, int $questionPosition): void
    {
        $selectedAnswer = false;
        foreach ($requestAnswers as $answerPosition => $requestAnswer) {
            $answer = $question->getAnswers()->get($requestAnswer->id);

            if (null === $answer) {
                $this->context
                    ->buildViolation('Invalid answer ID passed')
                    ->atPath(sprintf('questions[%d].answers[%d].id', $questionPosition, $answerPosition))
                    ->addViolation();
            }

            if ($requestAnswer->selected) {
                $selectedAnswer = true;
            }
        }

        if (!$selectedAnswer) {
            $this->context
                ->buildViolation('No answer selected')
                ->atPath(sprintf('questions[%d]', $questionPosition))
                ->addViolation();
        }
    }
}
