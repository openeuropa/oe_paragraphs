@api
Feature: Illustration list with images and Illustration item with image paragraphs.
  As a content editor
  I need to be able to use Illustration list with images and Illustration item with image paragraphs
  so I can add items with image to the content.

  @cleanup:media
  Scenario: Illustration list with images paragraph creation.
    Given I am logged in as a user with the "access content, create oe_demo_landing_page content, edit any oe_demo_landing_page content, create av_portal_photo media, access content overview" permission
    And the following images:
      | name    | file           |
      | Image 1 | example_1.jpeg |

    # Create an "AV Portal photo" media
    When I go to "the avportal photo creation page"
    And I fill in "Media AV Portal Photo" with "https://audiovisual.ec.europa.eu/en/photo/P-038924~2F00-15"
    And I press "Save"
    Then I should see the text "AV Portal Photo Euro with miniature figurines has been created."

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Illustration list with images paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Illustration list with images"
    # Check Illustration list with images paragraph fields.
    Then the following fields should be present "Variant, Title, Columns, Center the content, Size" in the "demo paragraphs element" region
    And the available options in the "Columns" select should be:
      | - Select a value - |
      | Two columns        |
      | Three columns      |
      | Four columns       |
    And the available options in the "Image ratio" select should be:
      | - Select a value - |
      | Landscape          |
      | Square             |
    And the available options in the "Size" select should be:
      | - Select a value - |
      | Small              |
      | Medium             |
      | Large              |
    # Check Illustration item with image paragraphs fields.
    And the following fields should be present "Image, Highlight, Title, Body" in the "demo paragraphs element" region

    When I press "Save"
    Then I should see the following error messages:
      | error messages                       |
      | Use existing media field is required |
      | Columns field is required.           |
      | Image ratio field is required.       |
      | Size field is required.              |

    When I fill in "Title" with "Illustration list with images title" in the 1st "Illustration list with images" paragraph
    And I select "Large" from "Size" in the 1st "Illustration list with images" paragraph
    And I select "Three columns" from "Columns" in the 1st "Illustration list with images" paragraph
    And I select "Landscape" from "Image ratio" in the 1st "Illustration list with images" paragraph
    And I fill in "Use existing media" with "Image 1" in the 1st "Illustration item with image" paragraph
    And I fill in "Highlight" with "Highlighted image term 1" in the 1st "Illustration item with image" paragraph
    And I fill in "Title" with "Illustration item with image term 1" in the 1st "Illustration item with image" paragraph
    And I fill in "Body" with "Illustration item with image description 1" in the 1st "Illustration item with image" paragraph
    And I press "Illustration item with image"
    And I fill in "Use existing media" with "Euro with miniature figurines" in the 2nd "Illustration item with image" paragraph
    And I fill in "Highlight" with "Highlighted image term 2" in the 2nd "Illustration item with image" paragraph
    And I fill in "Title" with "Illustration item with image term 2" in the 2nd "Illustration item with image" paragraph
    And I fill in "Body" with "Illustration item with image description 2" in the 2nd "Illustration item with image" paragraph
    And I press "Save"
    Then I should see the heading "Illustration list with images paragraph test page"
    And I should see the text "Illustration list with images title"
    And I should see the text "Center the content"
    And I should see the text "Size"
    And I should see the text "Large"
    And I should see the image "example_1.jpeg"
    And I should see the text "Highlighted image term 1"
    And I should see the text "Illustration item with image term 1"
    And I should see the text "Illustration item with image description 1"
    And I should see the text "Highlighted image term 2"
    And I should see the text "Illustration item with image term 2"
    And I should see the text "Illustration item with image description 2"
    And I should see the text "Three columns"
    And I should see the text "Landscape"
    And I should see the text "Off"

    # Re-edit the page and verify the Vertical variant.
    When I click "Edit"
    And I select "Vertical" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Variant, Title, Highlight, Alternating background, Center the content, Size" in the "demo paragraphs element" region
    And the following field should not be present "Columns" in the "demo paragraphs element" region

    When I check "Alternating background"
    And I check "Center the content"
    And I press "Save"
    Then I should see the text "On"
    And I should not see the text "Off"
