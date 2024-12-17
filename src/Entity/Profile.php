<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use App\Service\ImageService;
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
    #[Groups(['getEvents','users','profile','getDetailOfPrivateEvent'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['getEvents','users','profile','getDetailOfPrivateEvent'])]
    private ?string $displayName = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['profile'])]
    private ?User $userAssociated = null;


    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'participants')]
    #[ORM\OrderBy(["startDate"=>"ASC"])]
    private Collection $eventsWichProfileParticip;



    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'organisator', orphanRemoval: true)]
    private Collection $organizedEvents;



    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'guest', orphanRemoval: true)]
    #[ORM\OrderBy(["createdAt"=>"ASC"])]
    private Collection $invitations;

    /**
     * @var Collection<int, Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $contributions;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?Image $image = null;


    #[Groups(['users','profile','getDetailOfPrivateEvent'])]
    private ?string $imageUrl = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'administrators')]
    private Collection $administratorInEvents;



    public function __construct()
    {
        $this->eventsWichProfileParticip = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->administratorInEvents = new ArrayCollection();

        $this->organizedEvents = new ArrayCollection();
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
            $contribution->setAuthor($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getAuthor() === $this) {
                $contribution->setAuthor(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }


    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }


    public function setImageUrl(string $url): static
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getAdministratorInEvents(): Collection
    {
        return $this->administratorInEvents;
    }

    public function addAdministratorInEvent(Event $administratorInEvent): static
    {
        if (!$this->administratorInEvents->contains($administratorInEvent)) {
            $this->administratorInEvents->add($administratorInEvent);
            $administratorInEvent->addAdministrator($this);
        }

        return $this;
    }

    public function removeAdministratorInEvent(Event $administratorInEvent): static
    {
        if ($this->administratorInEvents->removeElement($administratorInEvent)) {
            $administratorInEvent->removeAdministrator($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->organizedEvents;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->organizedEvents->contains($event)) {
            $this->organizedEvents->add($event);
            $event->setOrganisator($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->organizedEvents->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getOrganisator() === $this) {
                $event->setOrganisator(null);
            }
        }

        return $this;
    }




}
