<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\PHPUnit\TestCase;
use Codeception\TestInterface;
use PHPUnit\Framework\Assert;

/**
 * Wrapper for basic shell commands and shell output
 *
 * ## Responsibility
 * * Maintainer: **davert**
 * * Status: **stable**
 * * Contact: codecept@davert.mail.ua
 *
 * *Please review the code of non-stable modules and provide patches if you have issues.*
 */
class Cli extends Module
{
    public string $output = '';

    public int $result;

    public function _before(TestInterface $test): void
    {
        $this->output = '';
    }

    /**
     * Executes a shell command.
     * Fails if exit code is > 0. You can disable this by passing `false` as second argument
     *
     * ```php
     * <?php
     * $I->runShellCommand('phpunit');
     *
     * // do not fail test when command fails
     * $I->runShellCommand('phpunit', false);
     * ```
     */
    public function runShellCommand(string $command, bool $failNonZero = true): void
    {
        $data = [];
        /**
         * \Symfony\Component\Console\Application::configureIO sets SHELL_VERBOSITY environment variable
         * which may affect execution of shell command
         */
        if (\function_exists('putenv')) {
            @putenv('SHELL_VERBOSITY');
        }
        exec("{$command}", $data, $resultCode);
        $this->result = $resultCode;
        $this->output = implode("\n", $data);
        if ($this->output === null) {
            Assert::fail("{$command} can't be executed");
        }

        if ($resultCode !== 0 && $failNonZero) {
            Assert::fail("Result code was {$resultCode}.\n\n" . $this->output);
        }

        $this->debug(preg_replace('#s/\e\[\d+(?>(;\d+)*)m//g#', '', $this->output));
    }

    /**
     * Checks that output from last executed command contains text
     */
    public function seeInShellOutput(string $text): void
    {
        TestCase::assertStringContainsString($text, $this->output);
    }

    /**
     * Checks that output from latest command doesn't contain text
     */
    public function dontSeeInShellOutput(string $text): void
    {
        $this->debug($this->output);
        TestCase::assertStringNotContainsString($text, $this->output);
    }

    public function seeShellOutputMatches(string $regex): void
    {
        TestCase::assertMatchesRegularExpression($regex, $this->output);
    }

    /**
     * Returns the output from latest command
     */
    public function grabShellOutput(): string
    {
        return $this->output;
    }

    /**
     * Checks result code. To verify a result code > 0, you need to pass `false` as second argument to `runShellCommand()`
     *
     * ```php
     * <?php
     * $I->seeResultCodeIs(0);
     * ```
     */
    public function seeResultCodeIs(int $code): void
    {
        $this->assertEquals($this->result, $code, "result code is {$code}");
    }

    /**
     * Checks result code
     *
     * ```php
     * <?php
     * $I->seeResultCodeIsNot(0);
     * ```
     */
    public function seeResultCodeIsNot(int $code): void
    {
        $this->assertNotEquals($this->result, $code, "result code is {$code}");
    }
}
