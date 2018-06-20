@api
Feature: Create a landing page
  In order to create landing pages with rich layouts
  As a content editor
  I need to be able to use the available paragraph types in my landing pages

  Scenario: All supported paragraph types are available
    Then the following paragraph types are available for "demo landing page" content:
      | Accordion       |
      | Content row     |
      | Links block     |
      | List item       |
      | List item block |
      | Quote           |
      | Rich text       |
