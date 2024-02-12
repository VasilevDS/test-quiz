<?php

declare(strict_types=1);

namespace App\DTO\Common\Response;

final readonly class ErrorDescription
{
    public function __construct(
        public string $propertyPath,
        public string $message,
    ) {
    }
}
