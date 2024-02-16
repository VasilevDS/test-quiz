<?php

namespace App\Entity\Quiz;

use App\Repository\Quiz\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $name = null;

    /** @var Collection<int, Question> $questions */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quiz', indexBy: 'id')]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: QuizSnapshot::class, mappedBy: 'quiz', cascade: ['remove'])]
    private Collection $snapshots;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->snapshots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    /**
     * @param Collection<int, Question> $answers
     */
    public function setAnswers(Collection $answers): self
    {
        $this->questions = $answers;

        return $this;
    }

    public function getSnapshots(): Collection
    {
        return $this->snapshots;
    }

    public function addSnapshot(QuizSnapshot $snapshot): self
    {
        if (!$this->snapshots->contains($snapshot)) {
            $this->snapshots[] = $snapshot;
            $snapshot->setQuiz($this);
        }

        return $this;
    }
}
