<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

use Symfony\Component\Serializer\Annotation\Groups;

final readonly class QuestionDto
{
    public function __construct(
        #[Groups(['view'])]
        public int $id,
        #[Groups(['view'])]
        public string $text,
        /** @var AnswerDto[] */
        #[Groups(['view'])]
        public array $answers,
    ) {
    }
}
