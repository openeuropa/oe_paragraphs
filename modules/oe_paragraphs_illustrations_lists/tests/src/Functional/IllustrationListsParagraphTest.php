<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_illustrations_lists\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the illustration list paragraphs.
 *
 * @group batch3
 */
class IllustrationListsParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the illustration list with flags paragraph.
   */
  public function testIllustrationListWithFlags(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Illustration list with flags paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Illustration list with flags');
    // Check the illustration list with flags paragraph fields.
    $this->assertFieldsPresent(['Variant', 'Title', 'Columns', 'Center the content'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Columns', [
      '- Select a value -',
      'Two columns',
      'Three columns',
      'Four columns',
    ]);
    // Check the illustration item with flag paragraph fields.
    $this->assertFieldsPresent(['Flag', 'Highlight', 'Title', 'Body'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Flag field is required.',
      'Columns field is required.',
    ]);

    $this->fillParagraphField('Illustration list with flags', 1, 'Title', 'Illustration list with flags title');
    $this->selectParagraphOption('Illustration list with flags', 1, 'Columns', 'Two columns');
    $this->selectParagraphOption('Illustration item with flag', 1, 'Flag', 'Austria');
    $this->fillParagraphField('Illustration item with flag', 1, 'Highlight', 'Highlighted flag term 1');
    $this->fillParagraphField('Illustration item with flag', 1, 'Title', 'Illustration item with flag term 1');
    $this->fillParagraphField('Illustration item with flag', 1, 'Body', 'Illustration item with flag description 1');
    $page->pressButton('Add Illustration item with flag');
    $this->selectParagraphOption('Illustration item with flag', 2, 'Flag', 'Belgium');
    $this->fillParagraphField('Illustration item with flag', 2, 'Highlight', 'Highlighted flag term 2');
    $this->fillParagraphField('Illustration item with flag', 2, 'Title', 'Illustration item with flag term 2');
    $this->fillParagraphField('Illustration item with flag', 2, 'Body', 'Illustration item with flag description 2');
    $page->pressButton('Save');
    $this->assertHeading('Illustration list with flags paragraph test page');
    $this->assertTextsPresent([
      'Illustration list with flags title',
      'Center the content',
      'Austria',
      'Highlighted flag term 1',
      'Illustration item with flag term 1',
      'Illustration item with flag description 1',
      'Belgium',
      'Highlighted flag term 2',
      'Illustration item with flag term 2',
      'Illustration item with flag description 2',
      'Two columns',
      'Off',
    ]);

    // Re-edit the page and verify the vertical variant.
    $this->clickLink('Edit');
    $page->selectFieldOption('Variant', 'Vertical');
    $page->pressButton('Change variant');
    $this->assertFieldsPresent(['Variant', 'Title', 'Alternating background', 'Center the content'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertFieldsNotPresent(['Columns'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->checkField('Alternating background');
    $page->checkField('Center the content');
    $page->pressButton('Save');
    $this->assertSession()->pageTextContains('On');
    $this->assertSession()->pageTextNotContains('Off');
  }

  /**
   * Tests the illustration list with icons paragraph.
   */
  public function testIllustrationListWithIcons(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Illustration list with icons paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Illustration list with icons');
    // Check the illustration list with icons paragraph fields.
    $this->assertFieldsPresent(['Variant', 'Title', 'Columns', 'Center the content', 'Size'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Columns', [
      '- Select a value -',
      'Two columns',
      'Three columns',
      'Four columns',
    ]);
    $this->assertSelectOptions('Size', [
      '- Select a value -',
      'Small',
      'Medium',
      'Large',
    ]);
    // Check the illustration item with icon paragraph fields.
    $this->assertFieldsPresent(['Icon', 'Highlight', 'Title', 'Body'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Icon field is required.',
      'Columns field is required.',
      'Size field is required.',
    ]);

    $this->fillParagraphField('Illustration list with icons', 1, 'Title', 'Illustration list with icons title');
    $this->selectParagraphOption('Illustration list with icons', 1, 'Size', 'Medium');
    $this->selectParagraphOption('Illustration list with icons', 1, 'Columns', 'Two columns');
    $page->checkField('Center the content');
    $this->selectParagraphOption('Illustration item with icon', 1, 'Icon', 'Budget');
    $this->fillParagraphField('Illustration item with icon', 1, 'Highlight', 'Highlighted icon term 1');
    $this->fillParagraphField('Illustration item with icon', 1, 'Title', 'Illustration item with icon term 1');
    $this->fillParagraphField('Illustration item with icon', 1, 'Body', 'Illustration item with icon description 1');
    $page->pressButton('Add Illustration item with icon');
    $this->selectParagraphOption('Illustration item with icon', 2, 'Icon', 'Digital');
    $this->fillParagraphField('Illustration item with icon', 2, 'Highlight', 'Highlighted icon term 2');
    $this->fillParagraphField('Illustration item with icon', 2, 'Title', 'Illustration item with icon term 2');
    $this->fillParagraphField('Illustration item with icon', 2, 'Body', 'Illustration item with icon description 2');
    $page->pressButton('Save');
    $this->assertHeading('Illustration list with icons paragraph test page');
    $this->assertTextsPresent([
      'Illustration list with icons title',
      'Center the content',
      'On',
      'Size',
      'Medium',
      'Budget',
      'Highlighted icon term 1',
      'Illustration item with icon term 1',
      'Illustration item with icon description 1',
      'Digital',
      'Highlighted icon term 2',
      'Illustration item with icon term 2',
      'Illustration item with icon description 2',
      'Two columns',
      'Off',
    ]);

    // Re-edit the page and verify the vertical variant.
    $this->clickLink('Edit');
    $page->selectFieldOption('Variant', 'Vertical');
    $page->pressButton('Change variant');
    $this->assertFieldsPresent(['Variant', 'Title', 'Alternating background', 'Center the content', 'Size'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertFieldsNotPresent(['Columns'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->checkField('Alternating background');
    $page->pressButton('Save');
    $this->assertSession()->pageTextContains('On');
    $this->assertSession()->pageTextNotContains('Off');
  }

  /**
   * Tests the illustration list with images paragraph.
   */
  public function testIllustrationListWithImages(): void {
    $this->createImageMedia('Image 1');
    // The media name is taken from the mocked metadata.
    $this->createAvPortalPhotoMedia('https://audiovisual.ec.europa.eu/en/photo/P-038924~2F00-15');
    $this->drupalLogin($this->drupalCreateUser([
      'access content',
      'create oe_demo_landing_page content',
      'edit any oe_demo_landing_page content',
      'create av_portal_photo media',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Illustration list with images paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Illustration list with images');
    // Check the illustration list with images paragraph fields.
    $this->assertFieldsPresent(['Variant', 'Title', 'Columns', 'Center the content', 'Size'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Columns', [
      '- Select a value -',
      'Two columns',
      'Three columns',
      'Four columns',
    ]);
    $this->assertSelectOptions('Image ratio', [
      '- Select a value -',
      'Landscape',
      'Square',
    ]);
    $this->assertSelectOptions('Size', [
      '- Select a value -',
      'Small',
      'Medium',
      'Large',
    ]);
    // Check the illustration item with image paragraph fields.
    $this->assertFieldsPresent(['Image', 'Highlight', 'Title', 'Body'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Use existing media field is required',
      'Columns field is required.',
      'Image ratio field is required.',
      'Size field is required.',
    ]);

    $this->fillParagraphField('Illustration list with images', 1, 'Title', 'Illustration list with images title');
    $this->selectParagraphOption('Illustration list with images', 1, 'Size', 'Large');
    $this->selectParagraphOption('Illustration list with images', 1, 'Columns', 'Three columns');
    $this->selectParagraphOption('Illustration list with images', 1, 'Image ratio', 'Landscape');
    $this->fillParagraphField('Illustration item with image', 1, 'Use existing media', 'Image 1');
    $this->fillParagraphField('Illustration item with image', 1, 'Highlight', 'Highlighted image term 1');
    $this->fillParagraphField('Illustration item with image', 1, 'Title', 'Illustration item with image term 1');
    $this->fillParagraphField('Illustration item with image', 1, 'Body', 'Illustration item with image description 1');
    $page->pressButton('Add Illustration item with image');
    $this->fillParagraphField('Illustration item with image', 2, 'Use existing media', 'Euro with miniature figurines');
    $this->fillParagraphField('Illustration item with image', 2, 'Highlight', 'Highlighted image term 2');
    $this->fillParagraphField('Illustration item with image', 2, 'Title', 'Illustration item with image term 2');
    $this->fillParagraphField('Illustration item with image', 2, 'Body', 'Illustration item with image description 2');
    $page->pressButton('Save');
    $this->assertHeading('Illustration list with images paragraph test page');
    $this->assertTextsPresent([
      'Illustration list with images title',
      'Center the content',
      'Size',
      'Large',
      'Highlighted image term 1',
      'Illustration item with image term 1',
      'Illustration item with image description 1',
      'Highlighted image term 2',
      'Illustration item with image term 2',
      'Illustration item with image description 2',
      'Three columns',
      'Landscape',
      'Off',
    ]);
    $this->assertImagePresent('example_1.jpeg');

    // Re-edit the page and verify the vertical variant.
    $this->clickLink('Edit');
    $page->selectFieldOption('Variant', 'Vertical');
    $page->pressButton('Change variant');
    $this->assertFieldsPresent(['Variant', 'Title', 'Highlight', 'Alternating background', 'Center the content', 'Size'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertFieldsNotPresent(['Columns'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->checkField('Alternating background');
    $page->checkField('Center the content');
    $page->pressButton('Save');
    $this->assertSession()->pageTextContains('On');
    $this->assertSession()->pageTextNotContains('Off');
  }

}
