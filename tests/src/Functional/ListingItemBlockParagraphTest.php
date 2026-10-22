<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the listing item block paragraph.
 *
 * @group batch2
 */
class ListingItemBlockParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a listing item block paragraph.
   */
  public function testListingItemBlockParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Listing item block test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Listing item block');
    // Remove the listing item that is shown by default in the page so we are
    // able to test the required fields of the listing item block itself.
    $this->pressParagraphActionsButton('Listing item', 1, 'Remove');
    $this->assertFieldsPresent(['Layout', 'Title', 'URL', 'Link text'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Layout', [
      '- Select a value -',
      'One column',
      'Two columns',
      'Three columns',
    ]);
    // This paragraph has no variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Only listing item paragraphs can be added to this paragraph.
    $this->assertParagraphReferenceableTypes('Listing item block', 1, 'Items', ['Listing item']);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Layout field is required',
      'Items field is required',
    ]);

    // Fill a single list item so that we meet the requirements.
    $this->selectParagraphOption('Listing item block', 1, 'Layout', 'One column');
    $page->pressButton('Add Listing item');
    $this->fillParagraphField('Listing item', 1, 'Link', 'http://example.org/1');
    $this->fillParagraphField('Listing item', 1, 'Title', 'Sample link');
    $page->pressButton('Save');
    $this->assertHeading('Listing item block test page');
    $assert_session->linkExists('http://example.org/1');
    $assert_session->pageTextContains('Sample link');

    $this->clickLink('Edit');
    $this->fillParagraphField('Listing item block', 1, 'URL', 'http://example.org/see-more');
    $this->fillParagraphField('Listing item block', 1, 'Link text', 'See more');
    $page->pressButton('Save');
    $this->assertHeading('Listing item block test page');
    $assert_session->linkExists('http://example.org/1');
    $assert_session->pageTextContains('Sample link');
    $assert_session->linkExists('See more');
  }

}
