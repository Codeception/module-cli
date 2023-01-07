<?php


namespace Tests\Cli;

use Tests\Support\CliTester;

class ShellVerbosityIsNotInheritedCest
{
    /**
     * \Symfony\Component\Console\Application::configureIO sets SHELL_VERBOSITY environment variable
     * which may affect execution of shell command
     */
    public function shellVerbosityIsNotInheritedFromCodeception(CliTester $I)
    {
        $I->runShellCommand('php ' . codecept_data_dir('check_verbosity.php'));
        $I->seeInShellOutput('bool(false)');
    }
}
