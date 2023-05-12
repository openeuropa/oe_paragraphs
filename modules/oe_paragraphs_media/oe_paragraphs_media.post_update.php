<?php

/**
 * @file
 * Post update functions for the OE Text with featured Media paragraph module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\Component\Utility\Crypt;
use Drupal\field\Entity\FieldConfig;

/**
 * Update fields in the Text with featured Media paragraph.
 *
 * Add "Title" and "Link" fields and rename existing "Title" field to "Heading".
 */
function oe_paragraphs_media_post_update_00001(): void {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_media') . '/config/post_updates/00001_add_link_title_fields');
  \Drupal::service('config.installer')->installOptionalConfig($storage);

  // Rename "Title" field to "Heading".
  $field_config = FieldConfig::load('paragraph.oe_text_feature_media.field_oe_title');
  $field_config->setLabel('Heading');
  $field_config->save();
}

/**
 * Create new form modes for Text with featured Media paragraph.
 */
function oe_paragraphs_media_post_update_00002(): void {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_media') . '/config/post_updates/00002_create_form_modes');
  $config_manager = \Drupal::service('config.manager');
  $entity_type_manager = \Drupal::entityTypeManager();

  // Create new form modes and form displays.
  $field_config = [
    'core.entity_form_mode.paragraph.left_featured',
    'core.entity_form_mode.paragraph.right_featured',
    'core.entity_form_mode.paragraph.right_simple',
    'core.entity_form_display.paragraph.oe_text_feature_media.left_featured',
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
    $config_record['_core']['default_config_hash'] = Crypt::hashBase64(serialize($config_record));
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

/**
 * Update default view display of Text with featured Media paragraph.
 */
function oe_paragraphs_media_post_update_00003(): void {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_media') . '/config/post_updates/00003_update_view_display');
  $display_values = $storage->read('core.entity_view_display.paragraph.oe_text_feature_media.default');
  $display_storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
  $display = $display_storage->load($display_values['id']);
  if ($display) {
    $display = $display_storage->updateFromStorageRecord($display, $display_values);
    $display->save();
  }
}

/**
 * Add Highlighted field to Text with Featured media paragraph.
 */
function oe_paragraphs_media_post_update_00004() {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_media') . '/config/post_updates/00004_highlighted_field');
  $config_manager = \Drupal::service('config.manager');
  $entity_type_manager = \Drupal::entityTypeManager();
  $configs = [
    'field.storage.paragraph.field_oe_highlighted',
    'field.field.paragraph.oe_text_feature_media.field_oe_highlighted',
  ];
  foreach ($configs as $config_id) {
    $config_record = $storage->read($config_id);
    $entity_type = $config_manager->getEntityTypeIdByName($config_id);
    $entity_storage = $entity_type_manager->getStorage($entity_type);
    $entity = $entity_storage->load($config_record['id']);
    if ($entity) {
      // Skip if configuration exists.
      continue;
    }
    $config_record['_core']['default_config_hash'] = Crypt::hashBase64(serialize($config_record));
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }
}
