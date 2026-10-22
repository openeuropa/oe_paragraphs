<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests the facts and figures and fact paragraphs.
 *
 * @group batch2
 */
class FactsFiguresParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a facts and figures paragraph.
   */
  public function testFactsFiguresParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Facts and figures paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Facts and figures');
    // Check the facts and figures paragraph fields.
    $this->assertFieldsPresent(['Title', 'URL', 'Link text'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Check the fields of the referenced fact paragraph.
    $this->assertFieldsPresent(['Figure', 'Icon', 'Description', 'Label'], self::DEMO_PARAGRAPHS_ELEMENT);

    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Label field is required',
      'Figure field is required',
    ]);

    $this->fillParagraphField('Facts and figures', 1, 'Title', 'Facts and figures');
    $page->fillField('URL', 'https://example.com');
    $page->fillField('Link text', 'Example');
    $this->selectParagraphOption('Fact', 1, 'Icon', 'Budget');
    $this->fillParagraphField('Fact', 1, 'Figure', '10 millions');
    $this->fillParagraphField('Fact', 1, 'Label', '1st Fact');
    $this->fillParagraphField('Fact', 1, 'Description', '1st Description');
    $page->pressButton('Add Fact');
    $this->selectParagraphOption('Fact', 2, 'Icon', 'Book');
    $this->fillParagraphField('Fact', 2, 'Figure', '22 millions');
    $this->fillParagraphField('Fact', 2, 'Label', '2nd Fact');
    $this->fillParagraphField('Fact', 2, 'Description', '2nd Description');
    $page->pressButton('Save');
    $this->assertHeading('Facts and figures paragraph test page');
    $this->assertSession()->linkExists('Example');
    $this->assertTextsPresent([
      'Facts and figures',
      '1st Fact',
      '1st Description',
      '10 millions',
      'Budget',
      '2nd Fact',
      '2nd Description',
      '22 millions',
      'Book',
    ]);
  }

}
