<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_illustrations_lists\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsTestBase;

/**
 * Tests illustration lists paragraphs forms.
 */
class ParagraphsTest extends ParagraphsTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'oe_paragraphs_illustrations_lists',
    'oe_paragraphs_illustrations_lists_test',
  ];

  /**
   * Test column options and ratio options event subscribers.
   */
  public function testEventsubscribers(): void {
    $this->drupalGet('/node/add/paragraphs_test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Illustration list with images');

    // Assert the Columns select field.
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_illustration_columns]');
    $allowed_values = [
      'column-test-1',
      'column-test-2',
      'column-test-3',
    ];
    foreach ($allowed_values as $allowed_value) {
      $this->assertSession()->elementsCount('css', 'option[value="' . $allowed_value . '"]', 1);
    }
    $this->assertSession()->elementsCount('css', 'select#edit-oe-paragraphs-0-subform-field-oe-illustration-columns option', 4);

    // Assert the Image ratio select field.
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_illustration_ratio]');
    $allowed_values = [
      'ratio-test-1',
      'ratio-test-2',
      'ratio-test-3',
    ];
    foreach ($allowed_values as $allowed_value) {
      $this->assertSession()->elementsCount('css', 'option[value="' . $allowed_value . '"]', 1);
    }
    $this->assertSession()->elementsCount('css', 'select#edit-oe-paragraphs-0-subform-field-oe-illustration-ratio option', 4);
  }

}
