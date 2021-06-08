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
