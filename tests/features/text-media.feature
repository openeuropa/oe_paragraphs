@api
Feature: Text with featured media paragraph.
  As a content editor
  I need to be able to use Text with featured media paragraphs
  so that I can add featured media together with explanatory text.

  @cleanup:media @remote-video-services
  Scenario: Text with featured media paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content, create image media, create remote_video media, create av_portal_video media, create webtools_chart media, create webtools_countdown media, create webtools_map media, create webtools_social_feed media,access content overview" permission

    # Create an "Image" media.
    When I go to "the image creation page"
    And I fill in "Name" with "My Image 1"
    And I attach the file "example_1.jpeg" to "Image"
    And I press "Upload"
    And I fill in "Alternative text" with "Image Alt Text 1"
    Then I press "Save"

    # Create an "Remote video" media.
    When I go to "the remote video creation page"
    And I fill in "Remote video URL" with "https://www.youtube.com/watch?v=1-g73ty9v04"
    Then I press "Save"

    # Create an "AV Portal video" media
    When I go to "the avportal video creation page"
    And I fill in "Media AV Portal Video" with "https://audiovisual.ec.europa.eu/en/video/I-162747"
    Then I press "Save"

    # Create an "Webtools chart" media
    When I visit "the Webtools chart creation page"
    And I fill in "Name" with "Basic chart"
    And I fill in "Webtools chart snippet" with "{\"service\":\"charts\",\"data\":{\"series\":[{\"name\":\"Y\",\"data\":[{\"name\":\"1\",\"y\":0.5}]}]},\"provider\":\"highcharts\"}"
    Then I press "Save"

    # Create an "Webtools map" media
    When I visit "the Webtools map creation page"
    And I fill in "Name" with "World map"
    And I fill in "Webtools map snippet" with "{\"service\": \"map\"}"
    Then I press "Save"

    # Create an "Webtools social feed" media
    When I visit "the Webtools social feed creation page"
    And I fill in "Name" with "My social feed"
    And I fill in "Webtools social feed snippet" with "{\"service\":\"smk\",\"type\":\"list\",\"slug\":\"ec-spokespersons\"}"
    Then I press "Save"

    # Create an "Webtools countdown" media
    When I visit "the Webtools countdown creation page"
    And I fill in "Name" with "My countdown"
    And I fill in "Webtools countdown snippet" with "{\"service\":\"cdown\",\"date\":\"30/04/2052\",\"timezone\":\"Etc/Universal\",\"title\":\"Event countdown\",\"end\":true,\"show\":{\"day\":true,\"time\":true}}"
    Then I press "Save"

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Text with Featured media paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Text with Featured media"
    Then the following fields should be present "Heading, Title, Use existing media, Caption, Full text, Highlighted" in the "demo paragraphs element" region
    And the available options in the "Variant" select should be:
      | Text on the left, simple call to action    |
      | Text on the left, featured call to action  |
      | Text on the right, featured call to action |
      | Text on the right, simple call to action   |

    # Create a Text with featured media paragraph with an image.
    When I fill in "Heading" with "Heading text" in the 1st "Text with Featured media" paragraph
    And I fill in "Title" with "Title text" in the 1st "Text with Featured media" paragraph
    And I fill in "Use existing media" with "My Image 1"
    And I fill in "Caption" with "Caption text" in the 1st "Text with Featured media" paragraph
    And I fill in "Full text" with "Featured text" in the 1st "Text with Featured media" paragraph
    And I fill in "URL" with "http://example.com/link" in the 1st "Text with Featured media" paragraph
    And I fill in "Link text" with "Link title" in the 1st "Text with Featured media" paragraph
    And I press "Save"
    Then I should see the heading "Text with Featured media paragraph test page"
    And I should see the text "Heading text"
    And I should see the text "Title text"
    And I should see the image "example_1.jpeg"
    And I should see the text "Caption text"
    And I should see the text "Featured text"
    And the response should contain "http://example.com/link"
    And I should see the link "Link title"

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

    # Use an webtools chart on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "Basic chart"
    And I press "Save"
    Then I should see the Webtools chart "Basic chart" on the page

    # Use an webtools map on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "World map"
    And I press "Save"
    Then I should see the Webtools map "World map" on the page

    # Use an webtools social feed on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "My social feed"
    And I press "Save"
    Then I should see the Webtools social feed "My social feed" on the page

    # Use an webtools countdown on the Text with featured media paragraph.
    When I click "Edit"
    And I fill in "Use existing media" with "My countdown"
    And I press "Save"
    Then I should see the Webtools countdown "My countdown" on the page

    # Change of variants to ensure presence of the fields.
    When I click "Edit"
    And I select "Text on the left, featured call to action" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Heading, Title, Use existing media, Caption, Full text, URL, Link text, Highlighted" in the "demo paragraphs element" region

    And I select "Text on the left, simple call to action" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Heading, Title, Use existing media, Caption, Full text, URL, Link text, Highlighted" in the "demo paragraphs element" region

    And I select "Text on the right, featured call to action" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Heading, Title, Use existing media, Caption, Full text, URL, Link text, Highlighted" in the "demo paragraphs element" region

    And I select "Text on the right, simple call to action" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Heading, Title, Use existing media, Caption, Full text, URL, Link text, Highlighted" in the "demo paragraphs element" region

    # Verify that the variant has been kept.
    When I press "Save"
    And I click "Edit"
    Then the option "Text on the right, simple call to action" should be selected in the "Variant" select of the 1st "Text with Featured media" paragraph

