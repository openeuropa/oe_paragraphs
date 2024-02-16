<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs\Traits;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Helper methods for dealing with entity references in Behat contexts.
 *
 * @todo This should be split off in a separate project so it can be reused.
 * @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-303
 * @see https://github.com/jhedstrom/drupalextension/issues/465
 */
trait EntityReferenceTrait {

  /**
   * Returns the ID of the entity type that is targeted by the entity reference.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity for which to return the target entity ID.
   * @param string $field_name
   *   The entity reference field.
   *
   * @return string
   *   The target entity type ID.
   */
  protected static function getEntityReferenceTargetEntityId(ContentEntityInterface $entity, string $field_name): string {
    $field_definition = $entity->getFieldDefinition($field_name);

    return $field_definition->getFieldStorageDefinition()->getSetting('target_type');
  }

  /**
   * Returns the IDs of the bundles that are targeted by the entity reference.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity for which to return the target bundle IDs.
   * @param string $field_name
   *   The entity reference field.
   *
   * @return array
   *   The target bundle IDs.
   */
  protected static function getEntityReferenceTargetBundles(ContentEntityInterface $entity, string $field_name): array {
    $field_definition = $entity->getFieldDefinition($field_name);
    $handler_settings = $field_definition->getSetting('handler_settings');

    // If the list of target bundles is empty this means all bundles can be
    // targeted.
    if (empty($handler_settings['target_bundles'])) {
      /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info */
      $entity_type_bundle = \Drupal::service('entity_type.bundle.info');
      return array_keys($entity_type_bundle->getBundleInfo($field_definition->getFieldStorageDefinition()->getSetting('target_type')));
    }

    return array_keys($handler_settings['target_bundles']);
  }

}
