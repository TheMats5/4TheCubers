<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solve", mappedBy="user")
     */
    private $solves;

    public function __construct()
    {
        parent::__construct();
        $this->times = new ArrayCollection();
        $this->time = new ArrayCollection();
        $this->solves = new ArrayCollection();
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Solve[]
     */
    public function getSolves(): Collection
    {
        return $this->solves;
    }

    public function addsolve(Solve $solve): self
    {
        if (!$this->solves->contains($solve)) {
            $this->solves[] = $solve;
            $solve->setUser($this);
        }

        return $this;
    }

    public function removesolve(Solve $solve): self
    {
        if ($this->solves->contains($solve)) {
            $this->solves->removeElement($solve);
            // set the owning side to null (unless already changed)
            if ($solve->getUser() === $this) {
                $solve->setUser(null);
            }
        }

        return $this;
    }
}
