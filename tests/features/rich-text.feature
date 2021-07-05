@api
Feature: Rich text paragraph.
  As a content editor
  I need to be able to use rich text paragraphs
  so that I can add text to my pages.

  Scenario: Rich text paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Rich text paragraph test"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Rich text"
    Then the following fields should be present "Title, Text" in the "demo paragraphs element" region
    # Rich text paragraph has no variants.
    And the following fields should not be present "Variant" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages         |
      | Text field is required |
    When I fill in "Text" with "A sample text for the page." in the 1st "Rich text" paragraph
    And I press "Save"
    Then I should see the heading "Rich text paragraph test"
    And I should see the text "A sample text for the page."

    When I click "Edit"
    And I fill in "Title" with "Paragraph title" in the 1st "Rich text" paragraph
    And I press "Save"
    Then I should see the heading "Rich text paragraph test"
    And I should see the text "Paragraph title"
    And I should see the text "A sample text for the page."
