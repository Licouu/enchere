<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email address')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class : "doctrine.uuid_generator")]
    private Uuid $id;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups("user")]
    private string $username;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    private string $FirstName;

    #[ORM\Column(length: 255)]
    private string $LastName;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'beatmaker', targetEntity: Auction::class, orphanRemoval: true)]
    private Collection $auctions;

    #[ORM\OneToMany(mappedBy: 'winner', targetEntity: Auction::class)]
    private Collection $auctionsWin;

    #[ORM\ManyToMany(targetEntity: Auction::class, mappedBy: 'bidder')]
    private Collection $auctionsParticipated;

    #[ORM\OneToMany(mappedBy: 'bidder', targetEntity: Offer::class, orphanRemoval: true)]
    private Collection $offers;

    #[ORM\OneToMany(mappedBy: 'toUser', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    public function __construct()
    {
        $this->auctions = new ArrayCollection();
        $this->auctionsWin = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function isNotifyEmail(): bool
    {
        return $this->notifyEmail;
    }

    public function setNotifyEmail(bool $notifyEmail): self
    {
        $this->notifyEmail = $notifyEmail;

        return $this;
    }


    public function getFirstName(): string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    /**
     * @return Collection<int, Auction>
     */
    public function getAuctions(): Collection
    {
        return $this->auctions;
    }

    public function addAuction(Auction $auction): self
    {
        if (!$this->auctions->contains($auction)) {
            $this->auctions->add($auction);
            $auction->setBeatmaker($this);
        }

        return $this;
    }

    public function removeAuction(Auction $auction): self
    {
        if ($this->auctions->removeElement($auction)) {
            // set the owning side to null (unless already changed)
            if ($auction->getBeatmaker() === $this) {
                $auction->setBeatmaker(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Auction>
     */
    public function getAuctionsWin(): Collection
    {
        return $this->auctionsWin;
    }

    public function addAuctionsWin(Auction $auctionsWin): self
    {
        if (!$this->auctionsWin->contains($auctionsWin)) {
            $this->auctionsWin->add($auctionsWin);
            $auctionsWin->setWinner($this);
        }

        return $this;
    }

    public function removeAuctionsWin(Auction $auctionsWin): self
    {
        if ($this->auctionsWin->removeElement($auctionsWin)) {
            // set the owning side to null (unless already changed)
            if ($auctionsWin->getWinner() === $this) {
                $auctionsWin->setWinner(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Auction>
     */
    public function getAuctionsParticipated(): Collection
    {
        return $this->auctionsParticipated;
    }

    public function addAuctionsParticipated(Auction $auctionsParticipated): self
    {
        if (!$this->auctionsParticipated->contains($auctionsParticipated)) {
            $this->auctionsParticipated->add($auctionsParticipated);
            $auctionsParticipated->addBidder($this);
        }

        return $this;
    }

    public function removeAuctionsParticipated(Auction $auctionsParticipated): self
    {
        if ($this->auctionsParticipated->removeElement($auctionsParticipated)) {
            $auctionsParticipated->removeBidder($this);
        }

        return $this;
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
            $offer->setBidder($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getBidder() === $this) {
                $offer->setBidder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setToUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getToUser() === $this) {
                $notification->setToUser(null);
            }
        }

        return $this;
    }
}
