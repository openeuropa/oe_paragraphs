@api
Feature: Media paragraph.
  As a content editor
  I need to be able to use Media paragraphs
  so that I can add media items to the content.

  @cleanup:node @cleanup:media
  Scenario: Media paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content, create image media, create video_iframe media, create av_portal_video media, use text format oe_media_iframe" permissions
    # Create an "Image" media.
    When I go to "the image creation page"
    And I fill in "Name" with "My Image 1"
    And I attach the file "example_1.jpeg" to "Image"
    And I press "Upload"
    And I fill in "Alternative text" with "Image Alt Text 1"
    And I press "Save"

    # Create a "Video iframe" media.
    When I go to "the Video iframe creation page"
    Then I should see the heading "Add Video iframe"
    And I fill in "Name" with "My Iframe video"
    And I fill in "Iframe" with "<iframe src=\"http://example.com\"></iframe>"
    And I press "Save"

    # Create an "AV Portal video" media
    When I go to "the avportal video creation page"
    And I fill in "Media AV Portal Video" with "https://audiovisual.ec.europa.eu/en/video/I-162747"
    And I press "Save"
    # Create a node with a Media paragraph.
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Media paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Media"
    Then the following field should be present "Use existing media" in the "demo paragraphs element" region
    # Create a Media paragraph with an image.
    When I fill in "Use existing media" with "My Image 1"
    And I press "Save"
    Then I should see the heading "Media paragraph test page"
    And I should see the image "example_1.jpeg"
    # Edit the paragraph to use the Video iframe media.
    When I click "Edit"
    And I fill in "Use existing media" with "My Iframe video"
    And I press "Save"
    And the response should contain "<iframe src=\"http://example.com\"></iframe>"
    # Edit the paragraph to use the AV Portal video media.
    When I click "Edit"
    And I fill in "Use existing media" with "Midday press briefing from 25/10/2018"
    And I press "Save"
    Then I should see the AV Portal video "Midday press briefing from 25/10/2018"

