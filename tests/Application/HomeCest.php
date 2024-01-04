<?php

namespace App\Tests\Application;

use App\Tests\Support\ApplicationTester;

class HomeCest
{
    public function homePageIsRedirecting(ApplicationTester $I): void
    {
        $I->seePageRedirectsTo('/', '/advertisement');
        $I->seeResponseCodeIsSuccessful();
    }
}
