<?php

namespace App\Entity;

use App\Enum\MessageNotification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class : "doctrine.uuid_generator")]
    #[Groups("user")]
    private Uuid $id;

    #[ORM\Column(length: 255, enumType: "App\Enum\MessageNotification")]
    #[Groups("user")]
    private MessageNotification $message;

    #[ORM\Column]
    #[Groups("user")]
    private \DateTimeImmutable $date;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private User $toUser;

    #[ORM\ManyToOne]
    #[Groups("user")]
    private Auction $forAuction;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getMessage(): MessageNotification
    {
        return $this->message;
    }

    public function setMessage(MessageNotification $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getToUser(): User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser): self
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function getForAuction(): Auction
    {
        return $this->forAuction;
    }

    public function setForAuction(Auction $forAuction): self
    {
        $this->forAuction = $forAuction;

        return $this;
    }
}
