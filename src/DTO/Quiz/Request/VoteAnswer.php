<?php

declare(strict_types=1);

namespace App\DTO\Quiz\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class VoteAnswer
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Positive()]
        public int $id,
        public bool $selected = false,
    ) {
    }
}
