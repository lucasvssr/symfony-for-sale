<?php

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class SearchCest
{
    public function searchWork(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne([
            'name' => 'My category',
        ]);
        $user = UserFactory::createOne([
            'email' => 'text@example.com',
        ]);

        AdvertisementFactory::createOne([
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
        ]);

        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('search', 'My title');
        $I->click('searching');
        $I->seeResponseCodeIsSuccessful();
        $I->see('My title of the advertisement', 'h5');
    }
}
