@api
Feature: Paragraph types show different fields based on variants.
  As a content editor
  I need to be able to chose paragraph variants
  so that the form is showing the variant fields only.

  # This scenario covers the bug discovered in OPENEUROPA-1843.
  @javascript
  Scenario: Paragraph variant is correctly saved and persisted when re-saving the content.
    Given I am logged in as a user with the "Editor" role
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Test page"
    And I press "List additional actions"
    And I press "Add Listing item block"
    And I wait for AJAX to finish
    And I select "One column" from "Layout" in the 1st "Listing item block" paragraph
    And I fill in "Title" with "Block title" in the 1st "Listing item block" paragraph
    And I select "Highlight" from "Variant" in the 1st "Listing item"
    And I wait for AJAX to finish
    And I fill in "Link" with "<front>" in the 1st "Listing item" paragraph
    And I fill in "Title" with "First item" in the 1st "Listing item" paragraph
    And I press "Save"
    Then I should see the heading "Test page"
    When I click "Edit"
    # Open the listing item form and verify that the variant is the correct one.
    And I press "Edit" in the 1st "Listing item" paragraph actions
    And I wait for AJAX to finish
    Then the option "Highlight" should be selected in the "Variant" select of the 1st "Listing item" paragraph
    When I fill in "Title" with "First item v2" in the 1st "Listing item" paragraph
    And I press "Save"
    Then I should see the heading "Test page"
    When I click "Edit"
    # Check again that the variant field has the correct value after editing.
    And I press "Edit" in the 1st "Listing item" paragraph actions
    And I wait for AJAX to finish
    Then the option "Highlight" should be selected in the "Variant" select of the 1st "Listing item" paragraph
    # Reload the page so that the listing item will start in "closed" state.
    When I reload the page
    # Save without opening the paragraph form.
    And I press "Save"
    Then I should see the heading "Test page"
    When I click "Edit"
    And I press "Edit" in the 1st "Listing item" paragraph actions
    And I wait for AJAX to finish
    # Verify that the variant has been kept.
    Then the option "Highlight" should be selected in the "Variant" select of the 1st "Listing item" paragraph
