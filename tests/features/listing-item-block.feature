@api
Feature: Listing item block paragraph.
  As a content editor
  I need to be able to use listing item block paragraphs
  so that I can group information together.

  Scenario: Listing item block paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Listing item block test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Listing item block"
    # Remove the listing item that is shown by default in the page so we are
    # able to test the required fields of the listing item block itself.
    When I press "Remove" in the 1st "Listing item" paragraph actions
    Then the following fields should be present "Layout, Title, URL, Link text" in the "demo paragraphs element" region
    And the available options in the "Layout" select should be:
      | - Select a value - |
      | One column         |
      | Two columns        |
      | Three columns      |
      | Four columns       |
    # This paragraph has no variants.
    And the following field should not be present "Variant" in the "demo paragraphs element" region
    # Only listing item paragraphs can be added to this paragraph.
    And the "Items" field in the 1st "Listing item block" paragraph can reference:
      | Listing item |

    When I press "Save"
    Then I should see the following error messages:
      | error messages           |
      | Layout field is required |
      | Items field is required  |

    # Fill a single list item so that we meet the requirements.
    When I select "One column" from "Layout" in the 1st "Listing item block" paragraph
    And I press "Add Listing item"
    And I fill in "Link" with "http://example.org/1" in the 1st "Listing item" paragraph
    And I fill in "Title" with "Sample link" in the 1st "Listing item" paragraph
    And I press "Save"
    Then I should see the heading "Listing item block test page"
    And I should see the link "http://example.org/1"
    And I should see the text "Sample link"

    When I click "Edit"
    And I fill in "URL" with "http://example.org/see-more" in the 1st "Listing item block" paragraph
    And I fill in "Link text" with "See more" in the 1st "Listing item block" paragraph
    And I press "Save"
    Then I should see the heading "Listing item block test page"
    And I should see the link "http://example.org/1"
    And I should see the text "Sample link"
    And I should see the link "See more"
