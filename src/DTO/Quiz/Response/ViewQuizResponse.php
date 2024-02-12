<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Response;

final readonly class ViewQuizResponse
{
    public function __construct(
        public ?string $name = null,
        /** @var QuestionDto[] */
        public array $questions = [],
    ) {
    }
}
