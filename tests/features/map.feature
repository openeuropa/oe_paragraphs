@api
Feature: Map paragraph.
  As a content editor
  I need to be able to use map paragraphs
  so that I can reference Webtools map media entities.

  @cleanup:media
  Scenario: Map paragraph creation.
    Given I am logged in as a user with the "Editor" role
    And I visit "the Webtools map creation page"
    And I fill in "Name" with "World map"
    And I fill in "Webtools map snippet" with "{\"service\": \"map\"}"
    And I press "Save"

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Map paragraph test"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Map"
    Then the following fields should be present "Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                        |
      | Use existing media field is required. |
    When I fill in "Use existing media" with "World map"
    And I press "Save"
    Then I should see the heading "Map paragraph test"
    And I should see the Webtools map "World map" on the page
