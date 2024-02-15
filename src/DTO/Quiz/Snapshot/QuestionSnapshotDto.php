<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Snapshot;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class QuestionSnapshotDto
{
    public function __construct(
        public int $id,
        #[Groups('result')]
        public string $text,
        /** @var AnswerSnapshotDto[] */
        public array $answers,
        #[Groups('result')]
        public ?bool $isRight = null,
    ) {
    }
}
