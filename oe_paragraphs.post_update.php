<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\paragraphs\Entity\ParagraphsType;

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

  \Drupal::service('plugin.manager.field.field_type')->clearCachedDefinitions();
  \Drupal::service('plugin.manager.field.formatter')->clearCachedDefinitions();
  \Drupal::service('plugin.manager.field.widget')->clearCachedDefinitions();

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
 * Set Variant field required for Social media follow paragraph.
 */
function oe_paragraphs_post_update_10003(array &$sandbox): void {
  $field = FieldConfig::load('paragraph.oe_social_media_follow.field_oe_social_media_variant');
  $field->setRequired(TRUE);
  $field->save();
}

/**
 * Add optional link field to Social media follow paragraph.
 */
function oe_paragraphs_post_update_10004(array &$sandbox): void {
  \Drupal::service('module_installer')->install(['link']);

  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs') . '/config/post_updates/10004');
  $field_configs = [
    'field.storage.paragraph.field_oe_social_media_see_more',
    'field.field.paragraph.oe_social_media_follow.field_oe_social_media_see_more',
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

  $values = $storage->read('core.entity_form_display.paragraph.oe_social_media_follow.default');
  $display = EntityFormDisplay::load($values['id']);
  if ($display) {
    foreach ($values as $key => $value) {
      $display->set($key, $value);
    }
    $display->save();
  }

  $values = $storage->read('core.entity_view_display.paragraph.oe_social_media_follow.default');
  $display = EntityViewDisplay::load($values['id']);
  if ($display) {
    foreach ($values as $key => $value) {
      $display->set($key, $value);
    }
    $display->save();
  }
}

/**
 * Installs Facts and figures and Fact paragraphs.
 */
function oe_paragraphs_post_update_10005(array &$sandbox) {
  // If paragraph already exists, we bail out.
  $paragraph = \Drupal::entityTypeManager()->getStorage('paragraphs_type')->load('oe_facts_figures');
  if ($paragraph) {
    return t('Facts and figures paragraph exists, no action required.');
  }

  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs') . '/config/post_updates/10005');
  $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraphs_type');

  // Create the paragraphs.
  $paragraphs = [
    'paragraphs.paragraphs_type.oe_fact',
    'paragraphs.paragraphs_type.oe_facts_figures',
  ];
  foreach ($paragraphs as $paragraph) {
    $paragraph_storage->create($storage->read($paragraph))
      ->save();
  }

  // Configure fields and entity form and view displays for both paragraphs.
  $config_ids = [
    'field.storage.paragraph.field_oe_subtitle',
    'field.field.paragraph.oe_fact.field_oe_icon',
    'field.field.paragraph.oe_fact.field_oe_plain_text_long',
    'field.field.paragraph.oe_fact.field_oe_subtitle',
    'field.field.paragraph.oe_fact.field_oe_title',
    'field.field.paragraph.oe_facts_figures.field_oe_link',
    'field.field.paragraph.oe_facts_figures.field_oe_paragraphs',
    'field.field.paragraph.oe_facts_figures.field_oe_title',
    'core.entity_form_display.paragraph.oe_fact.default',
    'core.entity_form_display.paragraph.oe_facts_figures.default',
    'core.entity_view_display.paragraph.oe_fact.default',
    'core.entity_view_display.paragraph.oe_facts_figures.default',
  ];

  $config_manager = \Drupal::service('config.manager');
  $entity_manager = \Drupal::entityTypeManager();
  foreach ($config_ids as $config_id) {
    $config_record = $storage->read($config_id);
    $entity_type = $config_manager->getEntityTypeIdByName($config_id);
    $entity_storage = $entity_manager->getStorage($entity_type);
    $entity = $entity_storage->createFromStorageRecord($config_record);
    $entity->save();
  }

  return t('Facts and figures and Fact paragraphs have been installed.');
}

/**
 * Marks unsupported ECL icons as deprecated.
 */
function oe_paragraphs_post_update_10006(array &$sandbox): void {
  $field = \Drupal::entityTypeManager()->getStorage('field_storage_config')->load('paragraph.field_oe_icon');
  $settings = $field->get('settings');
  $icons = [
    'googleplus',
    'slides',
  ];
  foreach ($icons as $icon) {
    if (array_key_exists($icon, $settings['allowed_values'])) {
      $settings['allowed_values'][$icon] = $settings['allowed_values'][$icon] . ' (deprecated)';
    }
  }
  $field->set('settings', $settings);
  $field->save();
}

/**
 * Update field_oe_icon field to retrieve allowed values from an event.
 */
function oe_paragraphs_post_update_10007(array &$sandbox) {
  $entity_storage = \Drupal::entityTypeManager()->getStorage('field_storage_config');
  $entity = $entity_storage->load('paragraph.field_oe_icon');

  if (!$entity) {
    return 'Field storage "paragraph.field_oe_icon" not found.';
  }

  /** @var \Drupal\field\FieldStorageConfigInterface $entity */
  $entity->setSetting('allowed_values_function', '_oe_paragraphs_allowed_values_icons');
  $entity->save();
}

/**
 * Create field_oe_flag field storage.
 */
function oe_paragraphs_post_update_10008(array &$sandbox) {
  $entity_storage = \Drupal::entityTypeManager()->getStorage('field_storage_config');
  $entity = $entity_storage->load('paragraph.field_oe_flag');

  if ($entity) {
    return 'Field storage "paragraph.field_oe_flag" already exists.';
  }

  // Create field storage if it doesn't exist.
  $storage = new FileStorage(drupal_get_path('module', 'oe_paragraphs') . '/config/post_updates/10008');
  $config_record = $storage->read('field.storage.paragraph.field_oe_flag');
  $entity = $entity_storage->createFromStorageRecord($config_record);
  $entity->save();
}

/**
 * Drop "horizontal" from description list paragraph labels.
 */
function oe_paragraphs_post_update_10009(array &$sandbox): void {
  $paragraph = ParagraphsType::load('oe_description_list');

  if ($paragraph !== NULL) {
    $paragraph->set('label', 'Description list');
    $paragraph->save();
  }
}
