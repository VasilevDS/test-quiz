<?php

declare(strict_types=1);

namespace App\Infrastructure\RequestMapper;

use App\Exception\Api\ApiValidationRequestException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsDecorator(decorates: MapRequestPayloadValueResolver::class)]
final readonly class ValidatingValueResolver implements ValueResolverInterface
{
    public function __construct(
        #[AutowireDecorated] private ValueResolverInterface $resolver,
        private ValidatorInterface $validator,
    ) {
    }

    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $groups = $this->getValidationGroups($argument);

        foreach ($this->resolver->resolve($request, $argument) as $value) {
            $violationList = $this->validator->validate($value, groups: $groups);

            if ($violationList->count() > 0) {
                throw new ApiValidationRequestException($violationList);
            }

            yield $value;
        }
    }

    public function getValidationGroups(ArgumentMetadata $argument): string|GroupSequence|array|null
    {
        $attributes = $argument->getAttributes(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF);

        if (isset($attributes[0])) {
            return $attributes[0]->validationGroups;
        }

        return null;
    }
}
