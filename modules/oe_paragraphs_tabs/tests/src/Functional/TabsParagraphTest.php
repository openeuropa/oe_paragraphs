<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_tabs\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the Tabs paragraph.
 *
 * @group batch3
 */
class TabsParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a Tabs paragraph.
   */
  public function testTabsParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Tabs test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Tabs');
    $this->assertParagraphReferenceableTypes('Tabs', 1, 'Tabs', ['Tab']);
    $this->assertFieldsPresent(['Heading'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Neither Tabs nor Content row can be added in a Tab.
    $this->assertParagraphReferenceableTypes('Tab', 1, 'Content', [
      'Accordion',
      'Contextual navigation',
      'Facts and figures',
      'Listing item block',
      'Quote',
      'Rich text',
      'Social media follow',
    ]);

    // The Tab fields are required.
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Heading field is required',
      'Content field is required',
    ]);

    $this->fillParagraphField('Tab', 1, 'Heading', 'First tab');
    $this->findParagraph('Tab', 1)->pressButton('Add Rich text');
    $this->fillParagraphField('Rich text', 1, 'Text', 'Text of the first tab.');
    $page->pressButton('Save');
    $this->assertHeading('Tabs test page');
    $this->assertTextsPresent(['Text of the first tab.']);
  }

}
