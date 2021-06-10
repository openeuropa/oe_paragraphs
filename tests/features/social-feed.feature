@api
Feature: Social feed paragraph.
  As a content editor
  I need to be able to use social feed paragraphs
  so that I can reference Webtools social feed media entities.

  @cleanup:media
  Scenario: Social feed paragraph creation.
    Given I am logged in as a user with the "Editor" role
    And I visit "the Webtools social feed creation page"
    And I fill in "Name" with "My social feed"
    And I fill in "Webtools social feed snippet" with "{\"service\":\"smk\",\"type\":\"list\",\"slug\":\"ec-spokespersons\"}"
    And I press "Save"
    Then I should see the success message "Webtools social feed My social feed has been created."

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Social feed paragraph test"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Social feed"
    Then the following fields should be present "Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                        |
      | Use existing media field is required. |
    When I fill in "Use existing media" with "My social feed"
    And I press "Save"
    Then I should see the heading "Social feed paragraph test"
    And I should see the Webtools social feed "My social feed" on the page
