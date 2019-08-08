@api
Feature: Create a landing page
  In order to create landing pages with rich layouts
  As a content editor
  I need to be able to use the available paragraph types in my landing pages

  Scenario: All supported paragraph types are available
    Then the following paragraph types are available for "demo landing page" content:
      | Rich text             |
      | Links block           |
      | Accordion             |
      | Quote                 |
      | Listing item          |
      | Content row           |
      | Listing item block    |
      | Contextual navigation |
