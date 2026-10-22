<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_timeline\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the timeline paragraph.
 *
 * @group batch2
 */
class TimelineParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a timeline paragraph and the items validation.
   */
  public function testTimelineParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Timeline paragraph test');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('Add Timeline');
    $this->assertFieldsPresent([
      'Heading',
      'Introduction',
      'Label',
      'Title',
      'Content',
      'Expand button',
    ], self::DEMO_PARAGRAPHS_ELEMENT);
    // The first item has both fields required.
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Label field is required.',
      'Title field is required.',
    ]);

    $page->fillField('Heading', 'Timeline paragraph heading');
    $page->fillField('Introduction', 'Timeline paragraph introduction');
    $this->fillMultipleCardinalityFieldItem('Items', 1, 'Label', 'Item 1 label');
    $this->fillMultipleCardinalityFieldItem('Items', 1, 'Title', 'Item 1 title');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph test has been created.', 'status');
    $this->assertTextsPresent([
      'Timeline paragraph heading',
      'Timeline paragraph introduction',
      'Item 1 label',
      'Item 1 title',
    ]);

    // Verify that the title is required if a value is entered in the label.
    $this->clickLink('Edit');
    $assert_session->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Add another item');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', 'Item 2 label');
    $page->pressButton('Save');
    $this->assertErrorMessages(['The Title field is required when the Label field is specified.']);

    // Verify that the label is required if a value is entered in the title.
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', '');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', 'Item 2 title');
    $page->pressButton('Save');
    $this->assertErrorMessages(['The Label field is required when the Title field is specified.']);

    // Verify that label and title are required if a value is entered in the
    // content.
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', '');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Content', 'Item 2 content');
    $page->pressButton('Save');
    $this->assertErrorMessages(['The Label and Title fields are required when the Content field is specified.']);

    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', 'Item 2 label');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', 'Item 2 title');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph test has been updated.', 'status');
    $this->assertTextsPresent([
      'Timeline paragraph heading',
      'Timeline paragraph introduction',
      'Item 1 label',
      'Item 1 title',
      'Item 2 label',
      'Item 2 title',
      'Item 2 content',
    ]);

    // Check that the paragraph is not collapsed by searching for a field.
    $this->clickLink('Edit');
    $this->assertFieldsPresent(['Expand button'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Verify that we can edit the page and save it without adding any item.
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph test has been updated.', 'status');

    // Verify that an item can be deleted by emptying its values.
    $this->clickLink('Edit');
    $assert_session->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Edit all');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', '');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', '');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Content', '');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph test has been updated.', 'status');
    $this->assertTextsPresent([
      'Timeline paragraph heading',
      'Item 1 label',
      'Item 1 title',
    ]);
    $this->assertTextsNotPresent([
      'Item 2 label',
      'Item 2 title',
      'Item 2 content',
    ]);
  }

}
