<?php

namespace App\Tests\Application\Security;

use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class AuthenticationCest
{
    public function loginTest(ApplicationTester $I): void
    {
        UserFactory::createOne([
            'email' => 'test@example.com',
        ]);

        // Login
        $I->amOnPage('/login');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('email', 'test@example.com');
        $I->fillField('password', 'test');
        $I->click('Sign in');

        // Check if logged in
        $I->dontSee('Identifiants invalides.', 'form .alert.alert-danger');

        // Check if redirected to advertisement page
        $I->seeInCurrentUrl('/advertisement');
    }

    public function logoutTest(ApplicationTester $I): void
    {
        UserFactory::createOne([
            'email' => 'test@example.com',
        ]);

        // Login
        $I->amOnPage('/login');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('email', 'test@example.com');
        $I->fillField('password', 'test');
        $I->click('Sign in');
        $I->dontSee('Identifiants invalides.', 'form .alert.alert-danger');
        $I->seeInCurrentUrl('/advertisement');

        // Logout
        $I->click('Déconnexion');
        $I->seeResponseCodeIsSuccessful();

        // Check if logged out
        $I->amOnPage('/login');
        $I->dontSee('You are logged in as test@example.com, Logout', 'form div.mb-3');
    }

    public function failedLoginTest(ApplicationTester $I): void
    {
        UserFactory::createOne();

        // Login
        $I->amOnPage('/login');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('email', 'test@example.com');
        $I->fillField('password', 'wrong-password');
        $I->click('Sign in');

        // Check if failed login
        $I->see('Identifiants invalides.', 'form .alert.alert-danger');
    }
}
