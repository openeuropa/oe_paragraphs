@api
Feature: Create a landing page
  In order to create landing pages with rich layouts
  As a content editor
  I need to be able to use the available paragraph types in my landing pages

  Scenario: All supported paragraph types are available
    Then the following paragraph types are available for "demo landing page" content:
      | Accordion                     |
      | Chart                         |
      | Carousel                      |
      | Content row                   |
      | Gallery                       |
      | Iframe                        |
      | Links block                   |
      | Listing item                  |
      | Listing item block            |
      | Quote                         |
      | Rich text                     |
      | Media                         |
      | Banner                        |
      | Contextual navigation         |
      | Social media follow           |
      | Text with Featured media      |
      | Description list              |
      | Facts and figures             |
      | Timeline                      |
      | Map                           |
      | Social feed                   |
      | Document                      |
      | Contact                       |
      | Illustration list with flags  |
      | Illustration list with icons  |
      | Illustration list with images |
