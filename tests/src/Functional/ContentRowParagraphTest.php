<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the content row paragraph.
 *
 * @group batch1
 */
class ContentRowParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a content row paragraph.
   */
  public function testContentRowParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Content row test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Content row');
    $this->assertFieldsPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Verify the fields displayed in the "default" variant.
    $this->assertFieldsNotPresent(['Navigation title'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertParagraphReferenceableTypes('Content row', 1, 'Paragraphs', [
      'Accordion',
      'Contextual navigation',
      'Social media follow',
      'Links block',
      'Listing item block',
      'Quote',
      'Rich text',
    ]);

    $this->selectParagraphOption('Content row', 1, 'Variant', 'Inpage navigation');
    $page->pressButton('Change variant');
    $this->assertFieldsPresent(['Navigation title'], self::DEMO_PARAGRAPHS_ELEMENT);
    // No fields are required.
    $page->pressButton('Save');
    $this->assertHeading('Content row test page');

    $this->clickLink('Edit');
    $this->fillParagraphField('Content row', 1, 'Navigation title', 'Quick links');
    $page->pressButton('Save');
    $this->assertHeading('Content row test page');
    $this->assertSession()->pageTextContains('Navigation title');
  }

}
