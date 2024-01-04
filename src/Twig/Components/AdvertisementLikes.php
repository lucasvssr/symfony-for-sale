<?php

namespace App\Twig\Components;

use App\Entity\Advertisement;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class AdvertisementLikes
{
    public Advertisement $advertisement;
    public ?UserInterface $currentUser;

    public function __construct(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    public function getLikesCount()
    {
        return $this->advertisement->getAdvertisementLikes()->count();
    }

    public function isLikedByUser(UserInterface $currentUser)
    {
        return $this->advertisement->getAdvertisementLikes()
            ->exists(fn ($key, $like) => $like->getPeople() === $currentUser);
    }
}
