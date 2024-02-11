<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

final readonly class AnswerDto
{
    public function __construct(
        public int $id,
        public string $text,
        public bool $correct,
        public bool $selected = false,
    ) {
    }
}
