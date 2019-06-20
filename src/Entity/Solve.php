<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SolveRepository")
 */
class Solve
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="solves")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scramble;

    /**
     * @ORM\Column(type="boolean")
     */
    private $plus2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getScramble(): ?string
    {
        return $this->scramble;
    }

    public function setScramble(string $scramble): self
    {
        $this->scramble = $scramble;

        return $this;
    }

    public function getPlus2(): ?bool
    {
        return $this->plus2;
    }

    public function setPlus2(?bool $plus2): self
    {
        $this->plus2 = $plus2;

        return $this;
    }

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

}
