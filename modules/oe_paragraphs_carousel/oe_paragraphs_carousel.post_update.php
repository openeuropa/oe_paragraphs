<?php

/**
 * @file
 * Post update functions for the OE Paragraphs Carousel module.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;

/**
 * Adds new "Size" field to Carousel paragraph.
 */
function oe_paragraphs_carousel_post_update_00001(): void {
  $storage = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_paragraphs_carousel') . '/config/post_updates/00001_size_field');
  // Create the new "Size" field and add it to the form.
  _oe_paragraphs_import_config_from_file('field.storage.paragraph.field_oe_carousel_size', $storage);
  _oe_paragraphs_import_config_from_file('field.field.paragraph.oe_carousel.field_oe_carousel_size', $storage);
  _oe_paragraphs_import_config_from_file('core.entity_form_display.paragraph.oe_carousel.default', $storage);
}
