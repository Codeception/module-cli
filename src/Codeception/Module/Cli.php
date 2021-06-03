<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\TestInterface;

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
    /**
     * @var string
     */
    public $output = '';

    public $result;

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
     *
     * @param $command
     */
    public function runShellCommand($command, bool $failNonZero = true): void
    {
        $data = [];
        exec("$command", $data, $resultCode);
        $this->result = $resultCode;
        $this->output = implode("\n", $data);
        if ($this->output === null) {
            \PHPUnit\Framework\Assert::fail("$command can't be executed");
        }
        if ($resultCode !== 0 && $failNonZero) {
            \PHPUnit\Framework\Assert::fail("Result code was $resultCode.\n\n" . $this->output);
        }
        $this->debug(preg_replace('#s/\e\[\d+(?>(;\d+)*)m//g#', '', $this->output));
    }

    /**
     * Checks that output from last executed command contains text
     *
     * @param $text
     */
    public function seeInShellOutput($text): void
    {
        \Codeception\PHPUnit\TestCase::assertStringContainsString($text, $this->output);
    }

    /**
     * Checks that output from latest command doesn't contain text
     *
     * @param $text
     */
    public function dontSeeInShellOutput($text): void
    {
        $this->debug($this->output);
        \Codeception\PHPUnit\TestCase::assertStringNotContainsString($text, $this->output);
    }

    public function seeShellOutputMatches($regex): void
    {
        \Codeception\PHPUnit\TestCase::assertRegExp($regex, $this->output);
    }

    /**
     * Returns the output from latest command
     */
    public function getOutput(): string
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
     *
     * @param $code
     */
    public function seeResultCodeIs($code): void
    {
        $this->assertEquals($this->result, $code, sprintf('result code is %s', $code));
    }

    /**
     * Checks result code
     *
     * ```php
     * <?php
     * $I->seeResultCodeIsNot(0);
     * ```
     *
     * @param $code
     */
    public function seeResultCodeIsNot($code): void
    {
        $this->assertNotEquals($this->result, $code, sprintf('result code is %s', $code));
    }
}
