<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Common\Response\ErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    protected function apiErrorResponse(
        string $message,
        array $errors = [],
        int $code = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {

        return $this->json(new ErrorResponse($message, $errors), $code);
    }
}
