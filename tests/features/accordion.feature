@api
Feature: Accordion paragraph.
  As a content editor
  I need to be able to use accordion paragraphs
  so that I can show collapsible lists.

  Scenario: Accordion and accordion item required fields.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Required fields accordion"
    And I press "Add Accordion"
    # Neither accordion nor accordion item paragraphs have variants.
    Then the following fields should not be present "Variant"
    # Remove the accordion item that is shown by default in the page so we are
    # able to test the required fields of the accordion itself.
    When I press "Remove" in the 1st "Accordion item" paragraph actions
    And I press "Save"
    Then I should see the following error messages:
      | error messages               |
      | Paragraphs field is required |
    And the "Paragraphs" field in the 1st "Accordion" paragraph can reference:
      | Accordion item |

    # Test accordion item required fields.
    When I press "Add Accordion item"
    And I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Title field is required |
      | Body field is required  |
    When I fill in "Title" with "Accordion item 1 title" in the 1st "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 1 body" in the 1st "Accordion item" paragraph
    And I press "Save"
    Then I should see the heading "Required fields accordion"

  # Smoke test scenario for CRUD paragraph capabilities.
  Scenario: Accordion and accordion item CRUD operations.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Accordion page"
    And I press "Add Accordion"
    And I fill in "Title" with "Accordion item 1 title" in the 1st "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 1 body" in the 1st "Accordion item" paragraph
    And I select "Book" from "Icon" in the 1st "Accordion item" paragraph
    When I press "Add Accordion item"
    And I fill in "Title" with "Accordion item 2 title" in the 2nd "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 2 body" in the 2nd "Accordion item" paragraph
    And I select "Feedback" from "Icon" in the 2nd "Accordion item" paragraph
    And I press "Save"
    Then I should see the text "Book"
    And I should see the text "Accordion item 1 title"
    And I should see the text "Accordion item 1 body"
    And I should see the text "Feedback"
    And I should see the text "Accordion item 2 title"
    And I should see the text "Accordion item 2 body"

    # Edit one accordion item.
    When I click "Edit"
    And I press "Edit" in the 1st "Accordion" paragraph actions
    And I press "Edit" in the 1st "Accordion item" paragraph actions
    And I fill in "Title" with "Accordion item 1 title updated" in the 1st "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 1 body updated" in the 1st "Accordion item" paragraph
    And I select "Digital" from "Icon" in the 1st "Accordion item" paragraph
    # Collapse the acccordion item form.
    And I press "Collapse" in the 1st "Accordion item" paragraph actions
    # Finally save the page.
    And I press "Save"
    Then I should see the text "Digital"
    And I should see the text "Accordion item 1 title updated"
    And I should see the text "Accordion item 1 body updated"
    And I should see the text "Feedback"
    And I should see the text "Accordion item 2 title"
    And I should see the text "Accordion item 2 body"

    # Delete an accordion item.
    When I click "Edit"
    And I press "Edit" in the 1st "Accordion" paragraph actions
    And I press "Remove" in the 1st "Accordion item" paragraph actions
    And I press "Save"
    Then I should see the text "Feedback"
    And I should see the text "Accordion item 2 title"
    And I should see the text "Accordion item 2 body"
    But I should not see the text "Digital"
    And I should not see the text "Accordion item 1 title updated"
    And I should not see the text "Accordion item 1 body updated"

    # Delete the whole accordion.
    When I click "Edit"
    And I press "Remove" in the 1st "Accordion" paragraph actions
    And I press "Save"
    Then I should see the heading "Accordion page"
    But I should not see the text "Digital"
    And I should not see the text "Accordion item 1 title updated"
    And I should not see the text "Accordion item 1 body updated"
    And I should not see the text "Feedback"
    And I should not see the text "Accordion item 2 title"
    And I should not see the text "Accordion item 2 body"
