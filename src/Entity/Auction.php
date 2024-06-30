<?php

namespace App\Entity;

use App\Repository\AuctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: AuctionRepository::class)]
class Auction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class : "doctrine.uuid_generator")]
    #[Groups("auction")]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'auctions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("user")]
    private User $beatmaker;

    #[ORM\ManyToOne(inversedBy: 'auctionsWin')]
    private ?User $winner = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'auctionsParticipated')]
    private Collection $bidder;

    #[ORM\Column]
    private \DateTimeImmutable $endDate;

    #[ORM\Column]
    private int $minPrice = 0;

    #[ORM\Column]
    private int $maxPrice = 0;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("beat")]
    private Beat $Beat;

    #[ORM\OneToMany(mappedBy: 'auction', targetEntity: Offer::class, orphanRemoval: true)]
    private Collection $offers;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $signatories;

    public function __construct()
    {
        $this->bidder = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->signatories = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBeatmaker(): User
    {
        return $this->beatmaker;
    }

    public function setBeatmaker(User $beatmaker): self
    {
        $this->beatmaker = $beatmaker;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getBidder(): Collection
    {
        return $this->bidder;
    }

    public function addBidder(User $bidder): self
    {
        if (!$this->bidder->contains($bidder)) {
            $this->bidder->add($bidder);
        }

        return $this;
    }

    public function removeBidder(User $bidder): self
    {
        $this->bidder->removeElement($bidder);

        return $this;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMinPrice(): int
    {
        return $this->minPrice;
    }

    public function setMinPrice(int $minPrice): self
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBeat(): Beat
    {
        return $this->Beat;
    }

    public function setBeat(Beat $Beat): self
    {
        $this->Beat = $Beat;

        return $this;
    }

    public function getNbDaysLeft(): \DateInterval
    {
        $diff = new \DateTime();
        return $diff->diff($this->endDate);
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setAuction($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getAuction() === $this) {
                $offer->setAuction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSignatories(): Collection
    {
        return $this->signatories;
    }

    public function addSignatory(User $signatory): self
    {
        if (!$this->signatories->contains($signatory)) {
            $this->signatories->add($signatory);
            $signatory->setAuction($this);
        }

        return $this;
    }

    public function removeSignatory(User $signatory): self
    {
        if ($this->signatories->removeElement($signatory)) {
            // set the owning side to null (unless already changed)
            if ($signatory->getAuction() === $this) {
                $signatory->setAuction(null);
            }
        }

        return $this;
    }
}
