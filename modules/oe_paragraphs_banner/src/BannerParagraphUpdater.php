<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_banner;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Updates a paragraph "Alignment" and "Size" fields from "Banner type".
 */
class BannerParagraphUpdater {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new BannerParagraphUpdater.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Updates the revisions of a paragraph with "Alignment" and "Size" values.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   The paragraph.
   */
  public function updateParagraph(ParagraphInterface $paragraph): void {
    if (!$paragraph->hasField('field_oe_banner_type')) {
      return;
    }

    $storage = $this->entityTypeManager->getStorage('paragraph');
    $ids = $storage->getQuery()
      ->allRevisions()
      ->condition('id', $paragraph->id())
      ->accessCheck(FALSE)
      ->execute();

    $revisions = $storage->loadMultipleRevisions(array_keys($ids));
    /** @var \Drupal\paragraphs\ParagraphInterface $revision */
    foreach ($revisions as $revision) {
      // If the field has no value we can skip it and leave the
      // 'field_oe_banner_alignment' and 'field_oe_banner_size' fields empty.
      if ($revision->get('field_oe_banner_type')->isEmpty()) {
        continue;
      }

      // Prevent 'double migration'.
      if (!$revision->get('field_oe_banner_alignment')->isEmpty() ||
        !$revision->get('field_oe_banner_size')->isEmpty()) {
        continue;
      }

      // Clone the revision onto original to make sure all the revisions get
      // updated.
      // @see https://www.drupal.org/project/drupal/issues/2859042
      $revision->original = clone $revision;

      // Get the value from the revision and determine the size and alignment.
      $banner_type = $revision->get('field_oe_banner_type')->value;
      if (str_contains($banner_type, 'page')) {
        $revision->set('field_oe_banner_size', 'medium');
      }
      else {
        $revision->set('field_oe_banner_size', 'large');
      }
      if (str_contains($banner_type, 'left')) {
        $revision->set('field_oe_banner_alignment', 'left');
      }
      else {
        $revision->set('field_oe_banner_alignment', 'centered');
      }

      // Save the value over the same revision.
      $revision->setNewRevision(FALSE);
      $revision->setSyncing(TRUE);
      $revision->save();
    }
  }

}
