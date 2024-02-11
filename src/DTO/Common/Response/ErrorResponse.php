<?php

declare(strict_types=1);

namespace App\DTO\Common\Response;

final readonly class ErrorResponse
{
    public function __construct(
        public string $message,
        /** @var string[] */
        public array $errors = [],
    ) {
    }
}
