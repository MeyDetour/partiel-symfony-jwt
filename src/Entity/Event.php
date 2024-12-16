<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?bool $isPublic = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getEvents','getDetailOfPrivateEvent'])]
    //author
    private ?Profile $organisator = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?bool $isPublicPlace = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?string $state = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations"])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Profile>
     */
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'eventsWichProfileParticip')]
    #[Groups(['getDetailOfPrivateEvent'])]
    #[ORM\OrderBy(["displayName"=>"ASC"])]
    private Collection $participants;

    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'event')]
    #[Groups(['getDetailOfPrivateEvent'])]
    #[ORM\OrderBy(["createdAt"=>"ASC"])]
    private Collection $invitations;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOrganisator(): ?Profile
    {
        return $this->organisator;
    }

    public function setOrganisator(?Profile $organisator): static
    {
        $this->organisator = $organisator;

        return $this;
    }

    public function isPublicPlace(): ?bool
    {
        return $this->isPublicPlace;
    }

    public function setPublicPlace(bool $isPublicPlace): static
    {
        $this->isPublicPlace = $isPublicPlace;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Profile $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Profile $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setEvent($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getEvent() === $this) {
                $invitation->setEvent(null);
            }
        }

        return $this;
    }
}
