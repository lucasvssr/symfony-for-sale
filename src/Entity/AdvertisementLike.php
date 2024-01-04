<?php

namespace App\Entity;

use App\Repository\AdvertisementLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertisementLikeRepository::class)]
class AdvertisementLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'advertisement')]
    private ?User $people = null;

    #[ORM\ManyToOne(inversedBy: 'advertisementLikes')]
    private ?Advertisement $advertisement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeople(): ?User
    {
        return $this->people;
    }

    public function setPeople(?User $people): static
    {
        $this->people = $people;

        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): static
    {
        $this->advertisement = $advertisement;

        return $this;
    }
}
