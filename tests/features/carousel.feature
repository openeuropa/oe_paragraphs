@api
Feature: Carousel paragraph.
  As a content editor
  I need to be able to use carousel paragraphs
  so I can convey important messages to the audience.

  @cleanup:media
  Scenario: Carousel paragraph creation.
    Given the following image:
      | name    | file           |
      | Image 1 | example_1.jpeg |
      | Image 2 | example_1.jpeg |

    When I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content" permission
    # Add Carousel paragraph
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Carousel test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Carousel"
    # Assert all the fields are present.
    Then the following fields should be present "Title, Description, URL, Link text, Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    # Assert required fields.
    Then I should see the following error messages:
      | error messages                        |
      | Title field is required.              |
      | Use existing media field is required. |
    When I fill in the 2nd "Title" field with "First item"
    And I fill in the 1st "Description" field with "First item - description"
    And I fill in the 1st "URL" field with "https://example1.com"
    And I fill in the 1st "Link text" field with "CTA 1"
    And I fill in the 1st "Use existing media" field with "Image 1"
    And I press "Save"
    # Assert custom validation for items cardinality message.
    Then I should see the following error messages:
      | error messages                                          |
      | The Carousel paragraph should contain at least 2 items. |
    # Add a second Carousel item.
    When I press "Add Carousel item"
    And I fill in the 3nd "Title" field with "Second item"
    And I fill in the 2nd "Description" field with "Second item - description"
    And I fill in the 2nd "URL" field with "https://example2.com"
    And I fill in the 2nd "Link text" field with "CTA 2"
    And I fill in the 2nd "Use existing media" field with "Image 2"
    And I press "Save"
    Then I should see the success message "Demo landing page Carousel test page has been created."
    And I should see the text "First item"
    And I should see the text "First item - description"
    And I should see the link "CTA 1"
    And I should see the image "example_1.jpeg"
    And I should see the text "Second item"
    And I should see the text "Second item - description"
    And I should see the link "CTA 2"
