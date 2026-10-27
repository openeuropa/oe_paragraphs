<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the listing item paragraph.
 *
 * @group batch2
 */
class ListingItemParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a listing item paragraph and its variants.
   */
  public function testListingItemParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Listing item test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Listing item');
    $this->assertSelectOptions('Variant', [
      'Default',
      'Date',
      'Highlight',
      'Thumbnail primary',
      'Thumbnail secondary',
    ]);

    // Assert the fields shown and hidden in each variant.
    // The title field check is ambiguous as another title field is present in
    // the page. The required fields assertions make sure that a title field is
    // actually present in the paragraph.
    $variants = [
      'Default' => [
        'present' => ['Link', 'Title', 'Description', 'Meta'],
        'not present' => ['Day', 'Month', 'Year', 'Image'],
      ],
      'Date' => [
        'present' => ['Link', 'Title', 'Description', 'Day', 'Month', 'Year', 'Meta'],
        'not present' => ['Image'],
      ],
      'Highlight' => [
        'present' => ['Link', 'Title', 'Description', 'Image', 'Meta'],
        'not present' => ['Day', 'Month', 'Year'],
      ],
      'Thumbnail primary' => [
        'present' => ['Link', 'Title', 'Description', 'Image', 'Meta'],
        'not present' => ['Day', 'Month', 'Year'],
      ],
      'Thumbnail secondary' => [
        'present' => ['Link', 'Title', 'Image', 'Meta', 'Description'],
        'not present' => ['Day', 'Month', 'Year'],
      ],
    ];
    foreach ($variants as $variant => $fields) {
      // The default variant is shown when the paragraph is added.
      if ($variant !== 'Default') {
        $this->selectParagraphOption('Listing item', 1, 'Variant', $variant);
        $page->pressButton('Change variant');
      }
      $this->assertFieldsPresent($fields['present'], self::DEMO_PARAGRAPHS_ELEMENT);
      $this->assertFieldsNotPresent($fields['not present'], self::DEMO_PARAGRAPHS_ELEMENT);
      $page->pressButton('Save');
      $this->assertErrorMessages([
        'Link field is required',
        'Title field is required',
      ]);
    }

    $this->selectParagraphOption('Listing item', 1, 'Variant', 'Default');
    $page->pressButton('Change variant');
    $this->fillParagraphField('Listing item', 1, 'Link', 'http://example.org');
    $this->fillParagraphField('Listing item', 1, 'Title', 'Sample link');
    $page->pressButton('Save');
    $this->assertHeading('Listing item test page');
    $this->assertSession()->pageTextContains('Sample link');
    $this->assertSession()->linkExists('http://example.org');
  }

}
