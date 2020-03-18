@api
Feature: Facts and figures paragraph.
  As a content editor
  I need to be able to use Facts and figures paragraphs
  so I can add facts and figures to the content.

  Scenario: Facts and figures paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Facts and figures paragraph test page"
    And I press "Add Facts and figures"
    # Check Facts and figures paragraph fields.
    Then the following fields should be present "Title, URL, Link text"
    # Check the fields of the referenced Fact paragraph.
    And the following fields should be present "Figure, Icon, Description, Label"

    When I press "Save"
    Then I should see the following error messages:
      | error messages           |
      | Label field is required  |
      | Figure field is required |

    When I fill in "Title" with "Facts and figures" in the 1st "Facts and figures" paragraph
    And I fill in "URL" with "https://example.com"
    And I fill in "Link text" with "Example"
    And I select "Budget" from "Icon" in the 1st "Fact" paragraph
    And I fill in "Figure" with "10 millions" in the 1st "Fact" paragraph
    And I fill in "Label" with "1st Fact" in the 1st "Fact" paragraph
    And I fill in "Description" with "1st Description" in the 1st "Fact" paragraph
    And I press "Add Fact"
    And I select "Book" from "Icon" in the 2nd "Fact" paragraph
    And I fill in "Figure" with "22 millions" in the 2nd "Fact" paragraph
    And I fill in "Label" with "2nd Fact" in the 2nd "Fact" paragraph
    And I fill in "Description" with "2nd Description" in the 2nd "Fact" paragraph
    And I press "Save"
    Then I should see the heading "Facts and figures paragraph test page"
    And I should see the text "Facts and figures"
    And I should see the link "Example"
    And I should see the text "1st Fact"
    And I should see the text "1st Description"
    And I should see the text "10 millions"
    And I should see the text "Budget"
    And I should see the text "2nd Fact"
    And I should see the text "2nd Description"
    And I should see the text "22 millions"
    And I should see the text "Book"
