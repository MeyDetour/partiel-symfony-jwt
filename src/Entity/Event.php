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
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations","contributionsProfile"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations","contributionsProfile"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations","contributionsProfile"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations","contributionsProfile"])]
    private ?bool $isPublic = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations",'contributionsProfile'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations",'contributionsProfile'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getEvents','getDetailOfPrivateEvent','contributionsProfile'])]
    //author
    private ?Profile $organisator = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations",'contributionsProfile'])]
    private ?bool $isPublicPlace = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations",'contributionsProfile'])]
    private ?string $state = null;

    #[ORM\Column]
    #[Groups(['getEvents','getDetailOfPrivateEvent',"invitations",'contributionsProfile'])]
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

    /**
     * @var Collection<int, Suggestion>
     */
    #[ORM\OneToMany(targetEntity: Suggestion::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(['getDetailOfPrivateEvent'])]
    private Collection $suggestions;

    /**
     * @var Collection<int, Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'event')]
    #[Groups(['getDetailOfPrivateEvent'])]
    private Collection $contributions;

    /**
     * @var Collection<int, Profile>
     */
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'administratorInEvents')]
    #[Groups(['getDetailOfPrivateEvent'])]
    private Collection $administrators;




    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->administrators = new ArrayCollection();
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
    public function isProfileInParticipants(Profile $profile): bool
    {
        foreach ($this->participants as $participant){

            if ($participant ==  $profile) {
                return true;
            }
        }
        return false;
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
            $suggestion->setEvent($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getEvent() === $this) {
                $suggestion->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Contribution $contribution): static
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setEvent($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getEvent() === $this) {
                $contribution->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getAdministrators(): Collection
    {
        return $this->administrators;
    }

    public function addAdministrator(Profile $administrator): static
    {
        if (!$this->administrators->contains($administrator)) {
            $this->administrators->add($administrator);
        }

        return $this;
    }
    public function isProfileInAdministrators(Profile $profile): bool
    {
        foreach ($this->administrators as $admin){

            if ($admin ==  $profile) {
                return true;
            }
        }
        return false;
    }
    public function removeAdministrator(Profile $administrator): static
    {
        $this->administrators->removeElement($administrator);

        return $this;
    }


}
