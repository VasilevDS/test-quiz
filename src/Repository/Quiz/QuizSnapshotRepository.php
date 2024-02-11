<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Entity\Quiz\QuizSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class QuizSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizSnapshot::class);
    }
}
