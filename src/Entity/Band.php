<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BandRepository::class)]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $country = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $startYear = null;

    #[ORM\Column(nullable: true)]
    private ?int $endYear = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $founder = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfMembers = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $musicalType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $presentation = null;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): static
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getFounder(): ?string
    {
        return $this->founder;
    }

    public function setFounder(?string $founder): static
    {
        $this->founder = $founder;

        return $this;
    }

    public function getNumberOfMembers(): ?int
    {
        return $this->numberOfMembers;
    }

    public function setNumberOfMembers(?int $numberOfMembers): static
    {
        $this->numberOfMembers = $numberOfMembers;

        return $this;
    }

    public function getMusicalType(): ?string
    {
        return $this->musicalType;
    }

    public function setMusicalType(?string $musicalType): static
    {
        $this->musicalType = $musicalType;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }
}
