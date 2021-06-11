<?php

/**
 * @file
 * Post update functions for the OE Paragraphs Timeline module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\field\Entity\FieldConfig;

/**
 * Add optional heading field to Timeline paragraph.
 */
function oe_paragraphs_timeline_post_update_00001(array &$sandbox) {
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs_timeline') . '/config/post_updates/00001_heading_field');

  $field_config = 'field.field.paragraph.oe_timeline.field_oe_title';
  $config_record = $storage->read($field_config);
  $field = FieldConfig::load($config_record['id']);
  if ($field) {
    // Bail out if the field already exists.
    return t('The field_oe_title already exists.');
  }
  $field_config_storage = Drupal::entityTypeManager()->getStorage('field_config');
  $field = $field_config_storage->createFromStorageRecord($config_record);
  $field->save();

  return t('The heading field has been created for Timeline paragraph.');
}
