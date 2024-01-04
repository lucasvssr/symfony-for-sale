<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\Module;
use Codeception\Module\Symfony;

class BlameablePersist extends Module implements DependsOnModule
{
    private Symfony $symfony;
    protected string $dependencyMessage = <<<EOF
Set Symfony as dependent module:

modules:
    enabled:
        - \App\Tests\Support\Helper\BlameablePersist:
            depends: Symfony
EOF;

    protected array $config = [
        'depends' => null,
    ];

    public function _depends(): array
    {
        return [Symfony::class => $this->dependencyMessage];
    }

    public function _inject(Symfony $symfony): void
    {
        $this->symfony = $symfony;
    }

    /**
     * Executed before suite.
     *
     * Persist Blameable listener as permanent service to fix issue where user is null when blameable is called
     */
    public function _beforeSuite(array $settings = []): void
    {
        parent::_beforeSuite($settings);

        $this->symfony->persistPermanentService('stof_doctrine_extensions.listener.blameable');
    }
}
