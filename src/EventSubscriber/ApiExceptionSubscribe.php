<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\Api\ApiPartialDenormalizationException;
use App\Exception\Api\ApiValidationRequestException;
use App\Factory\Common\Response\ErrorResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ApiExceptionSubscribe implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ErrorResponseFactory $errorResponseFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'getResponseValidation',
        ];
    }

    public function getResponseValidation(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiValidationRequestException) {
            $errorResponse = $this->errorResponseFactory->fromApiValidationRequestException($exception);

            $responseData = $this->serializer->serialize($errorResponse, 'json');

            $event->setResponse(new JsonResponse($responseData, $exception->getCode(), [], true));
        }

        if ($exception instanceof ApiPartialDenormalizationException) {
            $errorResponse = $this->errorResponseFactory->fromApiPartialDenormalizationException($exception);

            $responseData = $this->serializer->serialize($errorResponse, 'json');

            $event->setResponse(new JsonResponse($responseData, $exception->getCode(), [], true));
        }
    }
}
