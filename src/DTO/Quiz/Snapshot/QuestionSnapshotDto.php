<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Snapshot;

final readonly class QuestionSnapshotDto
{
    public function __construct(
        public int $id,
        public string $text,
        /** @var AnswerSnapshotDto[] */
        public array $answers,
        public ?bool $isRight = null,
    ) {
    }
}
