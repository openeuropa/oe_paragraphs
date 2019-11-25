@api
Feature: Content row paragraph.
  As a content editor
  I need to be able to use content row paragraphs
  so that I can group information together.

  Scenario: Content row paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Content row test page"
    And I press "Add Content row"
    Then the following fields should be present "Variant"
    # Verify the fields displayed in the "default" variant.
    And the following field should not be present "Navigation title"
    And the "Paragraphs" field in the 1st "Content row" paragraph can reference:
      | Accordion             |
      | Contextual navigation |
      | Social media follow   |
      | Links block           |
      | Listing item block    |
      | Quote                 |
      | Rich text             |

    When I select "Inpage navigation" from "Variant" in the 1st "Content row" paragraph
    And I press "Change variant"
    Then the following field should be present "Navigation title"
    # No fields are required.
    When I press "Save"
    Then I should see the heading "Content row test page"

    When I click "Edit"
    And I fill in "Navigation title" with "Quick links" in the 1st "Content row" paragraph
    And I press "Save"
    Then I should see the heading "Content row test page"
    And I should see the text "Navigation title"
