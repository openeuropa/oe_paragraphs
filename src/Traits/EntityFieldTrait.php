<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Traits;

/**
 * Helper methods for dealing with entity fields in Behat contexts.
 *
 * @todo This should be split off in a separate project so it can be reused.
 * @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-303
 * @see https://github.com/jhedstrom/drupalextension/issues/465
 */
trait EntityFieldTrait {

  /**
   * Returns the field names of the given type for the given entity and bundle.
   *
   * @param string $entity_type_id
   *   The ID of the entity type for which to return the field names.
   * @param string $bundle
   *   The ID of the bundle for which to return the field names.
   * @param string $field_type
   *   The machine name of the field type for which to return the field names.
   *
   * @return array
   *   An array of field names.
   */
  protected static function getFieldNamesByFieldType(string $entity_type_id, string $bundle, string $field_type): array {
    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager */
    $entity_field_manager = \Drupal::service('entity_field.manager');
    $field_map = $entity_field_manager->getFieldMapByFieldType($field_type);

    if (!empty($field_map[$entity_type_id])) {
      return array_keys(array_filter($field_map[$entity_type_id], function (array $field_info) use ($bundle) {
        return in_array($bundle, $field_info['bundles']);
      }));
    }

    return [];
  }

}
