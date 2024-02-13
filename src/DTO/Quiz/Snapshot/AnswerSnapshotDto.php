<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Snapshot;

final readonly class AnswerSnapshotDto
{
    public function __construct(
        public int $id,
        public string $text,
        public bool $isCorrect,
        public bool $isSelected = false,
    ) {
    }
}
