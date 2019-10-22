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
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Title field is required |
    When I fill in "Title" with "Follow this page" in the 1st "Social media follow" paragraph
    And I select "<options>" from "Variant"
    And I fill in "URL" with "http://facebook.com"
    And I fill in "Link text" with "Facebook"
    And I select "Facebook" from "Link type"
    And I press "Save"
    Then I should see the heading "Social media follow paragraph test page"
    And I should see the text "Facebook"

    Examples:
    | options    |
    | Horizontal |
    | Vertical   |
