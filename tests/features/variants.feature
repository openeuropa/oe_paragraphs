@api
Feature: Paragraph types have different variants
  As a content editor
  I need to be able to chose paragraph variants, so the form is showing the variant fields only

  @javascript
  Scenario: I can chose paragraph variants and the form is changing accordingly
    Given I am logged in as a user with the "Editor" role
    And I follow "Add content"
    And I fill in "Title" with "Demo page"
    And I click "Add Rich text"
    And I break
    And I press "List additional actions"
    When I press "Add Listing item"
    # Then I should not see "Date"
