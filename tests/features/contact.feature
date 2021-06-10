@api
Feature: Contact paragraph.
  As a content editor
  I need to be able to use Contact paragraphs
  so that I can reference General and Press Contact entities.

  Scenario: Contact creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Contact paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Contact"
    # Check Contact paragraph fields.
    Then the following field should be present "Heading" in the "demo paragraphs element" region
    And I should see the text "Contacts"
    # Check the required field validation.
    When I press "Save"
    Then I should see the following error messages:
      | error messages              |
      | Contacts field is required. |
    When I fill in "Heading" with "Contact paragraph demo"
    And I press "Add new Contact"
    And I fill in "Name" with "First Contact"
    And I press "Create Contact"
    And I press "Add new Contact"
    And I fill in "Name" with "Second Contact"
    And I press "Save"
    Then I should see the heading "Contact paragraph test page"
    And I should see the text "Contact paragraph demo"
    And I should see the text "First Contact"
    And I should see the text "Second Contact"

