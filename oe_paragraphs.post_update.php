<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Config\FileStorage;

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
function oe_paragraphs_post_update_10001(array &$sandbox) {
  $field = FieldConfig::load('paragraph.oe_contextual_navigation.field_oe_links');

  if (!$field) {
    return t('Could not load the oe_links field in contextual navigation paragraph.');
  }

  $field->setRequired(TRUE);
  $field->setSetting('title', 2);
  $field->save();
}

/**
 * Installs Social media follow paragraph.
 */
function oe_paragraphs_post_update_10002(array &$sandbox): void {
  \Drupal::service('module_installer')->install(['typed_link']);

  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs') . '/config/post_updates/10002');

  \Drupal::entityTypeManager()->getStorage('paragraphs_type')
    ->create($storage->read('paragraphs.paragraphs_type.oe_social_media_follow'))
    ->save();

  $field_config = [
    'field.storage.paragraph.field_oe_social_media_variant',
    'field.storage.paragraph.field_oe_social_media_links',
    'field.field.paragraph.oe_social_media_follow.field_oe_title',
    'field.field.paragraph.oe_social_media_follow.field_oe_social_media_variant',
    'field.field.paragraph.oe_social_media_follow.field_oe_social_media_links',
    'core.entity_form_display.paragraph.oe_social_media_follow.default',
    'core.entity_view_display.paragraph.oe_social_media_follow.default',
  ];

  $config_manager = \Drupal::service('config.manager');
  $entity_manager = \Drupal::entityTypeManager();
  foreach ($field_config as $config) {
    $config_record = $storage->read($config);
    $entity_type = $config_manager->getEntityTypeIdByName($config);
    $entity_storage = $entity_manager->getStorage($entity_type);
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }
}

/**
 * Add Social media follow paragraph to Content row paragraph.
 *
 * Set Variant field required.
 */
function oe_paragraphs_post_update_10003(array &$sandbox): void {
  $paragraph_weights = [
    'oe_social_media_follow' => -17,
    'block_reference' => -18,
    'oe_quote' => -19,
    'oe_links_block' => -20,
    'oe_list_item_block' => -21,
    'oe_rich_text' => -22,
  ];
  // Add Social media follow paragraph to content row.
  $field = FieldConfig::load('paragraph.oe_content_row.field_oe_paragraphs');
  $handler_settings = $field->getSetting('handler_settings');
  if (isset($handler_settings['target_bundles'])) {
    $handler_settings['target_bundles']['oe_social_media_follow'] = 'oe_social_media_follow';
  }
  // Reorder paragraphs.
  foreach ($paragraph_weights as $paragraph => $weight) {
    if (isset($handler_settings['target_bundles_drag_drop'])) {
      $handler_settings['target_bundles_drag_drop'][$paragraph]['weight'] = $weight;
    }
  }
  $field->setSetting('handler_settings', $handler_settings);
  $field->save();

  // Set Variant field required.
  $field = FieldConfig::load('paragraph.oe_social_media_follow.field_oe_social_media_variant');
  $field->setRequired(TRUE);
  $field->save();
}

/**
 * Set Social media follow Variant field to required.
 */
function oe_paragraphs_post_update_10004(array &$sandbox): void {

}
