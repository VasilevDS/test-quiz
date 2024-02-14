<?php

declare(strict_types=1);

namespace App\Service\Query;

use App\DTO\Quiz\Snapshot\QuizSnapshotData;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\QuizSnapshot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class QuizSnapshotCreator
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function createAndSave(Quiz $quiz, QuizSnapshotData $quizSnapshotData): void
    {
        $snapshotDataArray = $this->normalizer->normalize($quizSnapshotData);

        $quizSnapshot = new QuizSnapshot();
        $quizSnapshot
            ->setQuiz($quiz)
            ->setData($snapshotDataArray);

        $this->entityManager->persist($quizSnapshot);
        $this->entityManager->flush();
    }
}
