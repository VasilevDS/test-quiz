<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

final readonly class QuizDto
{
    public function __construct(
        public string $name,
        /** @var QuestionDto[] */
        public array $questions,
    ) {
    }
}
