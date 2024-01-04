<?php

namespace App\Tests\Application\Registration;

use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class RegisterCest
{
    public function testRegister(ApplicationTester $I)
    {
        $I->amOnPage('/register');
        $I->seeResponseCodeIs(200);
        $I->see('Register', 'h1');

        $I->fillField('Email', 'test@example.com');
        $I->fillField(['name' => 'registration_form[plainPassword][first]'], 'Password123.');
        $I->fillField(['name' => 'registration_form[plainPassword][second]'], 'Password123.');
        $I->fillField('Firstname', 'John');
        $I->fillField('Lastname', 'Doe');

        $I->stopFollowingRedirects();
        $I->click('Register');

        $I->seeEmailIsSent(1);

        $email = $I->grabLastSentEmail();
        $body = $email->getHtmlBody();

        preg_match('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i', $body, $matches);
        $verificationLink = $matches[1] ?? null;
        $decodedVerificationLink = html_entity_decode($verificationLink);

        $I->amOnPage($decodedVerificationLink);
    }

    public function testFailedRegisterWrongPassword(ApplicationTester $I)
    {
        $I->amOnPage('/register');
        $I->seeResponseCodeIs(200);
        $I->see('Register', 'h1');

        $I->fillField('Email', 'test@example.com');
        $I->fillField(['name' => 'registration_form[plainPassword][first]'], 'Password123.');
        $I->fillField(['name' => 'registration_form[plainPassword][second]'], 'Password123');
        $I->fillField('Firstname', 'John');
        $I->fillField('Lastname', 'Doe');

        $I->click('Register');

        $I->see('Les valeurs ne correspondent pas.', 'form .mb-3 .invalid-feedback.d-block');
    }

    public function testFailedRegisterBadPassword(ApplicationTester $I)
    {
        $I->amOnPage('/register');
        $I->seeResponseCodeIs(200);
        $I->see('Register', 'h1');

        $I->fillField('Email', 'testexample.com');
        $I->fillField(['name' => 'registration_form[plainPassword][first]'], 'password123');
        $I->fillField(['name' => 'registration_form[plainPassword][second]'], 'password123');
        $I->fillField('Firstname', 'John');
        $I->fillField('Lastname', 'Doe');

        $I->click('Register');

        $I->see('Votre mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.', 'form .mb-3 .invalid-feedback.d-block');
    }

    public function testEmailNotVerified(ApplicationTester $I)
    {
        $user = UserFactory::createOne(['isVerified' => false]);

        $I->amLoggedInAs($user->object());

        $I->amOnPage('/advertisement');
        $I->seeInCurrentUrl('/validate/email');
    }
}
