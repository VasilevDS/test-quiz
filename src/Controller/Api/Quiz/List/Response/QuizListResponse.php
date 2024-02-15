<?php

declare(strict_types=1);

namespace App\Controller\Api\Quiz\List\Response;

use App\DTO\Quiz\QuizShortDto;

final readonly class QuizListResponse
{
    /**
     * @param QuizShortDto[] $data
     */
    public function __construct(public array $data)
    {
    }
}
