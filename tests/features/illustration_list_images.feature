@api
Feature: Illustration list with images and Illustration item with image paragraphs.
  As a content editor
  I need to be able to use Illustration list with images and Illustration item with image paragraphs
  so I can add items with image to the content.

  @cleanup:media
  Scenario: Illustration list with images paragraph creation.
    Given I am logged in as a user with the "Editor" role
    And the following images:
      | name    | file           |
      | Image 1 | example_1.jpeg |
      | Image 2 | example_1.jpeg |

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Illustration list with images paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Illustration list with images"
    # Check Illustration list with images paragraph fields.
    Then the following fields should be present "Variant, Title, Columns" in the "demo paragraphs element" region
    And the available options in the "Columns" select should be:
      | - Select a value - |
      | Two columns        |
      | Three columns      |
      | Four columns       |
    And the available options in the "Image ratio" select should be:
      | - Select a value - |
      | Landscape          |
      | Square             |
    # Check Illustration item with image paragraphs fields.
    And the following fields should be present "Image, Term, Description" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages                       |
      | Use existing media field is required |
      | Columns field is required.           |
      | Image ratio field is required.       |

    When I fill in "Title" with "Illustration list with images title" in the 1st "Illustration list with images" paragraph
    And I select "Three columns" from "Columns" in the 1st "Illustration list with images" paragraph
    And I select "Landscape" from "Image ratio" in the 1st "Illustration list with images" paragraph
    And I fill in "Use existing media" with "Image 1" in the 1st "Illustration item with image" paragraph
    And I fill in "Term" with "Illustration item with image term 1" in the 1st "Illustration item with image" paragraph
    And I fill in "Description" with "Illustration item with image description 1" in the 1st "Illustration item with image" paragraph
    And I press "Illustration item with image"
    And I fill in "Use existing media" with "Image 2" in the 2nd "Illustration item with image" paragraph
    And I fill in "Term" with "Illustration item with image term 2" in the 2nd "Illustration item with image" paragraph
    And I fill in "Description" with "Illustration item with image description 2" in the 2nd "Illustration item with image" paragraph
    And I press "Save"
    Then I should see the heading "Illustration list with images paragraph test page"
    And I should see the text "Illustration list with images title"
    And I should see the image "example_1.jpeg"
    And I should see the text "Illustration item with image term 1"
    And I should see the text "Illustration item with image description 1"
    And I should see the image "example_1_0.jpeg"
    And I should see the text "Illustration item with image term 2"
    And I should see the text "Illustration item with image description 2"
    And I should see the text "Three columns"
    And I should see the text "Landscape"
    And I should see the text "Off"

    # Re-edit the page and verify the Vertical variant.
    When I click "Edit"
    And I select "Vertical" from "Variant"
    And I press "Change variant"
    Then the following field should be present "Alternating background" in the "demo paragraphs element" region
    And the following field should not be present "Columns" in the "demo paragraphs element" region

    When I check "Alternating background"
    And I press "Save"
    Then I should see the text "On"
    And I should not see the text "Off"