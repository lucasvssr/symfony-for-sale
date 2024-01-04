<?php

namespace App\Tests\Application\Command;

use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class PurgeRegistrationCest
{
    public function _before(ApplicationTester $I): void
    {
        UserFactory::createMany(10, ['isVerified' => false, 'registeredAt' => new \DateTimeImmutable('-30 days')]);
        UserFactory::createMany(5, ['isVerified' => false, 'registeredAt' => new \DateTimeImmutable('-5 days')]);

        UserFactory::createMany(5, ['isVerified' => true]);
    }

    public function purgeUnverifiedUsersSince(ApplicationTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:purge-registration');

        $I->assertStringContainsString('Nom', $result);
        $I->assertStringContainsString('Prénom', $result);
        $I->assertStringContainsString('Email', $result);
        $I->assertStringContainsString('Non vérifié depuis', $result);
        $I->assertStringContainsString('30', $result);
        $I->assertStringContainsString('5', $result);
    }

    public function purgeUnverifiedUsersAllOptions(ApplicationTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:purge-registration', ['--days' => 7, '--delete' => true, '--force' => true]);
        $I->assertStringContainsString('10 utilisateur(s) ont été supprimé(s).', $result);
    }

    public function purgeUnverifiedUsersWithoutDays(ApplicationTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:purge-registration', ['--delete' => true, '--force' => true]);
        $I->assertStringContainsString('15 utilisateur(s) ont été supprimé(s).', $result);
    }

    public function displayUnverifiedUsersWithConfirmation(ApplicationTester $I): void
    {
        $result = $I->runSymfonyConsoleCommand('app:purge-registration', ['--delete' => true]);
        $I->assertStringContainsString('Voulez-vous vraiment supprimer les utilisateurs non vérifiés ? (y/n)', $result);
    }
}
