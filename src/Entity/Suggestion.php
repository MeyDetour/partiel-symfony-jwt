<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contribution $contribution = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isTaken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContribution(): ?Contribution
    {
        return $this->contribution;
    }

    public function setContribution(?Contribution $contribution): static
    {
        $this->contribution = $contribution;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isTaken(): ?bool
    {
        return $this->isTaken;
    }

    public function setTaken(bool $isTaken): static
    {
        $this->isTaken = $isTaken;

        return $this;
    }
}
