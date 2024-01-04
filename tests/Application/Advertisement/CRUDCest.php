<?php

namespace App\Tests\Application\Advertisement;

use App\Entity\Advertisement;
use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class CRUDCest
{
    public function authenticatedUserCanCreateAdvertisement(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne([
            'name' => 'My category',
        ]);
        $user = UserFactory::createOne([
            'email' => 'text@example.com',
            'firstname' => 'John',
        ]);

        // Login
        $I->amLoggedInAs($user->object());

        // Create advertisement if authenticated
        $I->amOnPage('/advertisement/create');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('Title', 'My title of the advertisement');
        $I->fillField('Description', 'My description of the advertisement');
        $I->fillField('Price', 100);
        $I->fillField('Location', 'My location');
        $I->selectOption('Category', 'My category');

        $I->click('Submit');

        $I->seeResponseCodeIsSuccessful();

        // Check if advertisement is created
        $I->seeInRepository(Advertisement::class, [
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
            'author' => $user->object(),
        ]);
    }

    public function unauthenticatedUserCannotCreateAdvertisement(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement/create');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/login');
    }

    public function editAdvertisement(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne([
            'name' => 'My category',
        ]);
        $user = UserFactory::createOne([
            'email' => 'text@example.com',
        ]);

        $I->amLoggedInAs($user->object());

        AdvertisementFactory::createOne([
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
        ]);

        $I->amOnPage('/advertisement/1/edit');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('Title', 'Another title of the advertisement');
        $I->fillField('Description', 'Another description of the advertisement');
        $I->fillField('Price', 50);
        $I->fillField('Location', 'My location');
        $I->selectOption('Category', 'My category');

        $I->click('Submit');

        $I->seeResponseCodeIsSuccessful();

        $I->seeInRepository(Advertisement::class, [
            'title' => 'Another title of the advertisement',
            'description' => 'Another description of the advertisement',
            'price' => 50,
            'location' => 'My location',
        ]);
    }

    public function userCannotEditAdvertisementOfAnotherUser(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();

        $user = UserFactory::createOne();

        $owner = UserFactory::createOne();

        AdvertisementFactory::createOne([
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
            'author' => $owner->object(),
        ]);

        $I->amLoggedInAs($user->object());

        $I->amOnPage('/advertisement/1');
        $I->dontSee('Edit');
    }

    public function deleteAdvertisement(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();

        $user = UserFactory::createOne();

        $I->amLoggedInAs($user->object());

        AdvertisementFactory::createOne([
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
        ]);

        $I->amOnPage('/advertisement/1');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Delete');
        $I->dontSeeInRepository(Advertisement::class, [
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
        ]);
    }

    public function userCannotDeleteAdvertisementOfAnotherUser(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();

        $user = UserFactory::createOne();

        $owner = UserFactory::createOne();

        AdvertisementFactory::createOne([
            'title' => 'My title of the advertisement',
            'description' => 'My description of the advertisement',
            'price' => 100,
            'location' => 'My location',
            'category' => $category->object(),
            'author' => $owner->object(),
        ]);

        $I->amLoggedInAs($user->object());

        $I->amOnPage('/advertisement/1');
        $I->dontSee('Delete');
    }
}
