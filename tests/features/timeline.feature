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
    # Verify that the label field are required if a value is entered in the
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
