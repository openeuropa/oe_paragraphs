@api
Feature: Illustration list with flags and Illustration item with flag paragraphs.
  As a content editor
  I need to be able to use Illustration list with flags and Illustration item with flag paragraphs
  so I can add items with flag to the content.

  Scenario: Illustration list with flags paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Illustration list with flags paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Illustration list with flags"
    # Check Illustration list with flags paragraph fields.
    Then the following fields should be present "Variant, Title, Columns, Center the content" in the "demo paragraphs element" region
    And the available options in the "Columns" select should be:
      | - Select a value - |
      | Two columns        |
      | Three columns      |
      | Four columns       |
    # Check Illustration item with flag paragraphs fields.
    And the following fields should be present "Flag, Highlight, Title, Body" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages             |
      | Flag field is required.    |
      | Columns field is required. |

    When I fill in "Title" with "Illustration list with flags title" in the 1st "Illustration list with flags" paragraph
    And I select "Two columns" from "Columns" in the 1st "Illustration list with flags" paragraph
    And I select "Austria" from "Flag" in the 1st "Illustration item with flag" paragraph
    And I fill in "Highlight" with "Highlighted flag term 1" in the 1st "Illustration item with flag" paragraph
    And I fill in "Title" with "Illustration item with flag term 1" in the 1st "Illustration item with flag" paragraph
    And I fill in "Body" with "Illustration item with flag description 1" in the 1st "Illustration item with flag" paragraph
    And I press "Illustration item with flag"
    And I select "Belgium" from "Flag" in the 2nd "Illustration item with flag" paragraph
    And I fill in "Highlight" with "Highlighted flag term 2" in the 2nd "Illustration item with flag" paragraph
    And I fill in "Title" with "Illustration item with flag term 2" in the 2nd "Illustration item with flag" paragraph
    And I fill in "Body" with "Illustration item with flag description 2" in the 2nd "Illustration item with flag" paragraph
    And I press "Save"
    Then I should see the heading "Illustration list with flags paragraph test page"
    And I should see the text "Illustration list with flags title"
    And I should see the text "Center the content"
    And I should see the text "Austria"
    And I should see the text "Highlighted flag term 1"
    And I should see the text "Illustration item with flag term 1"
    And I should see the text "Illustration item with flag description 1"
    And I should see the text "Belgium"
    And I should see the text "Highlighted flag term 2"
    And I should see the text "Illustration item with flag term 2"
    And I should see the text "Illustration item with flag description 2"
    And I should see the text "Two columns"
    And I should see the text "Off"

    # Re-edit the page and verify the Vertical variant.
    When I click "Edit"
    And I select "Vertical" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Variant, Title, Alternating background, Center the content" in the "demo paragraphs element" region
    And the following field should not be present "Columns" in the "demo paragraphs element" region

    When I check "Alternating background"
    And I check "Center the content"
    And I press "Save"
    Then I should see the text "On"
    And I should not see the text "Off"
