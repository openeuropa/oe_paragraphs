<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the rich text paragraph.
 *
 * @group batch1
 */
class RichTextParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a rich text paragraph.
   */
  public function testRichTextParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Rich text paragraph test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Rich text');
    $this->assertFieldsPresent(['Title', 'Text'], self::DEMO_PARAGRAPHS_ELEMENT);
    // The rich text paragraph has no variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages(['Text field is required']);
    $this->fillParagraphField('Rich text', 1, 'Text', 'A sample text for the page.');
    $page->pressButton('Save');
    $this->assertHeading('Rich text paragraph test');
    $this->assertSession()->pageTextContains('A sample text for the page.');

    $this->clickLink('Edit');
    $this->fillParagraphField('Rich text', 1, 'Title', 'Paragraph title');
    $page->pressButton('Save');
    $this->assertHeading('Rich text paragraph test');
    $this->assertTextsPresent(['Paragraph title', 'A sample text for the page.']);
  }

}
