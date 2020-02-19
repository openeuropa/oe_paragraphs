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
    Then the following fields should be present "Title, URL, Link text"
    # And fields of the referenced Fact and figure paragraph.
    And the following fields should be present "Label, Icon, Statistic, Description"

    When I press "Save"
    Then I should see the following error messages:
      | error messages              |
      | Label field is required     |
      | Statistic field is required |

    When I fill in "Title" with "Facts and figures" in the 1st "Facts and figures" paragraph
    And I fill in "URL" with "https://example.com"
    And I fill in "Link text" with "Example"
    And I fill in "Label" with "1st Fact"
    And I select "Budget" from "Icon"
    And I fill in "Statistic" with "10 millions"
    And I fill in "Description" with "1st Description"
    And I press "Save"
    Then I should see the heading "Facts and figures paragraph test page"
    And I should see the text "Facts and figures"
    And I should see the link "Example"
    And I should see the text "1st Fact"
    And I should see the text "1st Description"
    And I should see the text "10 millions"
    And I should see the text "Budget"

    # Facts and figures paragraph can contain up to 6 Fact and figure.
    When I click "Edit"
    And I press "Add Fact"
    And I press "Add Fact"
    And I press "Add Fact"
    And I press "Add Fact"
    And I press "Add Fact"
    Then I should not see the button "Add Fact"
