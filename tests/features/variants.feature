@api
Feature: Paragraph types show different fields based on variants.
  As a content editor
  I need to be able to chose paragraph variants
  so that the form is showing the variant fields only.

  @javascript
  Scenario: I can chose paragraph variants and the form is changing accordingly.
    Given I am logged in as a user with the "Editor" role
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
