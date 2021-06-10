<?php

/**
 * @file
 * Post update functions for the OE Text with featured Media paragraph module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\field\Entity\FieldConfig;

/**
 * Add Link field to Text with featured Media paragraph.
 */
function oe_paragraphs_media_post_update_00001(array &$sandbox) {
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs_media') . '/config/post_updates/00001_add_link_field');

  $field_config = 'field.field.paragraph.oe_text_feature_media.field_oe_link';
  $config_record = $storage->read($field_config);
  $field = FieldConfig::load($config_record['id']);
  if ($field) {
    // Bail out if the field already exists.
    return t('The field_oe_link already exists.');
  }
  $field_config_storage = \Drupal::entityTypeManager()->getStorage('field_config');
  $field = $field_config_storage->createFromStorageRecord($config_record);
  $field->save();

  return t('The Link field has been created for Text with featured Media paragraph.');
}

/**
 * Create new form modes for Text with featured Media paragraph.
 */
function oe_paragraphs_media_post_update_00002(): void {
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs_media') . '/config/post_updates/00002_create_form_modes');
  $config_manager = \Drupal::service('config.manager');
  $entity_type_manager = \Drupal::entityTypeManager();

  // Create new form modes and form displays.
  $field_config = [
    'core.entity_form_mode.paragraph.left_featured',
    'core.entity_form_mode.paragraph.left_simple',
    'core.entity_form_mode.paragraph.right_featured',
    'core.entity_form_mode.paragraph.right_simple',
    'core.entity_form_display.paragraph.oe_text_feature_media.left_featured',
    'core.entity_form_display.paragraph.oe_text_feature_media.left_simple',
    'core.entity_form_display.paragraph.oe_text_feature_media.right_featured',
    'core.entity_form_display.paragraph.oe_text_feature_media.right_simple',
  ];
  foreach ($field_config as $config) {
    $config_record = $storage->read($config);
    $entity_type = $config_manager->getEntityTypeIdByName($config);
    $entity_storage = $entity_type_manager->getStorage($entity_type);
    $entity = $entity_storage->load($config_record['id']);
    if ($entity) {
      // Skip if configuration exists.
      continue;
    }
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }

  // Update existing default form display.
  $form_values = $storage->read('core.entity_form_display.paragraph.oe_text_feature_media.default');
  $form_display_storage = $entity_type_manager->getStorage('entity_form_display');
  $form_display = $form_display_storage->load($form_values['id']);
  if ($form_display) {
    $form_display = $form_display_storage->updateFromStorageRecord($form_display, $form_values);
    $form_display->save();
  }
}
