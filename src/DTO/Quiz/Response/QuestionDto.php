<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Response;

final readonly class QuestionDto
{
    public function __construct(
        public int $id,
        public ?string $text = null,
        /** @var AnswerDto[] */
        public array $answers = [],
    ) {
    }
}
