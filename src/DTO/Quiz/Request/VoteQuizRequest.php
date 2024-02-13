<?php

declare(strict_types = 1);

namespace App\DTO\Quiz\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class VoteQuizRequest
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Positive()]
        public int $id,
        /** @var VoteQuestion[] */
        #[Assert\Valid()]
        #[Assert\Count(min: 1)]
        public array $questions = [],
    ) {
    }
}
