<?php

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public function build(): void
    {
        $this->addToPool('user', UserFactory::createSequence([
            [
                'email' => 'admin@example.com',
                'firstname' => 'admin',
                'lastname' => 'lafarge',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'email' => 'admin2@example.com',
                'firstname' => 'admin2',
                'lastname' => 'pokemon',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'email' => 'user@example.com',
                'firstname' => 'user',
                'lastname' => 'cyprien',
            ],
            [
                'email' => 'user2@example.com',
                'firstname' => 'user2',
                'lastname' => 'squeezie',
            ],
            ]));
        $this->addToPool('user', UserFactory::createMany(10));

        $this->addToPool('unverified_user', UserFactory::createMany(4, [
            'isVerified' => false,
        ])
        );
    }
}
