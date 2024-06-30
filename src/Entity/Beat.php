<?php

namespace App\Entity;

use App\Enum\Category;
use App\Enum\Instrument;
use App\Enum\Mood;
use App\Repository\BeatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BeatRepository::class)]
class Beat
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class : "doctrine.uuid_generator")]
    private Uuid $id;

    /**
    #[ORM\ManyToOne(inversedBy: 'beats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Author = null;
     */

    #[ORM\Column(length: 255)]
    #[Groups("beat")]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("beat")]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private string $music;

    #[ORM\Column(length: 255, enumType: "App\Enum\Category")]
    private Category $Category = Category::Pop;

    #[ORM\Column(length: 255, enumType: "App\Enum\Mood")]
    private Mood $mood = Mood::Aggressive;

    public function getId(): Uuid
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMusic(): string
    {
        return $this->music;
    }

    public function setMusic(string $music): self
    {
        $this->music = $music;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->Category;
    }

    public function setCategory(Category $Category): self
    {
        $this->Category = $Category;
        return $this;
    }

    public function getImagePath(): string
    {
        return 'assets/images/'.$this->image;
    }

    public function getMusicPath(): string
    {
        return 'assets/musics/'.$this->music;
    }

    /**
     * @return Mood
     */
    public function getMood(): Mood
    {
        return $this->mood;
    }

    /**
     * @param Mood $mood
     */
    public function setMood(Mood $mood): void
    {
        $this->mood = $mood;
    }
}
