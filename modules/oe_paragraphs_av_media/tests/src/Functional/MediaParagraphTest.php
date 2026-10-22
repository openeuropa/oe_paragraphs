<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_av_media\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the media paragraph.
 *
 * @group batch3
 */
class MediaParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a media paragraph with different media types.
   */
  public function testMediaParagraph(): void {
    $this->createImageMedia('My Image 1');
    $this->createIframeMedia('video_iframe', 'My Iframe video', '<iframe src="http://example.com"></iframe>');
    // The media name is taken from the mocked metadata.
    $this->createAvPortalVideoMedia('https://audiovisual.ec.europa.eu/en/video/I-162747');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'access content overview',
      'edit any oe_demo_landing_page content',
      'create image media',
      'create video_iframe media',
      'create av_portal_video media',
      'use text format oe_media_iframe',
    ]));

    $this->startDemoLandingPage('Media paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Media');
    $this->assertFieldsPresent(['Use existing media'], self::DEMO_PARAGRAPHS_ELEMENT);

    // Create a media paragraph with an image.
    $page->fillField('Use existing media', 'My Image 1');
    $page->pressButton('Save');
    $this->assertHeading('Media paragraph test page');
    $this->assertImagePresent('example_1.jpeg');

    // Edit the paragraph to use the video iframe media.
    $this->clickLink('Edit');
    $page->fillField('Use existing media', 'My Iframe video');
    $page->pressButton('Save');
    $this->assertSession()->responseContains('<iframe src="http://example.com"></iframe>');

    // Edit the paragraph to use the AV Portal video media.
    $this->clickLink('Edit');
    $page->fillField('Use existing media', 'Midday press briefing from 25/10/2018');
    $page->pressButton('Save');
    $this->assertAvPortalVideoPresent('Midday press briefing from 25/10/2018');
  }

}
