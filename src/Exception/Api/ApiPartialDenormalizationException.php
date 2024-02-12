<?php

declare(strict_types=1);

namespace App\Exception\Api;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;

final class ApiPartialDenormalizationException extends Exception
{
    /**
     * @var NotNormalizableValueException[]
     */
    private array $errors;

    public function __construct(PartialDenormalizationException $exception)
    {
        parent::__construct('Error validation request', Response::HTTP_BAD_REQUEST, $exception);

        $this->errors =  $exception->getErrors();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
