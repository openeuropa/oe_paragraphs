<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_tabs\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Tests the Tabs and Tab paragraph types.
 */
class TabsParagraphsTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'datetime',
    'entity_reference_revisions',
    'field',
    'file',
    'filter',
    'image',
    'link',
    'oe_paragraphs',
    'oe_paragraphs_tabs',
    'options',
    'paragraphs',
    'system',
    'text',
    'typed_link',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installEntitySchema('paragraph');
    $this->installConfig(['field', 'filter', 'text', 'oe_paragraphs', 'oe_paragraphs_tabs']);
  }

  /**
   * The paragraph types have the expected fields and allowed sub-types.
   */
  public function testFields(): void {
    $tabs = FieldConfig::loadByName('paragraph', 'oe_tabs', 'field_oe_paragraphs');
    $this->assertTrue($tabs->isRequired());
    $this->assertSame(['oe_tab' => 'oe_tab'], $tabs->getSetting('handler_settings')['target_bundles']);
    $this->assertSame(['field_oe_paragraphs'], $this->getConfigurableFields('oe_tabs'));

    $label = FieldConfig::loadByName('paragraph', 'oe_tab', 'field_oe_title');
    $this->assertTrue($label->isRequired());
    $this->assertTrue($label->isTranslatable());

    $body = FieldConfig::loadByName('paragraph', 'oe_tab', 'field_oe_paragraphs');
    $this->assertTrue($body->isRequired());
    $allowed = $body->getSetting('handler_settings')['target_bundles'];
    $this->assertArrayHasKey('oe_rich_text', $allowed);
    $this->assertArrayHasKey('oe_accordion', $allowed);
    $this->assertArrayHasKey('oe_content_row', $allowed);
    $this->assertArrayNotHasKey('oe_tabs', $allowed);
    $this->assertArrayNotHasKey('oe_tab', $allowed);
    $this->assertSame(['field_oe_paragraphs', 'field_oe_title'], $this->getConfigurableFields('oe_tab'));
  }

  /**
   * A Tab cannot contain Tabs, also through a Content row.
   */
  public function testNoNestedTabs(): void {
    $rich_text = Paragraph::create(['type' => 'oe_rich_text', 'field_oe_text_long' => 'Text']);
    $tab = Paragraph::create(['type' => 'oe_tab', 'field_oe_title' => 'Tab', 'field_oe_paragraphs' => [$rich_text]]);
    $this->assertNotContains('A Tab cannot contain Tabs.', $this->getMessages($tab));

    $inner_tab = Paragraph::create([
      'type' => 'oe_tab',
      'field_oe_title' => 'Inner',
      'field_oe_paragraphs' => [$rich_text],
    ]);
    $inner_tabs = Paragraph::create(['type' => 'oe_tabs', 'field_oe_paragraphs' => [$inner_tab]]);
    $row = Paragraph::create(['type' => 'oe_content_row', 'field_oe_paragraphs' => [$inner_tabs]]);
    $tab = Paragraph::create(['type' => 'oe_tab', 'field_oe_title' => 'Tab', 'field_oe_paragraphs' => [$row]]);
    $this->assertContains('A Tab cannot contain Tabs.', $this->getMessages($tab));
  }

  /**
   * A Content row inside a Tab cannot use the in-page navigation variant.
   */
  public function testNoInpageNavigation(): void {
    $message = 'A Content row inside a Tab cannot use the in-page navigation variant.';

    $row = Paragraph::create(['type' => 'oe_content_row', 'oe_paragraphs_variant' => 'default']);
    $tab = Paragraph::create(['type' => 'oe_tab', 'field_oe_title' => 'Tab', 'field_oe_paragraphs' => [$row]]);
    $this->assertNotContains($message, $this->getMessages($tab));

    $row = Paragraph::create(['type' => 'oe_content_row', 'oe_paragraphs_variant' => 'inpage_navigation']);
    $tab = Paragraph::create(['type' => 'oe_tab', 'field_oe_title' => 'Tab', 'field_oe_paragraphs' => [$row]]);
    $this->assertContains($message, $this->getMessages($tab));
  }

  /**
   * Returns the configurable field names of a paragraph type.
   *
   * @param string $bundle
   *   The paragraph type.
   *
   * @return string[]
   *   The sorted field names.
   */
  protected function getConfigurableFields(string $bundle): array {
    $fields = array_keys(array_filter(
      \Drupal::service('entity_field.manager')->getFieldDefinitions('paragraph', $bundle),
      fn($definition) => $definition instanceof FieldConfig,
    ));
    sort($fields);
    return $fields;
  }

  /**
   * Returns the violation messages of a paragraph.
   *
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   *   The paragraph.
   *
   * @return string[]
   *   The messages.
   */
  protected function getMessages(Paragraph $paragraph): array {
    $messages = [];
    foreach ($paragraph->validate() as $violation) {
      $messages[] = (string) $violation->getMessage();
    }
    return $messages;
  }

}
