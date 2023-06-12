<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_carousel;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Updates a paragraph's "Size" field.
 */
class CarouselParagraphUpdater {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new CarouselParagraphUpdater.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Updates the revisions of a paragraph with "Size" value.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   The paragraph.
   */
  public function updateParagraph(ParagraphInterface $paragraph): void {
    $storage = $this->entityTypeManager->getStorage('paragraph');
    $ids = $storage->getQuery()
      ->allRevisions()
      ->condition('id', $paragraph->id())
      ->accessCheck(FALSE)
      ->execute();

    $revisions = $storage->loadMultipleRevisions(array_keys($ids));
    /** @var \Drupal\paragraphs\ParagraphInterface $revision */
    foreach ($revisions as $revision) {
      // Prevent 'double migration'.
      if (!$revision->get('field_oe_carousel_size')->isEmpty()) {
        continue;
      }
      // Clone the revision onto original to make sure all the revisions get
      // updated.
      // @see https://www.drupal.org/project/drupal/issues/2859042
      $revision->original = clone $revision;
      $revision->set('field_oe_carousel_size', 'medium');
      // Save the value over the same revision.
      $revision->setNewRevision(FALSE);
      $revision->setSyncing(TRUE);
      $revision->save();
    }
  }

}
