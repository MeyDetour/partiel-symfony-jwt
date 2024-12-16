<?php

namespace App\Entity;

use App\Repository\OtherTakingInChargeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OtherTakingInChargeRepository::class)]
class OtherTakingInCharge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'otherTakingInCharges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'otherTakingInCharges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contribution $contirbution = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContirbution(): ?Contribution
    {
        return $this->contirbution;
    }

    public function setContirbution(?Contribution $contirbution): static
    {
        $this->contirbution = $contirbution;

        return $this;
    }
}
