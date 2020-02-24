@api
Feature: Banner paragraph.
  As a content editor
  I need to be able to use banner paragraphs
  so that I see snippets of information.

  Scenario: Banner paragraph creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Banner test page"
    And I press "Add Banner"
    Then the available options in the "Variant" select should be:
      | Default             |
      | Image banner        |
      | Image shade banner  |
      | Primary banner      |
    And the available options in the "Banner type" select should be:
      | - Select a value -         |
      | Page banner, centered      |
      | Hero banner, centered      |
      | Default, aligned left      |

    # Test the fields in the Default variant.
    And the following fields should be present "Banner type, Title, Description, URL, Link text"
    And the following fields should not be present "Background image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages                   |
      | Description field is required    |
      | Banner type field is required    |
      | URL field is required            |
      | Link text field is required      |

    # Test the fields in the Image banner variant.
    When I select "Image banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Banner type, Title, Description, URL, Link text, Background image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages                     |
      | Description field is required      |
      | Banner type field is required      |
      | URL field is required              |
      | Link text field is required        |
      | Background image field is required |

    # Test the fields in the Image shade banner variant.
    When I select "Image shade banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Banner type, Title, Description, URL, Link text, Background image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages                     |
      | Description field is required      |
      | Banner type field is required      |
      | URL field is required              |
      | Link text field is required        |
      | Background image field is required |

    # Test the fields in the Primary banner variant.
    When I select "Primary banner" from "Variant"
    And I press "Change variant"
    Then the following fields should be present "Banner type, Title, Description, URL, Link text"
    And the following fields should not be present "Background image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages                     |
      | Description field is required      |
      | Banner type field is required      |
      | URL field is required              |
      | Link text field is required        |

    When I select "Page banner, centered" from "Banner type"
    And I fill in "Title" with "Banner title" in the 1st "Banner" paragraph
    And I fill in "Description" with "Description"
    And I fill in "URL" with "https://example.com"
    And I fill in "Link text" with "Example"
    And I press "Save"
    Then I should see the heading "Banner test page"
    And I should see the text "Description"
    And I should see the link "Example"
