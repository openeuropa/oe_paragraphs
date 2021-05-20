@api
Feature: Iframe paragraph.
  As a content editor
  I need to be able to use Iframe paragraphs
  so that I can add Iframe media together with the title and attributes.

  @cleanup:media
  Scenario: Iframe paragraph creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, edit any oe_demo_landing_page content, access content, create iframe media, use text format oe_media_iframe" permission

    # Create an "Iframe" media.
    When I go to "the iframe creation page"
    And I fill in "Name" with "My Iframe"
    And I fill in "Iframe" with "<iframe src=\"http://example.com\"></iframe>"
    And I press "Save"

    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Iframe paragraph test page"
    And I press "Add Iframe"
    Then the following fields should be present "Use existing media, Full width, Title"

    # Create an Iframe paragraph.
    When I fill in "Use existing media" with "My Iframe"
    And I check "Full width"
    And I fill in "Title" with "Title text" in the 1st "Iframe" paragraph
    And I press "Save"
    Then I should see the heading "Iframe paragraph test page"
    And the response should contain "<iframe src=\"http://example.com\"></iframe>"
    And I should see the text "Title text"

