<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use PHPUnit\Framework\TestCase;
use Test\UseCases\Support\UseCaseTestServiceContainer;

final class RegistrationTest extends TestCase
{
    private UseCaseTestServiceContainer $container;

    protected function setUp(): void
    {
        $this->container = UseCaseTestServiceContainer::create();

        // Given today is "01-01-2020"
        $this->container->setCurrentDate('01-01-2020');
    }

    /**
     * @test
     */
    public function aScheduledTrainingShowsUpInUpcomingTrainings(): void
    {
        // When the organizer schedules a new training called "Decoupling from infrastructure" for "24-01-2020"
        $this->markTestIncomplete('TODO Assignment 1');

        // Then it shows up on the list of upcoming trainings
        $this->markTestIncomplete('TODO Assignment 2');
    }
}
