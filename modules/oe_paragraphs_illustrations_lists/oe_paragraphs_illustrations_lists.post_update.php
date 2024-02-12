<?php

/**
 * @file
 * Post update functions for the OE Paragraphs Illustrations Lists module.
 */

declare(strict_types=1);

use Drupal\Core\Config\FileStorage;

/**
 * Add new fields to Illustration Lists paragraphs.
 */
function oe_paragraphs_illustrations_lists_post_update_00001() {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_illustrations_lists') . '/config/post_updates/00001_new_fields');

  // Create "Highlight" field for the item paragraphs, the "Center the content"
  // for all 3 main paragraph types, and additionally for Illustration lists
  // with icons and images and a "Size" field too.
  foreach ($storage->listAll('field.storage') as $name) {
    _oe_paragraphs_import_config_from_file($name, $storage);
  }
  foreach ($storage->listAll('field.field') as $name) {
    _oe_paragraphs_import_config_from_file($name, $storage);
  }
}
