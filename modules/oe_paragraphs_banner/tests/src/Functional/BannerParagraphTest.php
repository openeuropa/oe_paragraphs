<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_banner\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the banner paragraph.
 *
 * @group batch3
 */
class BannerParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a banner paragraph and its variants.
   */
  public function testBannerParagraph(): void {
    $this->createImageMedia('Image');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'edit any oe_demo_landing_page content',
      'create image media',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Banner test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Banner');
    $this->assertSelectOptions('Variant', [
      'Default',
      'Image banner',
      'Image shade banner',
      'Primary banner',
    ]);
    $this->assertSelectOptions('Alignment', ['Left', 'Centered']);
    $this->assertSelectOptions('Size', [
      'Small (5:1)',
      'Medium (4:1)',
      'Large (3:1)',
    ]);

    // Test the fields in each variant.
    $variants = [
      'Default' => [
        'present' => ['Alignment', 'Size', 'Title', 'Description', 'URL', 'Link text', 'Display as full width'],
        'not present' => ['Banner type', 'Use existing media'],
        'errors' => [
          'Description field is required',
          'Link text field is required if there is URL input.',
        ],
      ],
      'Primary banner' => [
        'present' => ['Alignment', 'Size', 'Title', 'Description', 'URL', 'Link text', 'Display as full width'],
        'not present' => ['Banner type', 'Use existing media'],
        'errors' => [
          'Description field is required',
          'Link text field is required if there is URL input.',
        ],
      ],
      'Image banner' => [
        'present' => [
          'Alignment',
          'Size',
          'Title',
          'Description',
          'URL',
          'Link text',
          'Use existing media',
          'Display as full width',
        ],
        'not present' => ['Banner type'],
        'errors' => [
          'Description field is required',
          'Use existing media field is required',
          'Link text field is required if there is URL input.',
        ],
      ],
      'Image shade banner' => [
        'present' => [
          'Alignment',
          'Size',
          'Title',
          'Description',
          'URL',
          'Link text',
          'Use existing media',
          'Display as full width',
        ],
        'not present' => ['Banner type'],
        'errors' => [
          'Description field is required',
          'Use existing media field is required',
          'Link text field is required if there is URL input.',
        ],
      ],
    ];
    foreach ($variants as $variant => $expected) {
      // The default variant is shown when the paragraph is added.
      if ($variant !== 'Default') {
        $page->selectFieldOption('Variant', $variant);
        $page->pressButton('Change variant');
      }
      $this->assertFieldsPresent($expected['present'], self::DEMO_PARAGRAPHS_ELEMENT);
      $this->assertFieldsNotPresent($expected['not present'], self::DEMO_PARAGRAPHS_ELEMENT);
      if ($variant === 'Default') {
        $page->fillField('URL', 'https://example.com');
      }
      $page->pressButton('Save');
      $this->assertErrorMessages($expected['errors']);
    }

    $page->selectFieldOption('Alignment', 'Centered');
    $page->selectFieldOption('Size', 'Small (5:1)');
    $this->fillParagraphField('Banner', 1, 'Title', 'Banner title');
    $page->fillField('Description', 'Description');
    $page->fillField('Link text', 'Example');
    $page->fillField('Use existing media', 'Image');
    $page->pressButton('Save');
    $this->assertHeading('Banner test page');
    $assert_session->pageTextContains('Description');
    $assert_session->linkExists('Example');
    $this->assertImagePresent('example_1.jpeg');
    $this->assertTextsPresent(['Small (5:1)', 'Centered']);
  }

}
