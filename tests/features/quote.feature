@api
Feature: Quote paragraph.
  As a content editor
  I need to be able to use quote paragraphs
  so that I can add quoted text and their attribution.

  Scenario: Quote paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Quote paragraph test page"
    And I press "Add Quote"
    Then the following fields should be present "Quote text, Attribution"
    # Rich text paragraph has no variants.
    And the following fields should not be present "Variant"

    When I press "Save"
    Then I should see the following error messages:
      | error messages                |
      | Quote text field is required  |
      | Attribution field is required |

    When I fill in "Quote text" with "This quote is awesome" in the 1st "Quote" paragraph
    And I fill in "Attribution" with "Literally nobody" in the 1st "Quote" paragraph
    And I press "Save"
    Then I should see the heading "Quote paragraph test page"
    And I should see the text "This quote is awesome"
    And I should see the text "Literally nobody"
