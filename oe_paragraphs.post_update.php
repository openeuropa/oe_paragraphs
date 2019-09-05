<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Fix description for limit field on contextual navigation paragraph.
 */
function oe_paragraphs_post_update_contextual_navigation_fix_description(array &$sandbox) {
  /** @var \Drupal\field\Entity\FieldConfig $field */
  $field = \Drupal::entityTypeManager()
    ->getStorage('field_config')
    ->load('paragraph.oe_contextual_navigation.field_oe_limit');

  if (!$field) {
    return t('Could not load limit field on contextual navigation paragraph.');
  }

  $field->set('description', 'The number of items to display. When empty, defaults to 4.');
  $field->save();
}

/**
 * Make oe_links required in contextual navigation.
 */
function oe_paragraphs_post_update_00001_make_oe_links_required_in_contextual_navigation(array &$sandbox) {
  $field = FieldConfig::load('paragraph.oe_contextual_navigation.field_oe_links');

  if (!$field) {
    return t('Could not load the oe_links field in contextual navigation paragraph.');
  }

  $field->setRequired(TRUE);
  $field->setSetting('title', 2);
  $field->save();
}

/**
 * Add new variant for the Content row type called "column Layout".
 */
function oe_paragraphs_post_update_00002_content_row_type_add_column_layout_variant() {
  // Create the storage for the "layout" field.
  if (!\Drupal::service('entity_type.manager')->getStorage('field_storage_config')->load('paragraph.field_oe_content_row_layout')) {
    FieldStorageConfig::create([
      'field_name' => 'field_oe_content_row_layout',
      'entity_type' => 'paragraph',
      'type' => 'list_string',
      'settings' => [
        'allowed_values' => [],
        'allowed_values_function' => '_oe_paragraphs_allowed_values_content_row_column_layout',
      ],
      'cardinality' => 1,
      'translatable' => TRUE,
    ])->save();
  }

  // Create the "layout" field.
  if (!\Drupal::service('entity_type.manager')->getStorage('field_config')->load('paragraph.oe_content_row.field_oe_content_row_layout')) {
    FieldConfig::create([
      'field_name' => 'field_oe_content_row_layout',
      'entity_type' => 'paragraph',
      'bundle' => 'oe_content_row',
      'label' => 'Layout',
      'description' => 'Column layout for displaying content items. If none selected, the content will be displayed in one column.',
      'required' => FALSE,
      'translatable' => FALSE,
      'settings' => [],
    ])->save();
  }

  // Create new form mode (Columns layout).
  if (!\Drupal::service('entity_type.manager')->getStorage('entity_form_mode')->load('paragraph.columns_layout')) {
    EntityFormMode::create([
      'id' => 'paragraph.columns_layout',
      'label' => 'Columns layout',
      'targetEntityType' => 'paragraph',
    ])->save();
  }

  // Setup the form display.
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs') . '/post_updates/00002_content_row_type_add_column_layout_variant');
  $form_display_values = $storage->read('core.entity_form_display.paragraph.oe_content_row.columns_layout');
  if (!EntityFormDisplay::load($form_display_values['id'])) {
    $form_display = EntityFormDisplay::create($form_display_values);
    $form_display->save();
  }
}
