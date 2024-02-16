<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

/**
 * Tests paragraphs forms.
 */
class ParagraphsTest extends ParagraphsTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'oe_paragraphs_illustrations_lists',
    'oe_paragraphs_options_event_test',
  ];

  /**
   * Test icon options event subscriber.
   */
  public function testIconOptionsEventsubscriber(): void {
    $this->drupalGet('/node/add/paragraphs_test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Fact');

    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_icon]');
    $allowed_values = [
      '_none',
      'item-test-1',
      'item-test-2',
      'item-test-3',
    ];
    foreach ($allowed_values as $allowed_value) {
      $this->assertSession()->elementsCount('css', 'option[value="' . $allowed_value . '"]', 1);
    }
    $this->assertSession()->elementsCount('css', 'select#edit-oe-paragraphs-0-subform-field-oe-icon option', 4);
  }

  /**
   * Test flag options event subscriber.
   */
  public function testFlagOptionsEventsubscriber(): void {
    $this->drupalGet('/node/add/paragraphs_test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Illustration list with flags');

    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_flag]');
    $allowed_values = [
      'flag-test-1',
      'flag-test-2',
      'flag-test-3',
    ];
    foreach ($allowed_values as $allowed_value) {
      $this->assertSession()->elementsCount('css', 'option[value="' . $allowed_value . '"]', 1);
    }
    $this->assertSession()->elementsCount('css', 'select#edit-oe-paragraphs-0-subform-field-oe-paragraphs-0-subform-field-oe-flag option', 4);
  }

}
