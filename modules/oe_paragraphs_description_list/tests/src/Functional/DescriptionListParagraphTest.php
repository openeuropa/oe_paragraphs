<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_description_list\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the description list paragraph.
 *
 * @group batch2
 */
class DescriptionListParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a description list paragraph.
   */
  public function testDescriptionListParagraph(): void {
    $this->drupalLogin($this->drupalCreateUser([
      'create oe_demo_landing_page content',
      'access content',
      'edit any oe_demo_landing_page content',
      'access content overview',
    ]));

    $this->startDemoLandingPage('Description List test page');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Description list');
    $this->assertFieldsPresent(['Heading', 'Term', 'Description'], self::DEMO_PARAGRAPHS_ELEMENT);
    // Assert all the fields are required for the first item.
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Term field is required.',
      'Description field is required.',
    ]);

    $page->fillField('Heading', 'Overview');
    $this->fillNthField('Term', 1, 'Term 1');
    $this->fillNthField('Description', 1, 'Description 1');
    $this->assertSession()->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Add another item');
    // Assert that the description is required if the term is filled in.
    $this->fillNthField('Term', 2, 'Term 2');
    $page->pressButton('Save');
    $this->assertErrorMessages(['The Description field is required when the Term field is specified.']);

    // Assert that the term is required if the description is filled in.
    $this->fillNthField('Term', 2, '');
    $this->fillNthField('Description', 2, 'Description 2');
    $page->pressButton('Save');
    $this->assertErrorMessages(['The Term field is required when the Description field is specified.']);

    $this->fillNthField('Term', 2, 'Term 2');
    $page->pressButton('Save');
    $this->assertHeading('Description List test page');
    $this->assertTextsPresent([
      'Overview',
      'Term 1',
      'Description 1',
      'Term 2',
      'Description 2',
    ]);
  }

}
