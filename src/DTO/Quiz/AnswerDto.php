<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

final readonly class AnswerDto
{
    public function __construct(
        public string $text,
        public bool $correct,
    ) {
    }
}
