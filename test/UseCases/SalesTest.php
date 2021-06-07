<?php
declare(strict_types=1);

namespace Test\UseCases;

final class SalesTest extends AbstractUseCaseTestCase
{
    /**
     * @test
     */
    public function attendees_can_register_themselves_for_sessions(): void
    {
        $this->markTestIncomplete();

        //Given the organizer has scheduled a training
        //When a user buys a ticket for this training
        //Then they should be registered as an attendee
    }

    /**
     * @test
     */
    public function the_maximum_number_of_attendees_was_reached(): void
    {
        $this->markTestIncomplete();
        //Given the organizer has scheduled a training with a maximum of 5 attendees
        //And so far 4 attendees have registered themselves for this training
        //When a user buys a ticket for this training
        //Then the training still shows up on the list of upcoming trainings
        //But it will be marked as Sold out
        //And it's impossible to buy another ticket for this training
    }

    /**
     * @test
     */
    public function the_end_of_sales_date_has_been_reached(): void
    {
        $this->markTestIncomplete();

        //Given a training has been scheduled for which sales ends on "2020-01-15"
        //When it's "2020-01-15"
        //Then it's impossible to buy a ticket for this training
    }

    /**
     * @test
     */
    public function it_is_the_day_of_the_training(): void
    {
        $this->markTestIncomplete();

        //Given a training has been scheduled for "2020-01-24"
        //When it's "2020-01-24"
        //Then it does not show up on the list of upcoming trainings anymore
    }

    /**
     * @test
     */
    public function a_refund_is_requested(): void
    {
        $this->markTestIncomplete();

        //Given the organizer has scheduled a training with a maximum of 5 attendees
        //And so far 5 attendees have registered themselves for this training
        //When one attendee requests a refund for their ticket
        //Then another user can buy a ticket for this training
    }
}
