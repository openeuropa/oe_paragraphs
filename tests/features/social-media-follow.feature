@api
Feature: Social media follow paragraph.
  As a content editor
  I need to be able to use Social media follow paragraphs
  so I can add social media links to the content.

  Scenario Outline: Social media follow paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Social media follow paragraph test page"
    And I press "Add Social media follow"
    Then the following fields should be present "Title, URL, Link text, Link type"
    And the available options in the Variant select should be:
      | Horizontal |
      | Vertical   |
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Title field is required |
    When I fill in "Title" with "Follow this page" in the 1st "Social media follow" paragraph
    And I select "<options>" from "Variant"
    And I fill in the 1st "URL" field with "http://facebook.com"
    And I fill in the 1st "Link text" field with "Facebook"
    And I select "Facebook" from the 1st "Link type"
    And I press "Add another item" in the "social media links form element"
    And I fill in the 2nd "URL" field with "http://twitter.com"
    And I fill in the 2nd "Link text" with "1st Twitter"
    And I select "Twitter" from the 2nd "Link type"
    And I press "Add another item" in the "social media links form element"
    And I fill in the 3rd "URL" field with "http://twitter.com"
    And I fill in the 3rd "Link text" with "2nd Twitter"
    And I select "Twitter" from the 3rd "Link type"
    And I press "Save"
    Then I should see the heading "Social media follow paragraph test page"
    And I should see the text "Facebook"
    And I should see the text "1st Twitter"
    And I should see the text "2nd Twitter"

    Examples:
    | options    |
    | Horizontal |
    | Vertical   |
