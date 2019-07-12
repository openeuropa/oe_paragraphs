@api
Feature: Links block paragraph.
  As a content editor
  I need to be able to use links block paragraphs
  so that I can add a list of links to a page.

  Scenario: Links block paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Links block test page"
    And I press "Add Links block"
    Then the following fields should be present "Title, URL, Link text"
    # Rich text paragraph has no variants.
    And the following fields should not be present "Variant"

    When I press "Save"
    # No fields are required.
    Then I should see the heading "Links block test page"

    When I click "Edit"
    And I press "Edit" in the 1st "Links block" paragraph actions
    And I fill in "Title" with "List of links" in the 1st "Links block" paragraph
    And I fill in "URL" with "http://example.com/1" in the 1st "Links block" paragraph
    And I fill in "Link text" with "First link" in the 1st "Links block" paragraph
    And I press "Save"
    Then I should see the heading "Links block test page"
    And I should see the text "List of links"
    And I should see the link "First link"

    When I click "Edit"
    And I press "Edit" in the 1st "Links block" paragraph actions
    # Add another link.
    And I fill in the 2nd "URL" field with "http://example.com/1"
    And I fill in the 2nd "Link text" field with "Second link"
    And I press "Save"
    Then I should see the heading "Links block test page"
    And I should see the text "List of links"
    And I should see the link "First link"
    And I should see the link "Second link"
