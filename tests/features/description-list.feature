@api
Feature: Description List paragraph.
  As a content editor
  I need to be able to use Description List paragraphs
  so I can display an Overview.

  Scenario: Description List paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content" permission
    # Add Description List paragraph
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Description List test page"
    And I press "Add Description List"

    # Assert the fields are visible.
    And the following fields should be present "Heading, Term, Description"
    When I press "Save"
    Then I should see the following error messages:
      | error messages            |
      | Heading field is required |

    When I fill in "Heading" with "Overview"
    And I fill in the 1st "Term" field with "Term 1"
    And I fill in the 1st "Description" field with "Description 1"
    And I press the "Add another item" button
    And I fill in the 2nd "Term" field with "Term 2"
    And I fill in the 2nd "Description" field with "Description 2"
    And I press "Save"
    Then I should see the heading "Description List test page"
    And I should see the text "Overview"
    And I should see the text "Term 1"
    And I should see the text "Description 1"
    And I should see the text "Term 2"
    And I should see the text "Description 2"
