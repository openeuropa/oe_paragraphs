@api
Feature: Illustration list with icons and Illustration item with icon paragraphs.
  As a content editor
  I need to be able to use Illustration list with icons and Illustration item with icon paragraphs
  so I can add items with icon to the content.

  Scenario: Illustration list with icons paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Illustration list with icons paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Illustration list with icons"
    # Check Illustration list with icons paragraph fields.
    Then the following fields should be present "Variant, Title, Columns" in the "demo paragraphs element" region
    And the available options in the "Columns" select should be:
      | - Select a value - |
      | Two columns        |
      | Three columns      |
      | Four columns       |
    # Check Illustration item with icon paragraphs fields.
    And the following fields should be present "Icon, Title, Body" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages             |
      | Icon field is required.    |
      | Columns field is required. |

    When I fill in "Title" with "Illustration list with icons title" in the 1st "Illustration list with icons" paragraph
    And I select "Two columns" from "Columns" in the 1st "Illustration list with icons" paragraph
    And I select "Budget" from "Icon" in the 1st "Illustration item with icon" paragraph
    And I fill in "Title" with "Illustration item with icon term 1" in the 1st "Illustration item with icon" paragraph
    And I fill in "Body" with "Illustration item with icon description 1" in the 1st "Illustration item with icon" paragraph
    And I press "Illustration item with icon"
    And I select "Digital" from "Icon" in the 2nd "Illustration item with icon" paragraph
    And I fill in "Title" with "Illustration item with icon term 2" in the 2nd "Illustration item with icon" paragraph
    And I fill in "Body" with "Illustration item with icon description 2" in the 2nd "Illustration item with icon" paragraph
    And I press "Save"
    Then I should see the heading "Illustration list with icons paragraph test page"
    And I should see the text "Illustration list with icons title"
    And I should see the text "Budget"
    And I should see the text "Illustration item with icon term 1"
    And I should see the text "Illustration item with icon description 1"
    And I should see the text "Digital"
    And I should see the text "Illustration item with icon term 2"
    And I should see the text "Illustration item with icon description 2"
    And I should see the text "Two columns"
    And I should see the text "Off"

    # Re-edit the page and verify the Vertical variant.
    When I click "Edit"
    And I select "Vertical" from "Variant"
    And I press "Change variant"
    Then the following field should be present "Alternating background" in the "demo paragraphs element" region
    And the following field should not be present "Columns" in the "demo paragraphs element" region

    When I check "Alternating background"
    And I press "Save"
    Then I should see the text "On"
    And I should not see the text "Off"
