<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the links block paragraph.
 *
 * @group batch1
 */
class LinksBlockParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a links block paragraph.
   */
  public function testLinksBlockParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Links block test page');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Links block');
    $this->assertFieldsPresent(['Title', 'URL', 'Link text'], self::DEMO_PARAGRAPHS_ELEMENT);
    // The links block paragraph has no variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);

    // No fields are required.
    $page->pressButton('Save');
    $this->assertHeading('Links block test page');

    $this->clickLink('Edit');
    $this->fillParagraphField('Links block', 1, 'Title', 'List of links');
    $this->fillParagraphField('Links block', 1, 'URL', 'http://example.com/1');
    $this->fillParagraphField('Links block', 1, 'Link text', 'First link');
    $page->pressButton('Save');
    $this->assertHeading('Links block test page');
    $assert_session->pageTextContains('List of links');
    $assert_session->linkExists('First link');

    // Add another link.
    $this->clickLink('Edit');
    $this->fillNthField('URL', 2, 'http://example.com/1');
    $this->fillNthField('Link text', 2, 'Second link');
    $page->pressButton('Save');
    $this->assertHeading('Links block test page');
    $assert_session->pageTextContains('List of links');
    $assert_session->linkExists('First link');
    $assert_session->linkExists('Second link');
  }

}
