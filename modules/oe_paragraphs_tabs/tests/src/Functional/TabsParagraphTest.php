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
    $page->pressButton('Add Content row');
    $page->pressButton('Add Tabs');
    $this->assertParagraphReferenceableTypes('Tabs', 1, 'Tabs', ['Tab']);
    $this->assertFieldsPresent(['Label'], self::DEMO_PARAGRAPHS_ELEMENT);
    $this->assertParagraphReferenceableTypes('Tab', 1, 'Content', [
      'Accordion',
      'Content row',
      'Links block',
      'Listing item block',
      'Quote',
      'Rich text',
    ]);

    // The Tab fields are required.
    $page->pressButton('Save');
    $this->assertErrorMessages([
      'Label field is required',
      'Content field is required',
    ]);

    // The in-page navigation variant is not available for a Content row
    // inside a Tab, only outside.
    $this->findParagraph('Tab', 1)->pressButton('Add Content row');
    $this->assertContains('Inpage navigation', $this->getVariantOptions(1));
    $this->assertNotContains('Inpage navigation', $this->getVariantOptions(2));

    // A Content row inside a Tab cannot contain Tabs.
    $this->assertParagraphReferenceableTypes('Content row', 2, 'Paragraphs', [
      'Accordion',
      'Contextual navigation',
      'Links block',
      'Listing item block',
      'Quote',
      'Rich text',
      'Social media follow',
    ]);

    $this->fillParagraphField('Tab', 1, 'Label', 'First tab');
    $page->pressButton('Save');
    $this->assertHeading('Tabs test page');

    // An existing in-page navigation Content row inside a Tab keeps its
    // variant, and cannot be saved.
    $rows = $this->container->get('entity_type.manager')
      ->getStorage('paragraph')
      ->loadByProperties(['type' => 'oe_content_row', 'parent_type' => 'paragraph']);
    /** @var \Drupal\paragraphs\ParagraphInterface $row */
    $row = reset($rows);
    $row->set('oe_paragraphs_variant', 'inpage_navigation')->save();

    $this->clickLink('Edit');
    $this->pressParagraphActionsButton('Tab', 1, 'Edit');
    $this->pressParagraphActionsButton('Content row', 2, 'Edit');
    $this->assertParagraphSelectedOption('Content row', 2, 'Variant', 'Inpage navigation');
    $page->pressButton('Save');
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
