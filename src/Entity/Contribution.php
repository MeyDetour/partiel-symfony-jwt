<?php

namespace App\Entity;

use App\Repository\ContributionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributionsRepository::class)]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    private ?Event $event = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, OtherTakingInCharge>
     */
    #[ORM\OneToMany(targetEntity: OtherTakingInCharge::class, mappedBy: 'contirbution', orphanRemoval: true)]
    private Collection $otherTakingInCharges;

    /**
     * @var Collection<int, Suggestion>
     */
    #[ORM\OneToMany(targetEntity: Suggestion::class, mappedBy: 'contribution', orphanRemoval: true)]
    private Collection $suggestions;

    public function __construct()
    {
        $this->otherTakingInCharges = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, OtherTakingInCharge>
     */
    public function getOtherTakingInCharges(): Collection
    {
        return $this->otherTakingInCharges;
    }

    public function addOtherTakingInCharge(OtherTakingInCharge $otherTakingInCharge): static
    {
        if (!$this->otherTakingInCharges->contains($otherTakingInCharge)) {
            $this->otherTakingInCharges->add($otherTakingInCharge);
            $otherTakingInCharge->setContirbution($this);
        }

        return $this;
    }

    public function removeOtherTakingInCharge(OtherTakingInCharge $otherTakingInCharge): static
    {
        if ($this->otherTakingInCharges->removeElement($otherTakingInCharge)) {
            // set the owning side to null (unless already changed)
            if ($otherTakingInCharge->getContirbution() === $this) {
                $otherTakingInCharge->setContirbution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suggestion>
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setContribution($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getContribution() === $this) {
                $suggestion->setContribution(null);
            }
        }

        return $this;
    }
}
