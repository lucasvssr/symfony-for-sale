<?php

namespace App\Tests\Application\Advertisement;

use App\Factory\AdvertisementFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class ListCest
{
    public function seeEmptyList(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->see("Pas d'annonce", 'p');
    }

    public function seeListWithPagination(ApplicationTester $I): void
    {
        $user = UserFactory::createMany(3);

        AdvertisementFactory::createMany(30);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->seeNumberOfElements('.card.mb-3.bg-subtle', 15);
        $I->see('Suivant', 'a');
    }
}
