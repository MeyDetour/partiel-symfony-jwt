<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getDetailOfPrivateEvent','contributionsProfile'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    #[Groups(['getDetailOfPrivateEvent','contributionsProfile'])]
    private ?string $description = null;


    #[ORM\OneToOne(mappedBy: 'suggestion', cascade: ['persist', 'remove'])]
    private ?Contribution $contribution = null;

    #[ORM\Column]
    #[Groups(['getDetailOfPrivateEvent','contributionsProfile'])]
    private ?bool $isTaken = null;

    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
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


    public function getContribution(): ?Contribution
    {
        return $this->contribution;
    }

    public function setContribution(?Contribution $contribution): static
    {
        // unset the owning side of the relation if necessary
        if ($contribution === null && $this->contribution !== null) {
            $this->contribution->setSuggestion(null);
        }

        // set the owning side of the relation if necessary
        if ($contribution !== null && $contribution->getSuggestion() !== $this) {
            $contribution->setSuggestion($this);
        }

        $this->contribution = $contribution;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}
