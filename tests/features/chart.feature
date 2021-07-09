@api
Feature: Chart paragraph.
  As a content editor
  I need to be able to use chart paragraphs
  so that I can reference Webtools chart media entities.

  @cleanup:media
  Scenario: Chart paragraph creation.
    Given I am logged in as a user with the "Editor" role
    And I visit "the Webtools chart creation page"
    And I fill in "Name" with "Basic chart"
    When I fill in "Webtools chart snippet" with "{\"service\":\"charts\",\"data\":{\"series\":[{\"name\":\"Y\",\"data\":[{\"name\":\"1\",\"y\":0.5}]}]},\"provider\":\"highcharts\"}"
    And I press "Save"

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Chart paragraph test"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Chart"
    Then the following fields should be present "Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                        |
      | Use existing media field is required. |
    When I fill in "Use existing media" with "Basic chart"
    And I press "Save"
    Then I should see the heading "Chart paragraph test"
    And I should see the Webtools chart "Basic chart" on the page
