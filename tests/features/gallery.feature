@api
Feature: Gallery paragraph.
  As a content editor
  I need to be able to use gallery paragraphs
  so I can create collections of images and videos.

  @cleanup:media
  Scenario: Gallery paragraph creation.
    Given the following remote video:
      | url                                         |
      | https://www.youtube.com/watch?v=YaUGTOnf6k0 |
    And the following image:
      | name    | file           |
      | Image 1 | example_1.jpeg |
    And the following AV Portal photo:
      | url                                                        |
      | https://audiovisual.ec.europa.eu/en/photo/P-038924~2F00-15 |
    And the following AV Portal video:
      | url                                                |
      | https://audiovisual.ec.europa.eu/en/video/I-163162 |
    And the following document:
      | name       | file          |
      | Document 1 | example_1.pdf |

    When I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content, access content overview" permission
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Gallery test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    # And I press "List additional actions"
    And I press "Add Gallery"
    # Assert all the fields are present.
    Then the following fields should be present "Title, Description, Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    # Assert required fields.
    Then I should see the following error messages:
      | error messages                        |
      | Use existing media field is required. |

    When I fill in "Title" with "Gallery test page" in the 1st Gallery paragraph
    And I fill in "Description" with "A very long description for the gallery paragraph." in the 1st Gallery paragraph
    And I fill in the 1st "Use existing media" with "Plant health in the EU"
    And I press "Add another item" in the 1st Gallery paragraph
    And I fill in "Medias (value 2)" with "Image 1"
    And I press "Add another item" in the 1st Gallery paragraph
    And I fill in "Medias (value 3)" with "Euro with miniature figurines"
    And I press "Add another item" in the 1st Gallery paragraph
    And I fill in "Medias (value 4)" with " Economic and Financial Affairs Council - Arrivals"
    And I press "Save"
    Then I should see the success message "Demo landing page Gallery test page has been created."
    And I should see the embedded video player for "https://www.youtube.com/watch?v=YaUGTOnf6k0"
    And I should see the image "example_1.jpeg"
    And I should see the AV Portal video " Economic and Financial Affairs Council - Arrivals"
    And I should see the AV Portal photo "Euro with miniature figurines" with source "//ec.europa.eu/avservices/avs/files/video6/repository/prod/photo/store/store2/4/P038924-352937.jpg"

    When I click "Edit"
    And I fill in "Medias (value 5)" with "Document 1"
    And I press "Save"
    # Documents cannot be referenced. More bundle medias cannot be referenced, but we cannot assert them all.
    # This is just to check that a minimum filtering is applied.
    Then I should see the error message 'There are no media items matching "Document 1".'
