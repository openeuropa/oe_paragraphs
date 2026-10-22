<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the quote paragraph.
 *
 * @group batch1
 */
class QuoteParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a quote paragraph.
   */
  public function testQuoteParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Quote paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Quote');
    $this->assertFieldsPresent(['Quote text', 'Attribution'], self::DEMO_PARAGRAPHS_ELEMENT);
    // The quote paragraph has no variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Quote text field is required',
      'Attribution field is required',
    ]);

    $this->fillParagraphField('Quote', 1, 'Quote text', 'This quote is awesome');
    $this->fillParagraphField('Quote', 1, 'Attribution', 'Literally nobody');
    $page->pressButton('Save');
    $this->assertHeading('Quote paragraph test page');
    $this->assertTextsPresent(['This quote is awesome', 'Literally nobody']);
  }

}
