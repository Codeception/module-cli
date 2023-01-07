<?php


namespace Tests\Cli;

use Tests\Support\CliTester;

class ShellVerbosityIsNotInheritedCest
{
    public function shellVerbosityIsNotInheritedFromCodeception(CliTester $I)
    {
        $I->runShellCommand('php ' . codecept_data_dir('check_verbosity.php'));
        $I->seeInShellOutput('bool(false)');
    }
}
