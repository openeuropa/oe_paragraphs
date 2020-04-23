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
    When I fill in "Label" with "Label" in the "first" "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                   |
      | Title field is required if there is Label input. |
    When I fill in "Label" with "" in the "first" "Items" field element
    And I fill in "Title" with "Title" in the "first" "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                   |
      | Label field is required if there is Title input. |
    When I fill in "Title" with "" in the "first" "Items" field element
    And I fill in "Content" with "Content"
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | Label field is required if there is Content input. |
      | Title field is required if there is Content input. |
    When I fill in "Label" with "Label" in the "first" "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                               |
      | Title field is required if there is Label and Content input. |
    When I fill in "Label" with "" in the "first" "Items" field element
    When I fill in "Title" with "Title" in the "first" "Items" field element
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                               |
      | Label field is required if there is Title and Content input. |
    When I fill in "Label" with "Label 1" in the "first" "Items" field element
    And I fill in "Title" with "Title 1" in the "first" "Items" field element
    And I fill in "Content" with "Description 1" in the "first" "Items" field element
    And I press "Add another item"
    And I fill in "Label" with "Label 2" in the "second" "Items" field element
    And I fill in "Title" with "Title 2" in the "second" "Items" field element
    And I fill in "Content" with "Description 2" in the "second" "Items" field element
    And I press "Save"
    Then I should see the heading "Timeline paragraph test"
    And I should see the text "Label 1"
    And I should see the text "Title 1"
    And I should see the text "Description 1"
    And I should see the text "Label 2"
    And I should see the text "Title 2"
    And I should see the text "Description 2"
