<?php

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;

class AvailabilityCest
{
    public function _before(ApplicationTester $I): void
    {
        UserFactory::createOne();
        AdvertisementFactory::createOne();
    }

    protected function provideUrls(): array
    {
        return [
            ['url' => '/advertisement/create'],
            ['url' => '/advertisement/1'],
            ['url' => '/advertisement'],
            ['url' => '/category'],
            ['url' => '/login'],
            ['url' => '/logout'],
            ['url' => '/user/1/advertisements'],
            ['url' => '/register'],
            ['url' => '/validate/email'],
            ['url' => '/verify/email'],
        ];
    }

    #[DataProvider('provideUrls')]
    #[Group('Availability')]
    public function pageIsAvailable(ApplicationTester $I, Example $data): void
    {
        $I->amOnPage($data['url']);
        $I->seeResponseCodeIsSuccessful();
    }
}
