<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Traits;

use Drupal;
use RuntimeException;
/**
 * Helper methods for dealing with entity types in Behat contexts.
 *
 * @todo This should be split off in a separate project so it can be reused.
 * @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-303
 * @see https://github.com/jhedstrom/drupalextension/issues/465
 */
trait EntityTypeTrait {

  /**
   * Returns the entity type ID that matches the given human readable label.
   *
   * @param string $label
   *   The label of the entity type for which to retrieve the ID.
   *
   * @return string
   *   The entity type ID.
   */
  protected static function getEntityTypeIdByLabel(string $label): string {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = Drupal::entityTypeManager();

    foreach ($entity_type_manager->getDefinitions() as $definition) {
      if (strcasecmp((string) $definition->getLabel(), $label) === 0) {
        return $definition->id();
      }
    }

    throw new RuntimeException("There is no entity type with label '$label'.");
  }

  /**
   * Returns the ID of the bundle that matches the given human readable label.
   *
   * @param string $entity_type_id
   *   The ID of the entity type for which to retrieve the bundle ID.
   * @param string $label
   *   The label of the bundle for which to retrieve the ID.
   *
   * @return string
   *   The bundle ID.
   */
  protected static function getBundleIdByLabel(string $entity_type_id, string $label): string {
    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info */
    $entity_type_bundle = Drupal::service('entity_type.bundle.info');

    foreach ($entity_type_bundle->getBundleInfo($entity_type_id) as $bundle_id => $info) {
      if (strcasecmp($info['label'], $label) === 0) {
        return $bundle_id;
      }
    }

    throw new RuntimeException("The entity type '$entity_type_id' doesn\'t have a bundle with label '$label'.");
  }

  /**
   * Returns the label key for the given entity type.
   *
   * @param string $entity_type
   *   The entity type.
   *
   * @return string
   *   The label key.
   */
  protected static function getEntityTypeLabelKey(string $entity_type): string {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = Drupal::entityTypeManager();
    $label_key = $entity_type_manager->getDefinition($entity_type)->getKey('label');

    if (empty($label_key)) {
      throw new RuntimeException("The '$entity_type' entity type does not have a label key.");
    }

    return $label_key;
  }

  /**
   * Returns the bundle key for the given entity type.
   *
   * @param string $entity_type
   *   The entity type.
   *
   * @return string
   *   The bundle key.
   */
  protected static function getEntityTypeBundleKey(string $entity_type): string {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = Drupal::entityTypeManager();
    $bundle_key = $entity_type_manager->getDefinition($entity_type)->getKey('label');

    if (empty($bundle_key)) {
      throw new RuntimeException("The '$entity_type' entity type does not have a bundle key.");
    }

    return $bundle_key;
  }

}
