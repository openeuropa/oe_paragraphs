<?php

/**
 * @file
 * Contains \OEParagraphsSubContext.
 */

declare(strict_types = 1);

use Behat\Gherkin\Node\TableNode;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\DrupalExtension\Context\DrupalSubContextBase;
use Drupal\oe_paragraphs\Traits\EntityFieldTrait;
use Drupal\oe_paragraphs\Traits\EntityReferenceTrait;
use Drupal\oe_paragraphs\Traits\EntityTrait;
use PHPUnit\Framework\Assert;

/**
 * Behat step definitions for testing the OE Paragraphs module.
 */
class OEParagraphsSubContext extends DrupalSubContextBase {

  use EntityFieldTrait;
  use EntityReferenceTrait;
  use EntityTrait;

  /**
   * Checks that the given entity bundle supports the given paragraph types.
   *
   * If there are multiple fields that reference paragraph types, only the first
   * field will be checked.
   *
   * @param string $entity_type_label
   *   The human readable label of the entity type to check.
   * @param string $bundle_label
   *   The human readable label of the bundle to check.
   * @param \Behat\Gherkin\Node\TableNode $table
   *   A table with a single column containing the human readable labels of the
   *   paragraph types that are expected to be present in the bundle.
   *
   * @Then the following paragraph types are available for :bundle_label :entity_type_label:
   */
  public function assertParagraphTypesExistInEntityBundle(string $entity_type_label, string $bundle_label, TableNode $table): void {
    // Retrieve the machine names of the entity type and bundle.
    $entity_type_id = $this->getEntityTypeIdByLabel($entity_type_label);
    $bundle_id = $this->getBundleIdByLabel($entity_type_id, $bundle_label);

    // Retrieve the machine names of the paragraph types.
    $exp_par_type_ids = array_map(function ($label) {
      return $this->getEntityIdByLabel('paragraphs_type', $label);
    }, $table->getColumn(0));

    // Create a new entity of the given type and bundle so we can verify if it
    // references the given paragraph types.
    $entity = $this->createNewEntity($entity_type_id, $bundle_id);

    // Don't bother continuing the test if this is a config entity.
    if (!$entity instanceof ContentEntityInterface) {
      throw new \RuntimeException("The '$entity_type_id' entity type cannot reference paragraph types since it is not a content entity.");
    }

    $field_names = $this->getFieldNamesByFieldType($entity_type_id, $bundle_id, 'entity_reference_revisions');
    foreach ($field_names as $field_name) {
      // Only check fields that reference paragraph types.
      if ($this->getEntityReferenceTargetEntityId($entity, $field_name) !== 'paragraph') {
        continue;
      };

      $actual_par_type_ids = $this->getEntityReferenceTargetBundles($entity, $field_name);

      // Check if the target paragraph types match the expected ones.
      Assert::assertEquals($exp_par_type_ids, $actual_par_type_ids);

      // We found the field and the paragraph types match the expectation, so
      // we're done.
      return;
    }

    // No field was found that references paragraph types.
    throw new \RuntimeException("The '$bundle_id' bundle of the '$entity_type_id' entity does not reference any paragraph types.");
  }

}
