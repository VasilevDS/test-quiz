<?php

declare(strict_types=1);

namespace App\Factory\Common\Response;

use App\DTO\Common\Response\ErrorDescription;
use App\DTO\Common\Response\ErrorResponse;
use App\Exception\Api\ApiPartialDenormalizationException;
use App\Exception\Api\ApiValidationRequestException;

final class ErrorResponseFactory
{
    public function fromApiValidationRequestException(ApiValidationRequestException $exception): ErrorResponse
    {
        $errors = [];
        foreach ($exception->constraintViolationList as $violation) {
            $message = $violation->getParameters()['hint'] ?? $violation->getMessage();
            $errors[] = new ErrorDescription($violation->getPropertyPath(), $message);
        }

        return new ErrorResponse($exception->getMessage(), $errors);
    }

    public function fromApiPartialDenormalizationException(ApiPartialDenormalizationException $exception): ErrorResponse
    {
        $errors = [];
        foreach ($exception->getErrors() as $valueException) {
            $message = sprintf(
                'The type must be one of "%s" ("%s" given).',
                implode(', ', $valueException->getExpectedTypes()),
                $valueException->getCurrentType(),
            );

            $errors[] = new ErrorDescription($valueException->getPath(), $message);
        }

        return new ErrorResponse($exception->getMessage(), $errors);
    }
}
