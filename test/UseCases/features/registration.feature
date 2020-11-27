Feature: Registration

  Background:
    Given today is "01-01-2020"

  Scenario: A scheduled training shows up in Upcoming trainings
    When the organizer schedules a new training called "Decoupling from infrastructure" for "2020-01-24 09:30"
    Then it shows up on the list of upcoming trainings

  @ignore
  Scenario: Attendees can register themselves for sessions
    Given the organizer has scheduled a training
    When a user buys a ticket for this training
    Then they should be registered as an attendee

  @ignore
  Scenario: Attendees can't register themselves if the maximum number of attendees was reached
    Given the organizer has scheduled a training with a maximum of 5 attendees
    And so far 4 attendees have registered themselves for this training
    When a user buys a ticket for this training
    Then the training still shows up on the list of upcoming trainings
    But it will be marked as Sold out
    And it's impossible to buy another ticket for this training

  @ignore
  Scenario: After the end-of-sales date you can't register for it anymore
    Given a training has been scheduled for which sales ends on "15-01-2020"
    When it's "15-01-2020"
    Then it's impossible to buy a ticket for this training

  @ignore
  Scenario: On the day of the training, it will no longer be an upcoming training
    Given a training has been scheduled for "24-01-2020"
    When it's "24-01-2020"
    Then it does not show up on the list of upcoming trainings anymore

  @ignore
  Scenario: Requesting a refund for a ticket frees up a seat for someone else
    Given the organizer has scheduled a training with a maximum of 5 attendees
    And so far 5 attendees have registered themselves for this training
    When one attendee requests a refund for their ticket
    Then another user can buy a ticket for this training
