<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class : "doctrine.uuid_generator")]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private Auction $auction;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private User $bidder;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column]
    private int $price=0;

    #[ORM\Column]
    private bool $eliminated = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAuction(): Auction
    {
        return $this->auction;
    }

    public function setAuction(Auction $auction): self
    {
        $this->auction = $auction;

        return $this;
    }

    public function getBidder(): User
    {
        return $this->bidder;
    }

    public function setBidder(User $bidder): self
    {
        $this->bidder = $bidder;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isEliminated(): bool
    {
        return $this->eliminated;
    }

    public function setIsEliminated(bool $eliminated): self
    {
        $this->eliminated = $eliminated;

        return $this;
    }
}
