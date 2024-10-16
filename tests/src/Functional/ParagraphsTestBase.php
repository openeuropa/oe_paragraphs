<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Base class to test paragraphs.
 */
abstract class ParagraphsTestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field_ui',
    'oe_paragraphs',
  ];

  /**
   * The administration theme name.
   *
   * @var string
   */
  protected $adminTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalLogin($this->drupalCreateUser([], '', TRUE));
    $this->drupalCreateContentType([
      'type' => 'paragraphs_test',
      'name' => 'Paragraphs Test',
    ]);
    $this->addParagraphsField();
  }

  /**
   * Creates a paragraph reference field.
   */
  protected function addParagraphsField(): void {
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'oe_paragraphs',
      'entity_type' => 'node',
      'type' => 'entity_reference_revisions',
      'cardinality' => '-1',
      'settings' => [
        'target_type' => 'paragraph',
      ],
    ]);
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'paragraphs_test',
      'settings' => [
        'handler' => 'default:paragraph',
        'handler_settings' => ['target_bundles' => NULL],
      ],
    ]);
    $field->save();

    $form_display = $this->container->get('entity_display.repository')->getFormDisplay('node', 'paragraphs_test');
    $form_display = $form_display->setComponent('oe_paragraphs', ['type' => 'paragraphs']);
    $form_display->save();
  }

}
