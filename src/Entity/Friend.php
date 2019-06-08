<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendsRepository")
 */
class Friend
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $sender_id;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $receiver_id;

    /**
     * @ORM\Column(type="string")
     */
    private $type;


    public function getSenderId(): ?int
    {
        return $this->sender_id;
    }

    public function setSenderId(int $sender_id): self
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiver_id;
    }

    public function setReceiverId(int $receiver_id): self
    {
        $this->receiver_id = $receiver_id;


        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }
}
