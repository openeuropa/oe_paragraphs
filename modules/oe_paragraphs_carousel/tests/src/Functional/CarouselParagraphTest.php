<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_carousel\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the carousel paragraph.
 *
 * @group batch3
 */
class CarouselParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a carousel paragraph.
   */
  public function testCarouselParagraph(): void {
    $this->createImageMedia('Image 1');
    $this->createImageMedia('Image 2');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'edit any oe_demo_landing_page content',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Carousel test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Carousel');
    // Assert all the fields are present.
    $this->assertFieldsPresent([
      'Title',
      'Size',
      'Description',
      'URL',
      'Link text',
      'Use existing media',
    ], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Size', [
      'Small (5:1)',
      'Medium (4:1)',
      'Large (3:1)',
    ]);
    // Assert the required fields.
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Title field is required.',
      'Use existing media field is required.',
    ]);

    // The first "Title" field is the one of the node.
    $this->fillNthField('Title', 2, 'First item');
    $this->fillNthField('Description', 1, 'First item - description');
    $this->fillNthField('URL', 1, 'https://example1.com');
    $this->fillNthField('Link text', 1, 'CTA 1');
    $this->fillNthField('Use existing media', 1, 'Image 1');
    $page->pressButton('Save');
    // Assert the custom validation for the items cardinality.
    $this->assertErrorMessages(['The Carousel paragraph should contain at least 2 items.']);

    // Add a second carousel item.
    $page->pressButton('Add Carousel item');
    $this->fillNthField('Title', 3, 'Second item');
    $this->fillNthField('Description', 2, 'Second item - description');
    $this->fillNthField('URL', 2, 'https://example2.com');
    $this->fillNthField('Link text', 2, 'CTA 2');
    $this->fillNthField('Use existing media', 2, 'Image 2');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Carousel test page has been created.', 'status');
    $this->assertTextsPresent([
      'Medium (4:1)',
      'First item',
      'First item - description',
      'Second item',
      'Second item - description',
    ]);
    $assert_session->linkExists('CTA 1');
    $assert_session->linkExists('CTA 2');
    $this->assertImagePresent('example_1.jpeg');
  }

}
