<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Response;

final readonly class AnswerDto
{
    public function __construct(
        public int $id,
        public ?string $text = null,
    ) {
    }
}
