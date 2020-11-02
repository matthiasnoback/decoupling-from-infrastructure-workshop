Feature:

  Scenario: I am not logged in
    Given I am on "/"
    Then I should see "Hello, world!"

  Scenario: I log in after registration
    Given I am on "/registerUser"
    And I fill in the following:
      | Username | Matthias |
    When I press "Submit"
    And I am on "/login"
    And I fill in the following:
      | Username | Matthias |
    And I press "Submit"
    Then I should see "Hello, Matthias!"
