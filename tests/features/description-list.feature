@api
Feature: Description List paragraph.
  As a content editor
  I need to be able to use Horizontal description list paragraphs
  so I can display a list of term/description pairs.

  Scenario: Horizontal description list paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content" permission
    # Add Description List paragraph
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Description List test page"
    And I press "Add Horizontal description list"

    # Assert the fields are visible.
    And the following fields should be present "Heading, Term, Description"
    When I press "Save"
    # Assert all the fields are required for the first item.
    Then I should see the following error messages:
      | error messages                 |
      | Heading field is required      |
      | Term field is required.        |
      | Description field is required. |

    When I fill in "Heading" with "Overview"
    And I fill in the 1st "Term" field with "Term 1"
    And I fill in the 1st "Description" field with "Description 1"
    And I press the "Add another item" button
    # Assert Description field is required if Term field is filled in.
    And I fill in the 2nd "Term" field with "Term 2"
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                                      |
      | The Description field is required when the Term field is specified. |
    # Empty Term field and assert Term field is required if Description field is filled in.
    When I fill in the 2nd "Term" field with ""
    And I fill in the 2nd "Description" field with "Description 2"
    And I press "Save"
    Then I should see the following error messages:
      | error messages                                                      |
      | The Term field is required when the Description field is specified. |
    When I fill in the 2nd "Term" field with "Term 2"
    And I press "Save"
    Then I should see the heading "Description List test page"
    And I should see the text "Overview"
    And I should see the text "Term 1"
    And I should see the text "Description 1"
    And I should see the text "Term 2"
    And I should see the text "Description 2"
