<?php

declare(strict_types=1);

namespace Tests\Support;

require_once __DIR__ . '/_generated/CliTesterActions.php';

if (trait_exists(\Tests\_generated\CliTesterActions::class, false)) {
    class_alias(\Tests\_generated\CliTesterActions::class, \Tests\Support\_generated\CliTesterActions::class);
}

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class CliTester extends \Codeception\Actor
{
    use _generated\CliTesterActions;

    /**
     * Define custom actions here
     */
}

class_alias(CliTester::class, \Tests\CliTester::class);
