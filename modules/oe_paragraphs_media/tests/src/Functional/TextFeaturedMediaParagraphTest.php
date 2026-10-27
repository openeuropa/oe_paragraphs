<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_media\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the text with featured media paragraph.
 *
 * @group batch3
 */
class TextFeaturedMediaParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a text with featured media paragraph.
   */
  public function testTextFeaturedMediaParagraph(): void {
    $this->createImageMedia('My Image 1');
    // The names of the remote media are taken from the mocked metadata.
    $this->createRemoteVideoMedia('https://www.youtube.com/watch?v=1-g73ty9v04');
    $this->createAvPortalVideoMedia('https://audiovisual.ec.europa.eu/en/video/I-162747');
    $this->createWebtoolsMedia('webtools_chart', 'Basic chart', '{"service":"charts","data":{"series":[{"name":"Y","data":[{"name":"1","y":0.5}]}]},"provider":"highcharts"}');
    $this->createWebtoolsMedia('webtools_map', 'World map', '{"service": "map"}');
    $this->createWebtoolsMedia('webtools_countdown', 'My countdown', '{"service":"cdown","date":"30/04/2052","timezone":"Etc/Universal","title":"Event countdown","end":true,"show":{"day":true,"time":true}}');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'edit any oe_demo_landing_page content',
      'create image media',
      'create remote_video media',
      'create av_portal_video media',
      'create webtools_chart media',
      'create webtools_countdown media',
      'create webtools_map media',
      'create webtools_social_feed media',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Text with Featured media paragraph test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Text with Featured media');
    $this->assertFieldsPresent([
      'Heading',
      'Title',
      'Use existing media',
      'Caption',
      'Full text',
      'Highlighted',
    ], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSelectOptions('Variant', [
      'Text on the left, simple call to action',
      'Text on the left, featured call to action',
      'Text on the right, featured call to action',
      'Text on the right, simple call to action',
    ]);

    // Create a text with featured media paragraph with an image.
    $this->fillParagraphField('Text with Featured media', 1, 'Heading', 'Heading text');
    $this->fillParagraphField('Text with Featured media', 1, 'Title', 'Title text');
    $page->fillField('Use existing media', 'My Image 1');
    $this->fillParagraphField('Text with Featured media', 1, 'Caption', 'Caption text');
    $this->fillParagraphField('Text with Featured media', 1, 'Full text', 'Featured text');
    $this->fillParagraphField('Text with Featured media', 1, 'URL', 'http://example.com/link');
    $this->fillParagraphField('Text with Featured media', 1, 'Link text', 'Link title');
    $page->pressButton('Save');
    $this->assertHeading('Text with Featured media paragraph test page');
    $this->assertTextsPresent([
      'Heading text',
      'Title text',
      'Caption text',
      'Featured text',
    ]);
    $this->assertImagePresent('example_1.jpeg');
    $assert_session->responseContains('http://example.com/link');
    $assert_session->linkExists('Link title');

    // Use a remote video.
    $this->clickLink('Edit');
    $page->fillField('Use existing media', "Energy, let's save it!");
    $page->pressButton('Save');
    $this->assertOembedIframePresent('https://www.youtube.com/watch?v=1-g73ty9v04');

    // Use an AV Portal video.
    $this->clickLink('Edit');
    $page->fillField('Use existing media', 'Midday press briefing from 25/10/2018');
    $page->pressButton('Save');
    $this->assertAvPortalVideoPresent('Midday press briefing from 25/10/2018');

    // Use the Webtools media.
    $webtools_media = [
      'webtools_chart' => 'Basic chart',
      'webtools_map' => 'World map',
      'webtools_countdown' => 'My countdown',
    ];
    foreach ($webtools_media as $bundle => $name) {
      $this->clickLink('Edit');
      $page->fillField('Use existing media', $name);
      $page->pressButton('Save');
      $this->assertWebtoolsJsonPresent($bundle, $name);
    }

    // Change the variants to ensure the presence of the fields.
    $this->clickLink('Edit');
    $variants = [
      'Text on the left, featured call to action',
      'Text on the left, simple call to action',
      'Text on the right, featured call to action',
      'Text on the right, simple call to action',
    ];
    foreach ($variants as $variant) {
      $page->selectFieldOption('Variant', $variant);
      $page->pressButton('Change variant');
      $this->assertFieldsPresent([
        'Heading',
        'Title',
        'Use existing media',
        'Caption',
        'Full text',
        'URL',
        'Link text',
        'Highlighted',
      ], self::DEMO_PARAGRAPHS_ELEMENT);
    }

    // Verify that the variant has been kept.
    $page->pressButton('Save');
    $this->clickLink('Edit');
    $this->assertParagraphSelectedOption('Text with Featured media', 1, 'Variant', 'Text on the right, simple call to action');
  }

}
