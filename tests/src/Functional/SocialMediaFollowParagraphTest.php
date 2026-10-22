<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the social media follow paragraph.
 *
 * @group batch2
 */
class SocialMediaFollowParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a social media follow paragraph in each variant.
   */
  public function testSocialMediaFollowParagraph(): void {
    $this->drupalLoginAsEditor();
    $page = $this->getSession()->getPage();

    foreach (['Horizontal', 'Vertical'] as $variant) {
      $this->startDemoLandingPage('Social media follow paragraph test page');
      $page->pressButton('Add Social media follow');
      $this->assertFieldsPresent(['Title', 'URL', 'Link text', 'Link type'], self::DEMO_PARAGRAPHS_ELEMENT);
      $this->assertSelectOptions('Variant', ['Horizontal', 'Vertical']);
      $this->assertSelectOptions('Link type', [
        '- Select -',
        'Bluesky',
        'Email',
        'Facebook',
        'Flickr',
        'Google+',
        'Instagram',
        'Linkedin',
        'Mastodon',
        'Pinterest',
        'RSS',
        'Storify',
        'Telegram',
        'X',
        'Yammer',
        'YouTube',
      ]);
      $page->pressButton('Save');
      $this->assertErrorMessages(['Title field is required']);

      $this->fillParagraphField('Social media follow', 1, 'Title', 'Follow this page');
      $page->selectFieldOption('Variant', $variant);
      $this->fillNthField('URL', 1, 'http://facebook.com');
      $this->fillNthField('Link text', 1, 'Facebook');
      $this->selectNthOption('Link type', 1, 'Facebook');
      $social_media_links = $this->assertSession()->elementExists('css', '.field--name-field-oe-social-media-links');
      $social_media_links->pressButton('Add another item');
      $this->fillNthField('URL', 2, 'http://twitter.com');
      $this->fillNthField('Link text', 2, '1st X');
      $this->selectNthOption('Link type', 2, 'X');
      $social_media_links = $this->assertSession()->elementExists('css', '.field--name-field-oe-social-media-links');
      $social_media_links->pressButton('Add another item');
      $this->fillNthField('URL', 3, 'http://twitter.com');
      $this->fillNthField('Link text', 3, '2nd X');
      $this->selectNthOption('Link type', 3, 'X');
      $see_more = $this->assertSession()->elementExists('css', '.field--name-field-oe-social-media-see-more');
      $see_more->fillField('URL', 'https://europa.eu/european-union/contact/social-networks_en');
      $see_more->fillField('Link text', 'Other social networks');
      $page->pressButton('Save');
      $this->assertHeading('Social media follow paragraph test page');
      $this->assertTextsPresent([
        'Facebook',
        '1st X',
        '2nd X',
        'Other social networks',
      ]);
    }
  }

}
