@api
Feature: Contextual navigation paragraph.
  As a content editor
  I need to be able to use contextual navigation paragraphs
  so that I can add a list of inline links to a page.

  Scenario: Contextual navigation paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Contextual navigation test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Contextual navigation"
    Then the following fields should be present "Navigation label, URL, Link text, Limit, More label" in the "demo paragraphs element" region
    # Rich text paragraph has no variants.
    And the following fields should not be present "Variant" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages              |
      | URL field is required       |
      | Link text field is required |

    When I fill in "URL" with "<front>" in the 1st "Contextual navigation" paragraph
    And I fill in "Link text" with "Back to the home" in the 1st "Contextual navigation" paragraph
    And I press "Save"
    Then I should see the heading "Contextual navigation test page"

    When I click "Edit"
    And I fill in "Navigation label" with "Quick links" in the 1st "Contextual navigation" paragraph
    And I fill in "Limit" with "97" in the 1st "Contextual navigation" paragraph
    And I fill in "More label" with "See more" in the 1st "Contextual navigation" paragraph
    And I press "Save"
    Then I should see the heading "Contextual navigation test page"
    And I should see the text "Quick links"
    And I should see the link "Back to the home"
    And I should see the text "97"
    And I should see the text "See more"

    When I click "Edit"
    # Add another link.
    And I fill in the 2nd "URL" field with "/admin/content"
    And I fill in the 2nd "Link text" field with "Content listing"
    And I press "Save"
    Then I should see the heading "Contextual navigation test page"
    And I should see the text "Quick links"
    And I should see the link "Back to the home"
    And I should see the link "Content listing"
    And I should see the text "97"
    And I should see the text "See more"
