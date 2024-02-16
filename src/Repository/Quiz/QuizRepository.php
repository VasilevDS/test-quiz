<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function findByIdWithQuestionsAndAnswer(int $id): ?Quiz
    {
        $qb = $this->createQueryBuilder('quiz');
        $qb
            ->addSelect('question')
            ->addSelect('answer')
            ->innerJoin('quiz.questions', 'question')
            ->innerJoin('question.answers', 'answer')
            ->where('quiz.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
