<?php

namespace App\DataFixtures;

use App\Factory\AdvertisementLikeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdvertisementsLikesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $advertisements = $manager->getRepository('App\Entity\Advertisement')->findAll();
        $users = $manager->getRepository('App\Entity\User')->findAll();

        foreach ($users as $user) {
            $numberOfLikes = mt_rand(50, 100);

            // Get the keys (IDs) of advertisements
            $advertisementIds = array_keys($advertisements);

            $userAdvertisementIds = $user->getAdvertisements()->map(fn ($adv) => $adv->getId())->toArray();
            $advertisementIds = array_diff($advertisementIds, $userAdvertisementIds);

            // Randomly select $numberOfLikes advertisement IDs to be liked by the current user
            $likedAdvertisementIds = array_rand($advertisementIds, $numberOfLikes);

            foreach ($likedAdvertisementIds as $advertisementId) {
                $advertisement = $advertisements[$advertisementId];

                AdvertisementLikeFactory::createOne([
                    'people' => $user,
                    'advertisement' => $advertisement,
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [
            UserFixtures::class,
            AdvertisementFixtures::class,
        ];
    }
}
