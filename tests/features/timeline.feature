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
    When I fill in "Label" with "Item 1 Label" in the first "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                                 |
      | The Title field is required when the Label field is specified. |
    When I fill in "Label" with "" in the first "Items" field element
    And I fill in "Title" with "Item 1 Title" in the first "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                                 |
      | The Label field is required when the Title field is specified. |
    When I fill in "Title" with "" in the first "Items" field element
    And I fill in "Content" with "Item 1 Content"
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | The Label and Title fields are required when the Body field is specified. |
    When I fill in "Label" with "Item 1 Label" in the first "Items" field element
    And I fill in "Title" with "Item 1 Title" in the first "Items" field element
    And I fill in "Content" with "Item 1 Description" in the first "Items" field element
    And I press "Add another item"
    And I fill in "Label" with "Item 2 Label" in the second "Items" field element
    And I fill in "Title" with "Item 2 Title" in the second "Items" field element
    And I fill in "Content" with "Item 2 Description" in the second "Items" field element
    And I press "Save"
    Then I should see the heading "Timeline paragraph test"
    And I should see the text "Item 1 Label"
    And I should see the text "Item 1 Title"
    And I should see the text "Item 1 Description"
    And I should see the text "Item 2 Label"
    And I should see the text "Item 2 Title"
    And I should see the text "Item 2 Description"
