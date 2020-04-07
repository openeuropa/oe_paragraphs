<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\Core\Entity\Entity\EntityFormDisplay;

/**
 * Adds new field "Display as full width" to Banner paragraph.
 */
function oe_paragraphs_banner_post_update_00001() {
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs_banner') . '/config/post_updates/00001_add_full_width_field');
  $field_configs = [
    'field.storage.paragraph.field_oe_banner_full_width',
    'field.field.paragraph.oe_banner.field_oe_banner_full_width',
  ];
  $config_manager = \Drupal::service('config.manager');
  $entity_manager = \Drupal::entityTypeManager();
  foreach ($field_configs as $field_config) {
    $config_record = $storage->read($field_config);
    $entity_type = $config_manager->getEntityTypeIdByName($field_config);
    $entity_storage = $entity_manager->getStorage($entity_type);
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }

  $form_displays = [
    'core.entity_form_display.paragraph.oe_banner.default',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_image',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_image_shade',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_primary',
  ];

  foreach ($form_displays as $form_display) {
    $values = $storage->read($form_display);
    $display = EntityFormDisplay::load($values['id']);
    if ($display) {
      foreach ($values as $key => $value) {
        $display->set($key, $value);
      }
      $display->save();
    }
  }
}
