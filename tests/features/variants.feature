@api
Feature: Paragraph types show different fields based on variants.
  As a content editor
  I need to be able to chose paragraph variants
  so that the form is showing the variant fields only.

  @javascript
  Scenario: I can chose paragraph variants and the form is changing accordingly.
    Given I am logged in as a user with the "Editor" role
    And I go to "the content management page"
    And I click "Add content"
    And I press "List additional actions"

    # Rich text.
    When I press "Add Rich text"
    Then the following fields should be present "Title, Text"
    And the following field should not be present "Variant"

    # Contextual navigation.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Contextual navigation"
    And I wait for AJAX to finish
    Then the following fields should be present "Navigation label, URL, Limit, More label"
    And the following field should not be present "Variant"

    # Links block.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Links block"
    Then the following fields should be present "Title, URL, Link text"
    And the following field should not be present "Variant"

    # Accordion.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Accordion"
    Then the following fields should be present "Icon, Title, Body"
    And the following field should not be present "Variant"

    # Quote.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Quote"
    Then the following fields should be present "Quote, Attribution"
    And the following field should not be present "Variant"

    # Listing item "Default" variant.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Listing item"
    Then the following fields should be present "Variant, Link, Title, Description, Meta"
    And the following fields should not be present "Day, Month, Year"
    # Listing item "Date" variant.
    When I select "Date" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Variant, Link, Title, Description, Meta, Day, Month, Year"
    And the following field should not be present "Image"
    # Listing item "Highlight" variant.
    When I select "Highlight" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Variant, Link, Title, Description, Meta, Image"
    And the following field should not be present "Day, Month, Year"
    # Listing item "Thumbnail primary" variant.
    When I select "Thumbnail primary" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Variant, Link, Title, Meta, Image"
    And the following field should not be present "Description, Day, Month, Year"
    # Listing item "Thumbnail secondary" variant.
    When I select "Thumbnail secondary" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Variant, Link, Title, Meta, Image, Description"
    And the following field should not be present "Day, Month, Year"

    # Content row.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Content row"
    Then the following fields should be present "Variant"
    And the following field should not be present "Navigation title"
    # Content row "Inpage navigation" variant.
    When I select "Inpage navigation" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Variant, Navigation title"

    # Listing item block.
    When I click "Add content"
    And I press "List additional actions"
    And I press "Add Listing item block"
    Then the following fields should be present "Layout, Variant, Link, Title, Description, Meta, URL, Link text"
    And the following fields should not be present "Day, Month, Year"
    When I select "Date" from "Variant"
    And I wait for AJAX to finish
    Then the following fields should be present "Layout, Variant, Link, Title, Description, Meta, URL, Link text, Day, Month, Year"

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
    And I fill in "Link" with "<front>" in the 1st "Listing item"
    And I fill in "Title" with "First item" in the 1st "Listing item"
    And I press "Save"
    Then I should see the heading "Test page"
    When I click "Edit"
    # Open the listing item form and verify that the variant is the correct one.
    And I press "Edit" in the 1st "Listing item" paragraph actions
    And I wait for AJAX to finish
    Then the option "Highlight" should be selected in the "Variant" select of the 1st "Listing item" paragraph
    When I fill in "Title" with "First item v2" in the 1st "Listing item"
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
