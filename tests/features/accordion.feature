@api
Feature: Accordion paragraph.
  As a content editor
  I need to be able to use accordion paragraphs
  so that I can show collapsible lists.

  @javascript
  Scenario: Accordion and accordion item CRUD operations.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Accordion page"
    And I press "List additional actions"
    And I press "Add Accordion"
    Then I wait for AJAX to finish
    And I fill in "Title" with "Accordion item 1 title" in the 1st "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 1 body" in the 1st "Accordion item" paragraph
    And I select "Book" from "Icon" in the 1st "Accordion item" paragraph
    When I press "Add Accordion item"
    And I wait for AJAX to finish
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

    When I click "Edit"
    # Test that values are kept when the form is saved with paragraph collapsed.
    And I press "Save"
    Then I should see the text "Book"
    And I should see the text "Accordion item 1 title"
    And I should see the text "Accordion item 1 body"
    And I should see the text "Feedback"
    And I should see the text "Accordion item 2 title"
    And I should see the text "Accordion item 2 body"

    # Edit one accordion item.
    When I click "Edit"
    And I press "Edit" in the 1st "Accordion item" paragraph actions
    And I wait for AJAX to finish
    And I fill in "Title" with "Accordion item 1 title updated" in the 1st "Accordion item" paragraph
    And I fill in "Body" with "Accordion item 1 body updated" in the 1st "Accordion item" paragraph
    And I select "Digital" from "Icon" in the 1st "Accordion item" paragraph
    # Collapse the acccordion item form.
    And I press "Collapse" in the 1st "Accordion item" paragraph actions
    And I wait for AJAX to finish
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
    And I press "Toggle Actions" in the 1st "Accordion item" paragraph actions
    And I press "Remove" in the 1st "Accordion item" paragraph actions
    And I wait for AJAX to finish
    And I press "Save"
    Then I should see the text "Feedback"
    And I should see the text "Accordion item 2 title"
    And I should see the text "Accordion item 2 body"
    But I should not see the text "Digital"
    And I should not see the text "Accordion item 1 title updated"
    And I should not see the text "Accordion item 1 body updated"

    # Delete the whole accordion.
    When I click "Edit"
    And I press "Toggle Actions" in the 1st "Accordion" paragraph actions
    And I press "Remove" in the 1st "Accordion" paragraph actions
    And I wait for AJAX to finish
    And I press "Save"
    Then I should see the heading "Accordion page"
    But I should not see the text "Digital"
    And I should not see the text "Accordion item 1 title updated"
    And I should not see the text "Accordion item 1 body updated"
    And I should not see the text "Feedback"
    And I should not see the text "Accordion item 2 title"
    And I should not see the text "Accordion item 2 body"
