<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FillQuizCommand extends Command
{
    private const array QUESTIONS_WITH_ANSWER = [
        '1 + 1 =' => [
            '3' => false,
            '2' => true,
            '0' => false,
        ],
        '2 + 2 =' => [
            '4' => true,
            '3 + 1' => true,
            '10' => false,
        ],
        '3 + 3 =' => [
            '1 + 5' => true,
            '1' => false,
            '6' => true,
            '2 + 4' => false,
        ],
        '4 + 4 =' => [
            '8' => true,
            '4' => false,
            '0' => false,
            '0 + 8' => true,
        ],
        '5 + 5 =' => [
            '6' => false,
            '18' => false,
            '10' => true,
            '9' => false,
            '0' => false,
        ],
        '6 + 6 =' => [
            '3' => false,
            '9' => false,
            '0' => false,
            '12' => true,
            '5 + 7' => true,
        ],
        '7 + 7 =' => [
            '5' => false,
            '14' => true,
        ],
        '8 + 8 =' => [
            '16' => true,
            '12' => false,
            '9' => false,
            '5' => false,
        ],
        '9 + 9 =' => [
            '18' => true,
            '9' => false,
            '17 + 1' => true,
            '2 + 16' => true,
        ],
        '10 + 10 =' => [
            '0' => false,
            '2' => false,
            '8' => false,
            '20' => true,
        ],
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct('app:fill-quiz');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $quiz = new Quiz();
        $quiz->setName('Test-quiz');

        $this->fillQuestionForQuiz($quiz);

        $this->entityManager->persist($quiz);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function fillQuestionForQuiz(Quiz $quiz): void
    {
        foreach (self::QUESTIONS_WITH_ANSWER as $questionText => $answers) {
            $question = new Question();
            $question->setText($questionText);

            foreach ($answers as $answerText => $isCorrect) {
                $answer = new Answer();
                $answer
                    ->setText((string)$answerText)
                    ->setCorrect($isCorrect);

                $question->addAnswer($answer);

                $this->entityManager->persist($answer);
            }

            $this->entityManager->persist($question);

            $quiz->addQuestion($question);
        }
    }
}
