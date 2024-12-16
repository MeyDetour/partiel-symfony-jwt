<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invitations','getDetailOfPrivateEvent'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    #[Groups(['invitations'])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getDetailOfPrivateEvent'])]
    private ?Profile $guest = null;

    #[ORM\Column]
    #[Groups(['invitations','getDetailOfPrivateEvent'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    //status : waiting, accepted,refused
    #[Groups(['invitations','getDetailOfPrivateEvent'])]
    private ?string $status = null;

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

    public function getGuest(): ?Profile
    {
        return $this->guest;
    }

    public function setGuest(?Profile $guest): static
    {
        $this->guest = $guest;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
