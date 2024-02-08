<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_carousel\Commands;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\oe_paragraphs_carousel\CarouselParagraphUpdater;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Sets default "Size" value for existing Carousel paragraphs.
 */
final class UpdateCarouselData extends DrushCommands {

  use StringTranslationTrait;
  use DependencySerializationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Carousel updater service.
   *
   * @var \Drupal\oe_paragraphs_carousel\CarouselParagraphUpdater
   */
  protected $carouselUpdater;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * UpdateCarouselData class constructor.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CarouselParagraphUpdater $carousel_updater, MessengerInterface $messenger) {
    parent::__construct();

    $this->entityTypeManager = $entityTypeManager;
    $this->carouselUpdater = $carousel_updater;
    $this->messenger = $messenger;
  }

  /**
   * Return an instance of these Drush commands.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   *
   * @return \Drupal\oe_paragraphs_carousel\Commands\UpdateCarouselData
   *   The instance of Drush commands.
   */
  public static function create(ContainerInterface $container): UpdateCarouselData {
    return new UpdateCarouselData(
      $container->get('entity_type.manager'),
      $container->get('oe_paragraphs_carousel.paragraph_updater'),
      $container->get('messenger'),
    );
  }

  /**
   * Triggers the update of the Carousel paragraph data.
   */
  #[CLI\Command(name: 'oe-paragraphs-update-carousel-data:run', aliases: [])]
  #[CLI\Usage(name: 'oe-paragraphs-update-carousel-data:run', description: 'Updates Carousel paragraph data.')]
  public function updateCarouselData(): void {
    $ids = $this->entityTypeManager->getStorage('paragraph')->getQuery()
      ->condition('type', 'oe_carousel')
      ->allRevisions()
      ->accessCheck(FALSE)
      ->execute();
    if (!$ids) {
      return;
    }
    $batch_builder = (new BatchBuilder())
      ->setTitle($this->t('Update Carousel paragraph data'))
      ->setFinishCallback([$this, 'processFinished']);
    $batch_builder->addOperation([$this, 'processUpdate'], [$ids]);
    batch_set($batch_builder->toArray());
    drush_backend_batch_process();
  }

  /**
   * Batch operation to process the update.
   *
   * @param array $ids
   *   The schedule Carousel paragraph IDs.
   * @param array|DrushBatchContext $context
   *   The batch context.
   */
  public function processUpdate(array $ids, &$context): void {
    if (!isset($context['results']['processed'])) {
      $context['results']['processed'] = [];
      $context['results']['failed'] = [];
    }

    $sandbox = &$context['sandbox'];
    if (!$sandbox) {
      $sandbox['current'] = 0;
      $sandbox['ids'] = array_unique($ids);
      $sandbox['total'] = count($sandbox['ids']);
    }

    if ($sandbox['ids']) {
      $id = array_pop($sandbox['ids']);
      $paragraph = $this->entityTypeManager->getStorage('paragraph')->load($id);
      try {
        $this->carouselUpdater->updateParagraph($paragraph);
        $context['results']['processed'][] = $paragraph->id();
      }
      catch (\Exception $e) {
        $context['results']['failed'][] = $paragraph->id();
      }
    }

    $sandbox['current']++;

    $context['finished'] = $sandbox['current'] / $sandbox['total'];
  }

  /**
   * Callback for when the batch processing completes.
   *
   * @param bool $success
   *   Whether the batch was successful.
   * @param array $results
   *   The batch results.
   * @param array $operations
   *   The batch operations.
   */
  public function processFinished(bool $success, array $results, array $operations): void {
    if (!$success) {
      $this->messenger->addError($this->t('There was a problem with the batch'));
      return;
    }

    $processed = count($results['processed']);
    if ($processed === 0) {
      $this->messenger->addStatus($this->t('No Carousel paragraphs have been processed.'));
    }
    else {
      $this->messenger->addStatus($this->t('@items Carousel paragraphs with following IDs have been processed: @ids.', [
        '@items' => $processed,
        '@ids' => implode(', ', $results['processed']),
      ]));
    }

    $failed = count($results['failed']);
    if ($failed) {
      $this->messenger->addStatus($this->t('Processing of the Carousel paragraphs @ids failed.', [
        '@ids' => implode(', ', $results['failed']),
      ]));
    }
  }

}
