<?php

declare(strict_types=1);

namespace App\Exception\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ApiValidationRequestException extends ValidatorException
{
    public function __construct(
        public readonly ConstraintViolationListInterface $constraintViolationList,
    ) {
        parent::__construct('Error validation request', Response::HTTP_BAD_REQUEST);
    }
}
