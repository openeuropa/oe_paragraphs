@api @javascript
Feature: Text with featured media paragraph.
  As a content editor
  I need to be able to use Text with featured media paragraphs
  so that I can add featured media together with explanatory text.

  Scenario: Text with featured media paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Text with Featured media paragraph test page"
    And I press "List additional actions"
    And I press "Add Text with Featured media"
    Then the following fields should be present "Title, Image, Caption, Full text"

    When I fill in "Title" with "Title text" in the 1st "Text with Featured media" paragraph
    And I attach the file "media/example_1.jpeg" to "Image"
    And I wait for AJAX to finish
    And I fill in "Alternative text" with "Image Alt Text 1"
    And I fill in "Caption" with "Caption text" in the 1st "Text with Featured media" paragraph
    And I fill in "Full text" with "Featured text" in the 1st "Text with Featured media" paragraph
    And I press "Save"
    Then I should see the heading "Text with Featured media paragraph test page"
    And I should see the text "Title text"
    And I should see the image "example_1.jpeg"
    And I should see the text "Caption text"
    And I should see the text "Featured text"
