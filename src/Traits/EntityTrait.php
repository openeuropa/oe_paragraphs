<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Traits;

use Drupal;
use RuntimeException;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Entity\EntityInterface;

/**
 * Helper methods for dealing with entities in Behat contexts.
 *
 * This trait depends on EntityTypeTrait. If you need to use methods from both
 * traits then make sure not to include EntityTypeTrait in your class or you
 * will get a collision.
 *
 * @todo This should be split off in a separate project so it can be reused.
 * @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-303
 * @see https://github.com/jhedstrom/drupalextension/issues/465
 */
trait EntityTrait {

  use EntityTypeTrait;

  /**
   * Creates a new entity of the given entity type and bundle.
   *
   * @param string $entity_type_id
   *   The type of the entity to create.
   * @param string $bundle
   *   The bundle of the entity to create.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The new unsaved entity.
   */
  protected static function createNewEntity(string $entity_type_id, string $bundle): EntityInterface {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = Drupal::entityTypeManager();

    try {
      $entity_storage = $entity_type_manager->getStorage($entity_type_id);
    }
    catch (InvalidPluginDefinitionException $e) {
      throw new RuntimeException("The entity type '$entity_type_id' is not defined.", 0, $e);
    }

    $bundle_key = $entity_storage->getEntityType()->getKey('bundle');

    return $entity_storage->create([$bundle_key => $bundle]);
  }

  /**
   * Retrieves an entity by its label.
   *
   * If there are multiple entities with the same label, the ID of one of them
   * will be returned.
   *
   * @param string $entity_type_id
   *   The type of the entity.
   * @param string $label
   *   The label of the entity we are searching for.
   * @param array $bundle
   *   Optional bundle to search on.
   *
   * @return string|null
   *   The entity ID, or NULL if no entity was found with the given label.
   */
  protected function getEntityIdByLabel(string $entity_type_id, string $label, array $bundle = []): ?string {
    $label_key = $this->getEntityTypeLabelKey($entity_type_id);
    $query = Drupal::entityQuery($entity_type_id)
      ->condition($label_key, $label)
      ->range(0, 1);

    if (!empty($bundle)) {
      $bundle_key = $this->getEntityTypeBundleKey($entity_type_id);
      $query->condition($bundle_key, $bundle, 'IN');
    }

    $result = $query->execute();

    return reset($result);
  }

}
