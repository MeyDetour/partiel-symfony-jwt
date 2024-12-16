<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[UniqueEntity(fields: ['displayName'], message: 'There an account with this display name')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getEvents','users','profile'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['getEvents','users','profile'])]
    private ?string $displayName = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['profile'])]
    private ?User $userAssociated = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'profile', orphanRemoval: true)]
    private Collection $events;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'participants')]
    private Collection $eventsWichProfileParticip;

    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'guest', orphanRemoval: true)]
    private Collection $invitations;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->eventsWichProfileParticip = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getUserAssociated(): ?User
    {
        return $this->userAssociated;
    }

    public function setUserAssociated(User $userAssociated): static
    {
        $this->userAssociated = $userAssociated;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setProfile($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getProfile() === $this) {
                $event->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsWichProfileParticip(): Collection
    {
        return $this->eventsWichProfileParticip;
    }

    public function isEventInEventsOfUser(Event $eventSearched): bool
    {
        foreach ($this->eventsWichProfileParticip as $event) {
            if ($eventSearched == $event) {
                return true;
            }
        }

        return false;
    }

    public function addEventsWichProfileParticip(Event $eventsWichProfileParticip): static
    {
        if (!$this->eventsWichProfileParticip->contains($eventsWichProfileParticip)) {
            $this->eventsWichProfileParticip->add($eventsWichProfileParticip);
            $eventsWichProfileParticip->addParticipant($this);
        }

        return $this;
    }

    public function removeEventsWichProfileParticip(Event $eventsWichProfileParticip): static
    {
        if ($this->eventsWichProfileParticip->removeElement($eventsWichProfileParticip)) {
            $eventsWichProfileParticip->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }
    public function isEventInInvited(Event $eventSearched): bool
    {
        foreach ($this->invitations as $invit) {
            if ($eventSearched == $invit->getEvent()) {
                return true;
            }
        }

        return false;
    }
    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setGuest($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getGuest() === $this) {
                $invitation->setGuest(null);
            }
        }

        return $this;
    }
}
