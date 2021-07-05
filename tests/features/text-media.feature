@api
Feature: Text with featured media paragraph.
  As a content editor
  I need to be able to use Text with featured media paragraphs
  so that I can add featured media together with explanatory text.

  @cleanup:media
  Scenario: Text with featured media paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content, create image media, create remote_video media, create av_portal_video media" permission

    # Create an "Image" media.
    When I go to "the image creation page"
    And I fill in "Name" with "My Image 1"
    And I attach the file "example_1.jpeg" to "Image"
    And I press "Upload"
    And I fill in "Alternative text" with "Image Alt Text 1"
    And I press "Save"

    # Create an "Remote video" media.
    When I go to "the remote video creation page"
    And I fill in "Remote video URL" with "https://www.youtube.com/watch?v=1-g73ty9v04"
    And I press "Save"

    # Create an "AV Portal video" media
    When I go to "the avportal video creation page"
    And I fill in "Media AV Portal Video" with "https://audiovisual.ec.europa.eu/en/video/I-162747"
    And I press "Save"

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Text with Featured media paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Text with Featured media"
    Then the following fields should be present "Title, Use existing media, Caption, Full text" in the "demo paragraphs element" region

    # Create a Text with featured media paragraph with an image.
    When I fill in "Title" with "Title text" in the 1st "Text with Featured media" paragraph
    And I fill in "Use existing media" with "My Image 1"
    And I fill in "Caption" with "Caption text" in the 1st "Text with Featured media" paragraph
    And I fill in "Full text" with "Featured text" in the 1st "Text with Featured media" paragraph
    And I press "Save"
    Then I should see the heading "Text with Featured media paragraph test page"
    And I should see the text "Title text"
    And I should see the image "example_1.jpeg"
    And I should see the text "Caption text"
    And I should see the text "Featured text"

    # Use a remote video on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "Energy, let's save it!"
    And I press "Save"
    Then I should see the embedded video player for "https://www.youtube.com/watch?v=1-g73ty9v04"

    # Use an avportal video on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "Midday press briefing from 25/10/2018"
    And I press "Save"
    Then I should see the AV Portal video "Midday press briefing from 25/10/2018"

