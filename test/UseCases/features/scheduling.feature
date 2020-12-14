Feature:

  @ignore
  Scenario: the organizer tries to schedule a training on a national holiday
    Given "2020-12-25" is a national holiday in "NL"
    When the organizer tries to schedule a training on this date in this country
    Then they see a message "The date of the training is a national holiday"

  @ignore
  Scenario: the organizer schedules a training on a normal day
    Given "2020-12-23" is not a national holiday in "NL"
    When the organizer tries to schedule a training on this date in this country
    Then this training will be scheduled
