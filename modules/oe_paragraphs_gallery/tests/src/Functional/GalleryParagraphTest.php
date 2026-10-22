<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_gallery\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the gallery paragraph.
 *
 * @group batch3
 */
class GalleryParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a gallery paragraph.
   */
  public function testGalleryParagraph(): void {
    // The media names of the remote media are taken from the mocked metadata.
    $this->createRemoteVideoMedia('https://www.youtube.com/watch?v=YaUGTOnf6k0');
    $this->createImageMedia('Image 1');
    $this->createAvPortalPhotoMedia('https://audiovisual.ec.europa.eu/en/photo/P-038924~2F00-15');
    $this->createAvPortalVideoMedia('https://audiovisual.ec.europa.eu/en/video/I-163162');
    $this->createLocalDocumentMedia('Document 1');
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'edit any oe_demo_landing_page content',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Gallery test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Gallery');
    // Assert all the fields are present.
    $this->assertFieldsPresent(['Title', 'Description', 'Use existing media'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Assert the required fields.
    $page->pressButton('Save');
    $this->assertErrorMessages(['Use existing media field is required.']);

    $this->fillParagraphField('Gallery', 1, 'Title', 'Gallery test page');
    $this->fillParagraphField('Gallery', 1, 'Description', 'A very long description for the gallery paragraph.');
    $this->fillNthField('Use existing media', 1, 'Plant health in the EU');
    $this->pressParagraphButton('Gallery', 1, 'Add another item');
    $page->fillField('Medias (value 2)', 'Image 1');
    $this->pressParagraphButton('Gallery', 1, 'Add another item');
    $page->fillField('Medias (value 3)', 'Euro with miniature figurines');
    $this->pressParagraphButton('Gallery', 1, 'Add another item');
    $page->fillField('Medias (value 4)', ' Economic and Financial Affairs Council - Arrivals');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Gallery test page has been created.', 'status');
    $this->assertOembedIframePresent('https://www.youtube.com/watch?v=YaUGTOnf6k0');
    $this->assertImagePresent('example_1.jpeg');
    $this->assertAvPortalVideoPresent(' Economic and Financial Affairs Council - Arrivals');
    $this->assertAvPortalPhotoPresent('Euro with miniature figurines', '//ec.europa.eu/avservices/repository/photo/03/89/24/P-038924_00-15_01-ORIGINAL-394989.jpg');

    // Documents cannot be referenced. More media bundles cannot be referenced,
    // but we cannot assert them all. This is just to check that a minimum
    // filtering is applied.
    $this->clickLink('Edit');
    $page->fillField('Medias (value 5)', 'Document 1');
    $page->pressButton('Save');
    $this->assertErrorMessages(['There are no media items matching "Document 1".']);
  }

}
