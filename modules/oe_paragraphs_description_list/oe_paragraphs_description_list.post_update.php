<?php

/**
 * @file
 * Post update functions for the OE Paragraphs module.
 */

declare(strict_types = 1);

use Drupal\paragraphs\Entity\ParagraphsType;

/**
 * Drop "horizontal" from description list paragraph labels.
 */
function oe_paragraphs_description_list_post_update_00001(array &$sandbox): void {
  $paragraph = ParagraphsType::load('oe_description_list');

  if ($paragraph !== NULL) {
    $paragraph->set('label', 'Description list');
    $paragraph->save();
  }
}
