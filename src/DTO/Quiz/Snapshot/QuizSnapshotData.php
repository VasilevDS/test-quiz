<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Snapshot;

final readonly class QuizSnapshotData
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var QuestionSnapshotDto[] */
        public array $questions,
    ) {
    }
}
