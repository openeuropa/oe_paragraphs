<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_iframe_media\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the iframe paragraph.
 *
 * @group batch3
 */
class IframeParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of an iframe paragraph.
   */
  public function testIframeParagraph(): void {
    $this->createIframeMedia('iframe', 'My Iframe', '<iframe src="http://example.com"></iframe>');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'edit any oe_demo_landing_page content',
      'access content',
      'create iframe media',
      'use text format oe_media_iframe',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Iframe paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Iframe');
    $this->assertFieldsPresent(['Use existing media', 'Full width', 'Title'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->fillField('Use existing media', 'My Iframe');
    $page->checkField('Full width');
    $this->fillParagraphField('Iframe', 1, 'Title', 'Title text');
    $page->pressButton('Save');
    $this->assertHeading('Iframe paragraph test page');
    $this->assertSession()->responseContains('<iframe src="http://example.com"></iframe>');
    $this->assertSession()->pageTextContains('Title text');
  }

}
