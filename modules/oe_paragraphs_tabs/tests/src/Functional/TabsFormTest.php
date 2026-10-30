<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_tabs\Functional;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Tests\oe_paragraphs\Functional\ParagraphsTestBase;
use Drupal\Tests\oe_paragraphs\Traits\ParagraphsFormTrait;
use Drupal\Tests\oe_paragraphs\Traits\TraversingTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;

/**
 * Tests the Content row variant inside a Tab in the node form.
 *
 * @group batch3
 */
class TabsFormTest extends ParagraphsTestBase {

  use ParagraphsFormTrait;
  use TraversingTrait;
  use UtilityTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'oe_paragraphs_tabs',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Use the variants widget on the node too, so a Content row outside a Tab
    // shows its variant select.
    $this->container->get('entity_display.repository')
      ->getFormDisplay('node', 'paragraphs_test')
      ->setComponent('oe_paragraphs', ['type' => 'oe_paragraphs_variants'])
      ->save();
  }

  /**
   * The in-page navigation variant is not available in a Tab.
   */
  public function testVariantOptions(): void {
    $this->drupalGet('/node/add/paragraphs_test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Content row');
    $page->pressButton('Add Tabs');
    $this->findParagraph('Tab', 1)->pressButton('Add Content row');

    // A Content row outside a Tab is not affected.
    $this->assertContains('Inpage navigation', $this->getVariantOptions(1));
    $this->assertNotContains('Inpage navigation', $this->getVariantOptions(2));
  }

  /**
   * An existing in-page navigation Content row in a Tab keeps its variant.
   */
  public function testExistingInpageNavigation(): void {
    $row = Paragraph::create(['type' => 'oe_content_row', 'oe_paragraphs_variant' => 'inpage_navigation']);
    $tab = Paragraph::create(['type' => 'oe_tab', 'field_oe_title' => 'Tab', 'field_oe_paragraphs' => [$row]]);
    $tabs = Paragraph::create(['type' => 'oe_tabs', 'field_oe_paragraphs' => [$tab]]);
    $node = $this->drupalCreateNode(['type' => 'paragraphs_test', 'oe_paragraphs' => [$tabs]]);

    $this->drupalGet($node->toUrl('edit-form'));
    $this->pressParagraphActionsButton('Tab', 1, 'Edit');
    $this->pressParagraphActionsButton('Content row', 1, 'Edit');
    $this->assertParagraphSelectedOption('Content row', 1, 'Variant', 'Inpage navigation');

    $this->getSession()->getPage()->pressButton('Save');
    $this->assertSession()->pageTextContains('A Content row inside a Tab cannot use the in-page navigation variant.');
  }

  /**
   * Returns the variant options of a Content row.
   *
   * @param int $position
   *   The position of the Content row.
   *
   * @return string[]
   *   The option labels.
   */
  protected function getVariantOptions(int $position): array {
    $select = $this->getParagraphFormFieldCollection($this->findParagraph('Content row', $position))
      ->find('named', ['select', 'Variant']);
    $this->assertNotNull($select);
    return $this->getSelectOptions($select);
  }

}
