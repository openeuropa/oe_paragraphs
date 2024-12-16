<?php

/**
 * @file
 * Post update functions for OpenEuropa Paragraphs Social feed module.
 */

declare(strict_types=1);

/**
 * Mark the Social feed paragraph as deprecated.
 */
function oe_paragraphs_social_feed_post_update_00001(): void {
  /** @var \Drupal\paragraphs\ParagraphInterface $paragraph_type */
  $paragraph_type = \Drupal::entityTypeManager()->getStorage('paragraphs_type')->load('oe_social_feed');
  if ($paragraph_type->get('label') === 'Social feed') {
    $paragraph_type->set('label', 'Social feed - Deprecated');
  }
  if ($paragraph_type->get('description') === 'The Social feed paragraph allows editors to add Webtools social feed on the page.') {
    $paragraph_type->set('description', 'The Social feed paragraph allows editors to add Webtools social feed on the page - The Webtools smk service is no longer supported.');
  }
  $paragraph_type->save();
}
