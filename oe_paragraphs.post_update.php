<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\field\Entity\FieldConfig;

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
function oe_paragraphs_post_update_00001_make_oe_links_required_in_contextual_navigation(array &$sandbox): void {
  $field = FieldConfig::load('paragraph.oe_contextual_navigation.field_oe_links');

  if (!$field) {
    return t('Could not load the oe_links field in contextual navigation paragraph.');
  }

  $field->setRequired(TRUE);
  $field->setSetting('title', 2);
  $field->save();
}
