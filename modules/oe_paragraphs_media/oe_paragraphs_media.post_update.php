<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\file\FileStorage;

declare(strict_types = 1);

/**
 * Installs Banner paragraph.
 */
function oe_paragraphs_post_update_10001(array &$sandbox) {
  // If paragraph already exists, we bail out.
  $paragraph = \Drupal::entityTypeManager()->getStorage('paragraphs_type')->load('oe_banner');
  if ($paragraph) {
    return t('Banner paragraph exists, no action required.');
  }

  // Add paragraph form mode variants.
  $form_modes = [
    'paragraph.oe_banner_image' => 'Image banner',
    'paragraph.oe_banner_image_shade' => 'Image shade banner',
    'paragraph.oe_banner_primary' => 'Primary banner',
  ];
  foreach ($form_modes as $id => $label) {
    $form_mode = EntityFormMode::create([
      'id' => $id,
      'label' => $label,
      'targetEntityType' => 'paragraph',
    ]);
    $form_mode->save();
  }

  // Add Banner paragraph.
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs_media') . '/config/post_updates/10001');

  \Drupal::entityTypeManager()->getStorage('paragraphs_type')
    ->create($storage->read('paragraphs.paragraphs_type.oe_banner'))
    ->save();

  $config_ids = [
    'field.storage.paragraph.field_oe_banner_type',
    'field.field.paragraph.oe_banner.field_oe_title',
    'field.field.paragraph.oe_banner.field_oe_text',
    'field.field.paragraph.oe_banner.field_oe_link',
    'field.field.paragraph.oe_banner.field_oe_image',
    'field.field.paragraph.oe_banner.field_oe_banner_type',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_primary',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_image_shade',
    'core.entity_form_display.paragraph.oe_banner.oe_banner_image',
    'core.entity_form_display.paragraph.oe_banner.default',
    'core.entity_view_display.paragraph.oe_banner.default',
  ];

  $config_manager = \Drupal::service('config.manager');
  $entity_manager = \Drupal::entityTypeManager();
  foreach ($config_ids as $config_id) {
    $config_record = $storage->read($config_id);
    $config_entity_type = $config_manager->getEntityTypeIdByName($config_id);
    $entity_storage = $entity_manager->getStorage($config_entity_type);
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }

  return t('Banner paragraph has been installed.');
}
