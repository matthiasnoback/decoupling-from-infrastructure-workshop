<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class FeatureContext implements Context
{
    /**
     * @Given /^I am a pending step$/
     */
    public function iAmAPendingStep(): void
    {
        throw new PendingException();
    }
}
