<?php

namespace App\DataFixtures;

use App\Story\UserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        Factory::delayFlush(function () {
            UserStory::getPool('user');
            UserStory::getPool('unverified_user');
        });
    }
}
