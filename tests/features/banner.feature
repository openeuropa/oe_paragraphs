@api
Feature: Banner paragraph.
  As a content editor
  I need to be able to use banner paragraphs
  so I can convey important messages to the audience.

  @cleanup:media
  Scenario: Banner paragraph creation.
    Given the following image:
      | name  | file           |
      | Image | example_1.jpeg |

    When I am logged in as a user with the "create oe_demo_landing_page content, access content, edit any oe_demo_landing_page content, create image media, access content overview" permission
    # Add Banner paragraph
    And I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Banner test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Banner"
    Then the available options in the "Variant" select should be:
      | Default            |
      | Image banner       |
      | Image shade banner |
      | Primary banner     |
    And the available options in the "Alignment" select should be:
      | Left               |
      | Centered           |
    And the available options in the "Size" select should be:
      | Small (5:1)        |
      | Medium (4:1)       |
      | Large (3:1)        |

    # Test the fields in the Default variant.
    And the following fields should be present "Alignment, Size, Title, Description, URL, Link text, Display as full width" in the "demo paragraphs element" region
    And the following fields should not be present "Banner type, Use existing media" in the "demo paragraphs element" region
    And I fill in "URL" with "https://example.com"
    When I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | Description field is required                      |
      | Link text field is required if there is URL input. |

    # Test the fields in the Primary banner variant.
    When I select "Primary banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Alignment, Size, Title, Description, URL, Link text, Display as full width" in the "demo paragraphs element" region
    And the following fields should not be present "Banner type, Use existing media" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | Description field is required                      |
      | Link text field is required if there is URL input. |

    # Test the fields in the Image banner variant.
    When I select "Image banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Alignment, Size, Title, Description, URL, Link text, Use existing media, Display as full width" in the "demo paragraphs element" region
    And the following fields should not be present "Banner type" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | Description field is required                      |
      | Use existing media field is required               |
      | Link text field is required if there is URL input. |

    # Test the fields in the Image shade banner variant.
    When I select "Image shade banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Alignment, Size, Title, Description, URL, Link text, Use existing media, Display as full width" in the "demo paragraphs element" region
    And the following fields should not be present "Banner type" in the "demo paragraphs element" region
    When I press "Save"
    Then I should see the following error messages:
      | error messages                                     |
      | Description field is required                      |
      | Use existing media field is required               |
      | Link text field is required if there is URL input. |

    When I select "Centered" from "Alignment"
    And I select "Small (5:1)" from "Size"
    And I fill in "Title" with "Banner title" in the 1st "Banner" paragraph
    And I fill in "Description" with "Description"
    And I fill in "Link text" with "Example"
    And I fill in "Use existing media" with "Image"
    And I press "Save"
    Then I should see the heading "Banner test page"
    And I should see the text "Description"
    And I should see the link "Example"
    And I should see the image "example_1.jpeg"
    And I should see the text "Small (5:1)"
    And I should see the text "Centered"
