<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the paragraph variants widget.
 *
 * @group batch2
 */
class ParagraphVariantsTest extends ParagraphsDemoTestBase {

  /**
   * Tests that the variant is kept when re-saving the content.
   */
  public function testVariantPersistedOnContentResave(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Listing item block');
    $this->selectParagraphOption('Listing item block', 1, 'Layout', 'One column');
    $this->fillParagraphField('Listing item block', 1, 'Title', 'Block title');
    $this->selectParagraphOption('Listing item', 1, 'Variant', 'Highlight');
    $page->pressButton('Change variant');
    $this->fillParagraphField('Listing item', 1, 'Link', '<front>');
    $this->fillParagraphField('Listing item', 1, 'Title', 'First item');
    $page->pressButton('Save');
    $this->assertHeading('Test page');

    // Open the listing item form and verify that the variant is the correct
    // one.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Listing item', 1, 'Edit');
    $this->assertParagraphSelectedOption('Listing item', 1, 'Variant', 'Highlight');
    $this->fillParagraphField('Listing item', 1, 'Title', 'First item v2');
    $page->pressButton('Save');
    $this->assertHeading('Test page');

    // Check again that the variant field has the correct value after editing.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Listing item', 1, 'Edit');
    $this->assertParagraphSelectedOption('Listing item', 1, 'Variant', 'Highlight');

    // Reload the page so that the listing item will start in "closed" state
    // and save without opening the paragraph form.
    $this->drupalGet($this->getUrl());
    $page->pressButton('Save');
    $this->assertHeading('Test page');

    // Verify that the variant has been kept.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Listing item', 1, 'Edit');
    $this->assertParagraphSelectedOption('Listing item', 1, 'Variant', 'Highlight');
  }

}
