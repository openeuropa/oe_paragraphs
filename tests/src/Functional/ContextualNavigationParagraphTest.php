<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the contextual navigation paragraph.
 *
 * @group batch1
 */
class ContextualNavigationParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a contextual navigation paragraph.
   */
  public function testContextualNavigationParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Contextual navigation test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Contextual navigation');
    $this->assertFieldsPresent([
      'Navigation label',
      'URL',
      'Link text',
      'Limit',
      'More label',
    ], self::DEMO_PARAGRAPHS_ELEMENT);
    // The contextual navigation paragraph has no variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'URL field is required',
      'Link text field is required',
    ]);

    $this->fillParagraphField('Contextual navigation', 1, 'URL', '<front>');
    $this->fillParagraphField('Contextual navigation', 1, 'Link text', 'Back to the home');
    $page->pressButton('Save');
    $this->assertHeading('Contextual navigation test page');

    $this->clickLink('Edit');
    $this->fillParagraphField('Contextual navigation', 1, 'Navigation label', 'Quick links');
    $this->fillParagraphField('Contextual navigation', 1, 'Limit', '97');
    $this->fillParagraphField('Contextual navigation', 1, 'More label', 'See more');
    $page->pressButton('Save');
    $this->assertHeading('Contextual navigation test page');
    $this->assertTextsPresent(['Quick links', '97', 'See more']);
    $assert_session->linkExists('Back to the home');

    // Add another link.
    $this->clickLink('Edit');
    $this->fillNthField('URL', 2, '/admin/content');
    $this->fillNthField('Link text', 2, 'Content listing');
    $page->pressButton('Save');
    $this->assertHeading('Contextual navigation test page');
    $this->assertTextsPresent(['Quick links', '97', 'See more']);
    $assert_session->linkExists('Back to the home');
    $assert_session->linkExists('Content listing');
  }

}
