<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Snapshot;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class QuizSnapshotData
{
    public function __construct(
        #[Groups('result')]
        public int $id,
        #[Groups('result')]
        public string $name,
        /** @var QuestionSnapshotDto[] */
        #[Groups('result')]
        public array $questions,
    ) {
    }
}
