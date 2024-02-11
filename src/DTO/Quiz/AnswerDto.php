<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

use Symfony\Component\Serializer\Annotation\Groups;

final readonly class AnswerDto
{
    public function __construct(
        #[Groups(['view'])]
        public int $id,
        #[Groups(['view'])]
        public string $text,
        public bool $correct,
        public bool $selected = false,
    ) {
    }
}
