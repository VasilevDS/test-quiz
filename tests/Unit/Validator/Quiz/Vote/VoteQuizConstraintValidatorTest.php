<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Quiz\Vote;

use App\DTO\Quiz\Request\VoteAnswer;
use App\DTO\Quiz\Request\VoteQuestion;
use App\DTO\Quiz\Request\VoteQuizRequest;
use App\Entity\Quiz\Answer;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Quiz;
use App\Repository\Quiz\QuizRepository;
use App\Validator\Quiz\Vote\VoteQuizConstraint;
use App\Validator\Quiz\Vote\VoteQuizConstraintValidator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class VoteQuizConstraintValidatorTest extends TestCase
{
    private QuizRepository&MockObject $quizRepository;
    private VoteQuizConstraintValidator $validator;

    protected function setUp(): void
    {
        $this->quizRepository = $this->getMockBuilder(QuizRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findByIdWithQuestionsAndAnswer'])
            ->getMock();

        $this->executionContextMock = $this->getMockBuilder(ExecutionContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->constraintViolationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->constraintViolationListInterfaceMock = $this->getMockBuilder(ConstraintViolationListInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validator = new VoteQuizConstraintValidator($this->quizRepository);
    }

    /**
     * @throws Exception
     */
    public function testValidateValidQuizSuccessful(): void
    {
        $quiz = $this->getMockQuizEntity();

        $quizId = 1;

        $this->quizRepository->expects($this->once())
            ->method('findByIdWithQuestionsAndAnswer')
            ->with($quizId)
            ->willReturn($quiz);

        $requestQuestions = [
            new VoteQuestion(
                1,
                [
                    new VoteAnswer(1, false),
                    new VoteAnswer(2, false),
                    new VoteAnswer(3, true),
                ],
            ),
            new VoteQuestion(
                2,
                [
                    new VoteAnswer(4, false),
                    new VoteAnswer(5, true),
                ],
            ),
        ];

        $voteQuizRequest = new VoteQuizRequest($quizId, $requestQuestions);

        $this->executionContextMock
            ->expects($this->never())
            ->method('buildViolation');

        $this->constraintViolationBuilderMock
            ->expects($this->never())
            ->method('atPath');

        $this->constraintViolationBuilderMock
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->initialize($this->executionContextMock);

        $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
    }

    /**
     * @throws Exception
     */
    public function testNumberOfQuestionsError(): void
    {
        $quiz = $this->getMockQuizEntity();

        $quizId = 1;

        $this->quizRepository->expects($this->once())
            ->method('findByIdWithQuestionsAndAnswer')
            ->with($quizId)
            ->willReturn($quiz);

        $requestQuestions = [
            new VoteQuestion(
                1,
                [
                    new VoteAnswer(1, false),
                    new VoteAnswer(2, true),
                ],
            ),
        ];

        $voteQuizRequest = new VoteQuizRequest($quizId, $requestQuestions);

        $this->executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with('Not all questions have been submitted')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('atPath')
            ->with('questions')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->initialize($this->executionContextMock);

        $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
    }

    /**
     * @throws Exception
     */
    public function testQuestionIdError(): void
    {
        $quiz = $this->getMockQuizEntity();

        $quizId = 1;

        $this->quizRepository->expects($this->once())
            ->method('findByIdWithQuestionsAndAnswer')
            ->with($quizId)
            ->willReturn($quiz);

        $requestQuestions = [
            new VoteQuestion(
                10, // wrong ID
                [
                    new VoteAnswer(1, false),
                    new VoteAnswer(2, false),
                    new VoteAnswer(3, true),
                ],
            ),
            new VoteQuestion(
                2,
                [
                    new VoteAnswer(4, false),
                    new VoteAnswer(5, true),
                ],
            ),
        ];

        $voteQuizRequest = new VoteQuizRequest($quizId, $requestQuestions);

        $this->executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with('Invalid question ID passed')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('atPath')
            ->with('questions[0].id')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->initialize($this->executionContextMock);

        $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
    }

    /**
     * @throws Exception
     */
    public function testAnswerIdError(): void
    {
        $quiz = $this->getMockQuizEntity();

        $quizId = 1;

        $this->quizRepository->expects($this->once())
            ->method('findByIdWithQuestionsAndAnswer')
            ->with($quizId)
            ->willReturn($quiz);

        $requestQuestions = [
            new VoteQuestion(
                1,
                [
                    new VoteAnswer(1, false),
                    new VoteAnswer(2, false),
                    new VoteAnswer(30, true), // wrong ID
                ],
            ),
            new VoteQuestion(
                2,
                [
                    new VoteAnswer(4, false),
                    new VoteAnswer(5, true),
                ],
            ),
        ];

        $voteQuizRequest = new VoteQuizRequest($quizId, $requestQuestions);

        $this->executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with('Invalid answer ID passed')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('atPath')
            ->with('questions[0].answers[2].id')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->initialize($this->executionContextMock);

        $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
    }

    /**
     * @throws Exception
     */
    public function testSelectedAnswerError(): void
    {
        $quiz = $this->getMockQuizEntity();

        $quizId = 1;

        $this->quizRepository->expects($this->once())
            ->method('findByIdWithQuestionsAndAnswer')
            ->with($quizId)
            ->willReturn($quiz);

        $requestQuestions = [
            new VoteQuestion(
                1,
                [
                    new VoteAnswer(1, false),
                    new VoteAnswer(2, false),
                    new VoteAnswer(3, true),
                ],
            ),
            new VoteQuestion(
                2,
                [
                    // none of the options are selected
                    new VoteAnswer(4, false),
                    new VoteAnswer(5, false),
                ],
            ),
        ];

        $voteQuizRequest = new VoteQuizRequest($quizId, $requestQuestions);

        $this->executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with('No answer selected')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('atPath')
            ->with('questions[1]')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->initialize($this->executionContextMock);

        $this->validator->validate($voteQuizRequest, new VoteQuizConstraint());
    }

    /**
     * @throws Exception
     */
    private function getMockQuizEntity(): Quiz&MockObject
    {
        $questionFirst = $this->createPartialMock(Question::class, ['getId']);
        $questionFirst
            ->method('getId')
            ->willReturn(1);
        $questionFirst->setText('test-question-1');

        $questionSecond = $this->createPartialMock(Question::class, ['getId']);
        $questionSecond
            ->method('getId')
            ->willReturn(2);
        $questionSecond->setText('test-question-2');

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

        $questionFirst->setAnswers(new ArrayCollection([
            1 => $answerFirst,
            2 => $answerSecond,
            3 => $answerThird,
        ]));


        $questionSecondAnswerFirst = $this->createPartialMock(Answer::class, ['getId']);
        $questionSecondAnswerFirst
            ->method('getId')
            ->willReturn(4);
        $questionSecondAnswerFirst
            ->setText('test-answer-4')
            ->setCorrect(true);

        $questionSecondAnswerSecond = $this->createPartialMock(Answer::class, ['getId']);
        $questionSecondAnswerSecond
            ->method('getId')
            ->willReturn(5);
        $questionSecondAnswerSecond
            ->setText('test-answer-5')
            ->setCorrect(true);

        $questionSecond->setAnswers(new ArrayCollection([
            4 => $questionSecondAnswerFirst,
            5 => $questionSecondAnswerSecond,
        ]));

        $quiz = $this->createPartialMock(Quiz::class, ['getId']);
        $quiz
            ->method('getId')
            ->willReturn(1);

        $quiz
            ->setName('test-quiz')
            ->setAnswers(new ArrayCollection([
                1 => $questionFirst,
                2 => $questionSecond,
            ]));

        return $quiz;
    }
}
