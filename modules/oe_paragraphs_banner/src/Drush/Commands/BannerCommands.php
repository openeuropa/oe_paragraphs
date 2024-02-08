<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_banner\Drush\Commands;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\oe_paragraphs_banner\BannerParagraphUpdater;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Updates Banner paragraph data.
 *
 * Migrates the "Banner type" field value to "Alignment" and "Size" fields.
 */
final class BannerCommands extends DrushCommands {

  use StringTranslationTrait;
  use DependencySerializationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Banner updater service.
   *
   * @var \Drupal\oe_paragraphs_banner\BannerParagraphUpdater
   */
  protected $bannerUpdater;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * UpdateBannerData class constructor.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, BannerParagraphUpdater $banner_updater, MessengerInterface $messenger) {
    parent::__construct();

    $this->entityTypeManager = $entityTypeManager;
    $this->bannerUpdater = $banner_updater;
    $this->messenger = $messenger;
  }

  /**
   * Return an instance of these Drush commands.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   *
   * @return \Drupal\oe_paragraphs_banner\Drush\Commands\BannerCommands
   *   The instance of Drush commands.
   */
  public static function create(ContainerInterface $container): BannerCommands {
    return new BannerCommands(
      $container->get('entity_type.manager'),
      $container->get('oe_paragraphs_banner.paragraph_updater'),
      $container->get('messenger'),
    );
  }

  /**
   * Triggers the update of the Banner paragraph data.
   */
  #[CLI\Command(name: 'oe-paragraphs-update-banner-data:run', aliases: [])]
  #[CLI\Usage(name: 'oe-paragraphs-update-banner-data:run', description: 'Updates Banner paragraph data.')]
  public function updateBannerData(): void {
    $ids = $this->entityTypeManager->getStorage('paragraph')->getQuery()
      ->condition('type', 'oe_banner')
      ->exists('field_oe_banner_type')
      ->allRevisions()
      ->accessCheck(FALSE)
      ->execute();
    if (!$ids) {
      return;
    }
    $batch_builder = (new BatchBuilder())
      ->setTitle($this->t('Update Banner paragraph data'))
      ->setFinishCallback([$this, 'processFinished']);
    $batch_builder->addOperation([$this, 'processUpdate'], [$ids]);
    batch_set($batch_builder->toArray());
    drush_backend_batch_process();
  }

  /**
   * Batch operation to process the update.
   *
   * @param array $ids
   *   The schedule Banner paragraph IDs.
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
        $this->bannerUpdater->updateParagraph($paragraph);
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
      $this->messenger->addStatus($this->t('No Banner paragraphs have been processed.'));
    }
    else {
      $this->messenger->addStatus($this->t('@items Banner paragraphs with following IDs have been processed: @ids.', [
        '@items' => $processed,
        '@ids' => implode(', ', $results['processed']),
      ]));
    }

    $failed = count($results['failed']);
    if ($failed) {
      $this->messenger->addStatus($this->t('Processing of the Banner paragraphs @ids failed.', [
        '@ids' => implode(', ', $results['failed']),
      ]));
    }
  }

}
