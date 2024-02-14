<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory\Quiz\Snapshot;

use App\DTO\Quiz\Request\VoteAnswer;
use App\DTO\Quiz\Snapshot\AnswerSnapshotDto;
use App\DTO\Quiz\Snapshot\QuestionSnapshotDto;
use App\Entity\Quiz\Answer;
use App\Entity\Quiz\Question;
use App\Factory\Quiz\Snapshot\AnswerSnapshotDtoFactory;
use App\Factory\Quiz\Snapshot\QuestionSnapshotDtoFactory;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class QuestionSnapshotDtoFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->factory = new QuestionSnapshotDtoFactory(new AnswerSnapshotDtoFactory());
    }

    /**
     * @throws Exception
     */
    #[DataProvider('fromVoteQuizRequestProvider')]
    public function testFromVoteQuizRequest(
        array $answerRequest,
        bool $isRight,
    ): void {
        $question = $this->createPartialMock(Question::class, ['getId']);
        $question
            ->method('getId')
            ->willReturn(1);
        $question->setText('test-question');

        $answerFirst = $this->createPartialMock(Answer::class, ['getId']);
        $answerFirst
            ->method('getId')
            ->willReturn(1);
        $answerFirst
            ->setText('test-answer-1')
            ->setCorrect(true);

        $answerSecond = $this->createPartialMock(Answer::class, ['getId']);
        $answerSecond
            ->method('getId')
            ->willReturn(2);
        $answerSecond
            ->setText('test-answer-2')
            ->setCorrect(true);

        $answerThird = $this->createPartialMock(Answer::class, ['getId']);
        $answerThird
            ->method('getId')
            ->willReturn(3);
        $answerThird
            ->setText('test-answer-3')
            ->setCorrect(false);

        $question->setAnswers(new ArrayCollection([
            1 => $answerFirst,
            2 => $answerSecond,
            3 => $answerThird
        ]));

        $snapshotData = $this->factory->fromQuestionAndVoteAnswers($question, $answerRequest);

        $expectedSnapshotDto = new QuestionSnapshotDto(
            1,
            'test-question',
            [
                new AnswerSnapshotDto(
                    1,
                    'test-answer-1',
                    true,
                    $answerRequest[0]->selected,
                ),
                new AnswerSnapshotDto(
                    2,
                    'test-answer-2',
                    true,
                    $answerRequest[1]->selected,
                ),
                new AnswerSnapshotDto(
                    3,
                    'test-answer-3',
                    false,
                    $answerRequest[2]->selected,
                ),
            ],
            $isRight,
        );

        self::assertEquals($expectedSnapshotDto, $snapshotData);
    }

    public static function fromVoteQuizRequestProvider(): \Generator
    {
        // one of the two correct options is selected
        yield [
            [
                new VoteAnswer(1, true),
                new VoteAnswer(2, false),
                new VoteAnswer(3, false),
            ],
            true,
        ];

        // two out of two correct options are selected
        yield [
            [
                new VoteAnswer(1, true),
                new VoteAnswer(2, true),
                new VoteAnswer(3, false),
            ],
            true,
        ];

        // one incorrect and one correct option selected
        yield [
            [
                new VoteAnswer(1, true),
                new VoteAnswer(2, false),
                new VoteAnswer(3, true),
            ],
            false,
        ];

        // only one incorrect option selected
        yield [
            [
                new VoteAnswer(1, false),
                new VoteAnswer(2, false),
                new VoteAnswer(3, true),
            ],
            false,
        ];
    }
}
