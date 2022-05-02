<?php

/**
 * @file
 * OpenEuropa Paragraphs Contact post updates.
 */

declare(strict_types = 1);

use Drupal\field\Entity\FieldConfig;

/**
 * Enable "composite revisions" option for "Contacts" field.
 */
function oe_paragraphs_contact_post_update_00001(): void {
  $field_config = FieldConfig::load('paragraph.oe_contact.field_oe_contacts');
  $field_config->setThirdPartySetting('composite_reference', 'composite_revisions', TRUE);
  $field_config->save();
}
