<?php

declare(strict_types=1);

namespace App\DTO\Quiz;

use Symfony\Component\Serializer\Annotation\Groups;

final readonly class QuizDto
{
    public function __construct(
        #[Groups(['view'])]
        public string $name,
        /** @var QuestionDto[] */
        #[Groups(['view'])]
        public array $questions,
    ) {
    }
}
