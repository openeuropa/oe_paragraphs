@api
Feature: Paragraph types have different variants
  As a content editor
  I need to be able to chose paragraph variants, so the form is showing the variant fields only

  @javascript
  Scenario: I can chose paragraph variants and the form is changing accordingly
    Given I am logged in as a user with the "Editor" role
    And I follow "Add content"
    And I press "List additional actions"
    When I press "Add Listing item"

    # Date variant
    Then I should not see "Date information. Used only on date variant."
    When I select "Date" from "Variant"
    And I wait for AJAX to finish
    Then I should see "Date information. Used only on date variant."

    # Thumbnail variant
    And I should not see "Image"
    And I should not see "List item image. Used only on thumbnail variants."
    When I select "Thumbnail primary" from "Variant"
    And I wait for AJAX to finish
    Then I should see "Image"
    And I should see "List item image. Used only on thumbnail variants."

    # Navigate to a new form
    And I follow "Add content"

    # Inpage naviagtion variant
    When I press "List additional actions"
    And I press "Add Content row"
    Then I should not see "Navigation title"
    And I should not see "The title to show for the inpage navigation. Used only for the inpage navigation variant."
    When I select "Inpage navigation" from "Variant"
    And I wait for AJAX to finish
    Then I should see "Navigation title"
    And I should see "The title to show for the inpage navigation. Used only for the inpage navigation variant."
