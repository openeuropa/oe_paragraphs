@api
Feature: Timeline paragraph.
  As a content editor
  I need to be able to use timeline paragraphs
  so that I can add time axis on my page.

  Scenario: Timeline paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Timeline paragraph test"
    And I press "Add Timeline"
    Then the following fields should be present "Label, Title, Content, Expand button"
    # The first widget has both fields required.
    When I press "Save"
    Then I should see the following error messages:
      | error messages           |
      | Label field is required. |
      | Title field is required. |

    When I fill in "Label" with "Item 1 label" in the first "Items" field element
    And I fill in "Title" with "Item 1 title" in the first "Items" field element
    And I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph test has been created."
    And I should see the text "Item 1 label"
    And I should see the text "Item 1 title"

    When I click "Edit"
    # Verify that the title field is required if a value is entered in the
    # label field.
    When I fill in "Label" with "Item 2 label" in the second "Items" field element
    And I press "Save"
    Then I should see the following error message:
      | error messages                                                 |
      | The Title field is required when the Label field is specified. |

    # Clear the label field.
    When I fill in "Label" with "" in the second "Items" field element
    # Verify that the label field is required if a value is entered in the
    # title field.
    When I fill in "Title" with "Item 2 title" in the second "Items" field element
    And I press "Save"
    Then I should see the following error message:
      | error messages                                                 |
      | The Label field is required when the Title field is specified. |

    # Clear the title field.
    When I fill in "Title" with "" in the second "Items" field element
    # Verify that label and title fields are required if a value is entered in
    # the body field.
    When I fill in "Content" with "Item 2 content" in the second "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                                               |
      | The Label and Title fields are required when the Content field is specified. |

    When I fill in "Label" with "Item 2 label" in the second "Items" field element
    And I fill in "Title" with "Item 2 title" in the second "Items" field element
    And I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph test has been updated."
    And I should see the text "Item 1 label"
    And I should see the text "Item 1 title"
    And I should see the text "Item 2 label"
    And I should see the text "Item 2 title"
    And I should see the text "Item 2 content"

    When I click "Edit"
    # Check that the paragraph is not collpased by searching for a field.
    Then the following field should be present "Expand button"
    # Verify that we can edit the page and save it without adding any item.
    When I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph test has been updated."

    # Verify that an item can be deleted by emptying its values.
    When I click "Edit"
    And I fill in "Label" with "" in the second "Items" field element
    And I fill in "Title" with "" in the second "Items" field element
    And I fill in "Content" with "" in the second "Items" field element
    And I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph test has been updated."
    And I should see the text "Item 1 label"
    And I should see the text "Item 1 title"
    But I should not see the text "Item 2 label"
    And I should not see the text "Item 2 title"
    And I should not see the text "Item 2 content"

  @javascript
  Scenario: Timeline fields are marked visually as required.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Timeline paragraph fields test"
    And I press "List additional actions"
    And I press "Add Timeline"
    Then the "Label" field in the "Items" field item should be marked as required
    And the "Title" field in the "Items" field item should be marked as required
    But the "Content" field in the "Items" field item should not be marked as required
    # Fill the first item with values, so we can add a new item.
    And I fill in "Label" with "Item 1 label" in the first "Items" field element
    And I fill in "Title" with "Item 1 title" in the first "Items" field element
    And I press "Add another item"
    # When all the fields are empty, no field is marked as required.
    Then the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required
    And the "Content" field in the 2nd item of the "Items" field should not be marked as required
    # Filling the Content field doesn't trigger any required state, as the
    # state form API doesn't behave well with fields that host editors.
    When I fill in "Content" with "Item 2 content" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required
    And the "Content" field in the 2nd item of the "Items" field should not be marked as required
    # Clear the content value.
    When I fill in "Content" with "" in the second "Items" field element

    When I fill in "Label" with "Item 2 label" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should be marked as required
    And the "Title" field in the 2nd item of the "Items" field should be marked as required
    But the "Content" field in the 2nd item of the "Items" field should not be marked as required

    When I fill in "Label" with "" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required
    And the "Content" field in the 2nd item of the "Items" field should not be marked as required

    When I fill in "Title" with "Item 2 title" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should be marked as required
    And the "Title" field in the 2nd item of the "Items" field should be marked as required
    But the "Content" field in the 2nd item of the "Items" field should not be marked as required

    When I fill in "Title" with "" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required
    And the "Content" field in the 2nd item of the "Items" field should not be marked as required

    # Add another item to verify that the correct items are marked when
    # multiple field items are available.
    When I press "Add another item"
    And I fill in "Label" with "Item 3 label" in the third "Items" field element
    Then the "Label" field in the 3rd item of the "Items" field should be marked as required
    And the "Title" field in the 3rd item of the "Items" field should be marked as required
    But the "Content" field in the 3rd item of the "Items" field should not be marked as required
    And the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required

    When I fill in "Label" with "" in the third "Items" field element
    Then the "Label" field in the 3rd item of the "Items" field should not be marked as required
    And the "Title" field in the 3rd item of the "Items" field should not be marked as required
    And the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required

    # Fill the second item.
    When I fill in "Label" with "Item 2 label" in the second "Items" field element
    And I fill in "Title" with "Item 2 title" in the second "Items" field element
    And I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph fields test has been created."

    # Re-edit the page and verify that on load fields are marked are required.
    When I click "Edit"
    Then the "Label" field in the 2nd item of the "Items" field should be marked as required
    And the "Title" field in the 2nd item of the "Items" field should be marked as required

    # Clearing the fields of the second item will unmark them and allow to save.
    When I fill in "Label" with "" in the second "Items" field element
    And I fill in "Title" with "" in the second "Items" field element
    Then the "Label" field in the 2nd item of the "Items" field should not be marked as required
    And the "Title" field in the 2nd item of the "Items" field should not be marked as required

    When I press "Save"
    Then I should see the success message "Demo landing page Timeline paragraph fields test has been updated."
