<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_contact\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the contact paragraph.
 *
 * @group batch2
 */
class ContactParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests that editors can create contacts through the contact paragraph.
   */
  public function testContactParagraph(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Contact paragraph test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Contact');
    // Check the contact paragraph fields.
    $this->assertFieldsPresent(['Heading'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertSession()->pageTextContains('Contacts');

    // Check the required field validation.
    $page->pressButton('Save');
    $this->assertErrorMessages(['Contacts field is required.']);

    $page->fillField('Heading', 'Contact paragraph demo');
    $page->pressButton('Add new Contact');
    $page->fillField('Name', 'First Contact');
    $page->pressButton('Create Contact');
    $page->pressButton('Add new Contact');
    $page->fillField('Name', 'Second Contact');
    $page->pressButton('Save');
    $this->assertHeading('Contact paragraph test page');
    $this->assertTextsPresent([
      'Contact paragraph demo',
      'First Contact',
      'Second Contact',
    ]);
  }

}
