<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

final readonly class QuestionDto
{
    public function __construct(
        public string $text,
        /** @var AnswerDto[] */
        public array $answers,
    ) {
    }
}
