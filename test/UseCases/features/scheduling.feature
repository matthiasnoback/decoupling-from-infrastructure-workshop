Feature:

  @wip
  Scenario: the organizer tries to schedule a training on a national holiday
    Given "25-12-2020" is a national holiday in "NL"
    When the organizer tries to schedule a training on "25-12-2020" in "NL"
    Then they see a message "The date of the training is a national holiday"

  @wip
  Scenario: the organizer schedules a training on a normal day
    Given "23-12-2020" is not a national holiday in "NL"
    When the organizer tries to schedule a training on "23-12-2020" in "NL"
    Then this training will be scheduled
