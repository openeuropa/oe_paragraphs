@api
Feature: Listing item paragraph.
  As a content editor
  I need to be able to use listing item paragraphs
  so that I see snippets of information.

  Scenario: Listing item creation.
    Given I am logged in as a user with the "Editor" role
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Listing item test page"
    And I press "Add Listing item"
    Then the available options in the "Variant" select should be:
      | Default             |
      | Date                |
      | Highlight           |
      | Thumbnail primary   |
      | Thumbnail secondary |

    # Test the fields in the "default" variant.
    # The title field check here is ambiguous as another title field is
    # present in the page. The required fields step will assure that
    # a title field is actually present in the paragraph.
    And the following fields should be present "Link, Title, Description, Meta"
    And the following fields should not be present "Day, Month, Year, Image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Link field is required  |
      | Title field is required |

    # Test the fields in the "date" variant.
    When I select "Date" from "Variant" in the 1st "Listing item" paragraph
    And I press "Change variant"
    Then the following fields should be present "Link, Title, Description, Day, Month, Year, Meta"
    And the following fields should not be present "Image"
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Link field is required  |
      | Title field is required |

    # Test the fields in the "highlight" variant.
    When I select "Highlight" from "Variant" in the 1st "Listing item" paragraph
    And I press "Change variant"
    Then the following fields should be present "Link, Title, Description, Image, Meta"
    And the following fields should not be present "Day, Month, Year"
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Link field is required  |
      | Title field is required |

    # Test the fields in the "thumbnail primary" variant.
    When I select "Thumbnail primary" from "Variant" in the 1st "Listing item" paragraph
    And I press "Change variant"
    Then the following fields should be present "Link, Title, Image, Meta"
    And the following fields should not be present "Day, Month, Year, Description"
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Link field is required  |
      | Title field is required |

    # Test the fields in the "thumbnail secondary" variant.
    When I select "Thumbnail secondary" from "Variant" in the 1st "Listing item" paragraph
    And I press "Change variant"
    Then the following fields should be present "Link, Title, Image, Meta, Description"
    And the following fields should not be present "Day, Month, Year"
    When I press "Save"
    Then I should see the following error messages:
      | error messages          |
      | Link field is required  |
      | Title field is required |

    When I select "Default" from "Variant" in the 1st "Listing item" paragraph
    And I press "Change variant"
    And I fill in "Link" with "http://example.org" in the 1st "Listing item" paragraph
    And I fill in "Title" with "Sample link" in the 1st "Listing item" paragraph
    And I press "Save"
    Then I should see the heading "Listing item test page"
    And I should see the text "Sample link"
    And I should see the link "http://example.org"
