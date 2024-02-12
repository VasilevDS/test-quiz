<?php

declare(strict_types=1);

namespace App\Infrastructure\RequestMapper;

use App\Exception\Api\ApiPartialDenormalizationException;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final readonly class MapRequestPayloadValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws ApiPartialDenormalizationException
     */
    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF);

        if (!isset($attributes[0])) {
            return;
        }

        /** @var MapRequestPayload $attribute */
        $attribute = $attributes[0];

        try {
            yield $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                'json',
                array_merge(
                    [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true],
                    $attribute->serializationContext,
                ),
            );
        } catch (PartialDenormalizationException $exception) {
            throw new ApiPartialDenormalizationException($exception);
        } catch (Throwable) {
            // you can add logging
            throw new HttpException(Response::HTTP_NOT_IMPLEMENTED);
        }
    }
}
