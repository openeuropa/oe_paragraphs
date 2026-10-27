<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_document\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the document paragraph.
 *
 * @group batch3
 */
class DocumentParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of document paragraphs with local and remote files.
   */
  public function testDocumentParagraph(): void {
    $local_document = $this->createLocalDocumentMedia('My example local document 1');
    // Use the URL of the local file as remote file, so that it's reachable.
    $file_name = basename($local_document->get('oe_media_file')->entity->getFileUri());
    $this->createRemoteDocumentMedia('My example remote document 1', $this->baseUrl . '/' . $this->publicFilesDirectory . '/' . $file_name);
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'edit any oe_demo_landing_page content',
      'access content overview',
      'create document media',
      'delete any document media',
      'update any media',
    ]));
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    // Add the local document to a page.
    $this->startDemoLandingPage('Local document paragraph test page');
    $page->pressButton('Add Document');
    $page->fillField('Use existing media', 'My example local document 1');
    $page->pressButton('Save');
    $this->assertHeading('Local document paragraph test page');
    $assert_session->pageTextContains('Document');
    $assert_session->linkExists('example_1.pdf');

    // Add the remote document to a page.
    $this->startDemoLandingPage('Remote document paragraph test page');
    $page->pressButton('Add Document');
    $page->fillField('Use existing media', 'My example remote document 1');
    $page->pressButton('Save');
    $this->assertHeading('Remote document paragraph test page');
    $assert_session->pageTextContains('Document');
    $assert_session->linkExists('My example remote document 1');
  }

}
