@api
Feature: Document media paragraph.
  As a content editor
  I need to be able to use the Document paragraph
  so that I can attach documents to a content.
  
  @cleanup:media
  Scenario: Document creation.
    Given I am logged in as a user with the "create oe_demo_landing_page content, edit any oe_demo_landing_page content, access content overview, create document media, delete any document media, update any media" permission
    
    # Create a local "Document" media.
    When I go to "the document creation page"
    And I fill in "Name" with "My example local document 1"
    And I select "Local" from "File Type"
    And I attach the file "example.pdf" to "File"
    And I press "Save"
    Then I should see "Document My example local document 1 has been created."
    
    # Create a remote "Document" media.
    When I go to "the document creation page"
    And I fill in "Name" with "My example remote document 1"
    And I select "Remote" from "File Type"
    And I fill in "URL" with "http://www.africau.edu/images/default/sample.pdf"
    And I fill in "Link text" with "My example remote document 1"
    And I press "Save"
    Then I should see "Document My example remote document 1 has been created."
    
    # Add Local document to content.
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Local document paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Document"
    And I fill in "Use existing media" with "My example local document 1"
    And I press "Save"
    Then I should see the heading "Local document paragraph test page"
    And I should see the text "Document"
    And I should see the link "My example local document 1"
    
    # Add Remote document to content.
    When I go to "the content management page"
    And I click "Add content"
    And I fill in "Title" with "Remote document paragraph test page"
    And I fill in "Content owner" with "Committee on Agriculture and Rural Development"
    And I press "Add Document"
    And I fill in "Use existing media" with "My example remote document 1"
    And I press "Save"
    Then I should see the heading "Remote document paragraph test page"
    And I should see the text "Document"
    And I should see the link "My example remote document 1"

