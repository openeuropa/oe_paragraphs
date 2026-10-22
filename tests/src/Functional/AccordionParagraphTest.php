<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the accordion and accordion item paragraphs.
 *
 * @group batch2
 */
class AccordionParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the accordion and accordion item required fields.
   */
  public function testAccordionRequiredFields(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Required fields accordion');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Accordion');
    // Neither accordion nor accordion item paragraphs have variants.
    $this->assertFieldsNotPresent(['Variant'], self::DEMO_PARAGRAPHS_ELEMENT);

    // Remove the accordion item that is shown by default in the page so we are
    // able to test the required fields of the accordion itself.
    $this->pressParagraphActionsButton('Accordion item', 1, 'Remove');
    $page->pressButton('Save');
    $this->assertErrorMessages(['Paragraphs field is required']);
    $this->assertParagraphReferenceableTypes('Accordion', 1, 'Paragraphs', ['Accordion item']);

    // Test accordion item required fields.
    $page->pressButton('Add Accordion item');
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Title field is required',
      'Body field is required',
    ]);
    $this->fillParagraphField('Accordion item', 1, 'Title', 'Accordion item 1 title');
    $this->fillParagraphField('Accordion item', 1, 'Body', 'Accordion item 1 body');
    $page->pressButton('Save');
    $this->assertHeading('Required fields accordion');
  }

  /**
   * Tests the CRUD operations on accordion and accordion item paragraphs.
   */
  public function testAccordionCrud(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Accordion page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Accordion');
    $this->fillParagraphField('Accordion item', 1, 'Title', 'Accordion item 1 title');
    $this->fillParagraphField('Accordion item', 1, 'Body', 'Accordion item 1 body');
    $this->selectParagraphOption('Accordion item', 1, 'Icon', 'Book');
    $page->pressButton('Add Accordion item');
    $this->fillParagraphField('Accordion item', 2, 'Title', 'Accordion item 2 title');
    $this->fillParagraphField('Accordion item', 2, 'Body', 'Accordion item 2 body');
    $this->selectParagraphOption('Accordion item', 2, 'Icon', 'Feedback');
    $page->pressButton('Save');
    $this->assertTextsPresent([
      'Book',
      'Accordion item 1 title',
      'Accordion item 1 body',
      'Feedback',
      'Accordion item 2 title',
      'Accordion item 2 body',
    ]);

    // Edit one accordion item.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Accordion item', 1, 'Edit');
    $this->fillParagraphField('Accordion item', 1, 'Title', 'Accordion item 1 title updated');
    $this->fillParagraphField('Accordion item', 1, 'Body', 'Accordion item 1 body updated');
    $this->selectParagraphOption('Accordion item', 1, 'Icon', 'Digital');
    // Collapse the accordion item form.
    $this->pressParagraphActionsButton('Accordion item', 1, 'Collapse');
    // Finally save the page.
    $page->pressButton('Save');
    $this->assertTextsPresent([
      'Digital',
      'Accordion item 1 title updated',
      'Accordion item 1 body updated',
      'Feedback',
      'Accordion item 2 title',
      'Accordion item 2 body',
    ]);

    // Delete an accordion item.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Accordion item', 1, 'Remove');
    $page->pressButton('Save');
    $this->assertTextsPresent([
      'Feedback',
      'Accordion item 2 title',
      'Accordion item 2 body',
    ]);
    $this->assertTextsNotPresent([
      'Digital',
      'Accordion item 1 title updated',
      'Accordion item 1 body updated',
    ]);

    // Delete the whole accordion.
    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Accordion', 1, 'Remove');
    $page->pressButton('Save');
    $this->assertHeading('Accordion page');
    $this->assertTextsNotPresent([
      'Digital',
      'Accordion item 1 title updated',
      'Accordion item 1 body updated',
      'Feedback',
      'Accordion item 2 title',
      'Accordion item 2 body',
    ]);
  }

}
