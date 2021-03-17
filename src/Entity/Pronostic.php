<?php

namespace App\Entity;

use App\Repository\PronosticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PronosticRepository::class)
 */
class Pronostic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $teamA;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $teamB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pronoType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamA(): ?string
    {
        return $this->teamA;
    }

    public function setTeamA(string $teamA): self
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?string
    {
        return $this->teamB;
    }

    public function setTeamB(string $teamB): self
    {
        $this->teamB = $teamB;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getPronoType(): ?bool
    {
        return $this->pronoType;
    }

    public function setPronoType(bool $pronoType): self
    {
        $this->pronoType = $pronoType;

        return $this;
    }
}
