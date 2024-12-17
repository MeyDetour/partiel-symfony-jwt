<?php

namespace App\Entity;

use App\Repository\ContributionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getDetailOfPrivateEvent','contributionsProfile'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    #[Groups(['getDetailOfPrivateEvent','contributionsProfile'])]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'contribution', cascade: ['persist', 'remove'])]
    #[Groups(['contributionsProfile',"contributionsProfile"])]
    private ?Suggestion $suggestion = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getDetailOfPrivateEvent'])]
    private ?Profile $author = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[Groups(["contributionsProfile"])]
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

    public function getSuggestion(): ?Suggestion
    {
        return $this->suggestion;
    }
    #[Groups(['getDetailOfPrivateEvent'])]
    public function getSuggestionId(): int|null
    {
        if (!$this->suggestion){
            return null;
        }
        return $this->suggestion->getId();
    }

    public function setSuggestion(?Suggestion $suggestion): static
    {
        $this->suggestion = $suggestion;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

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
