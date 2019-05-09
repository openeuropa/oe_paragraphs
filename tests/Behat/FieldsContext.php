<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;

/**
 * Provides extra steps definitions to handle fields.
 */
class FieldsContext extends RawDrupalContext {

  use UtilityTrait;

  /**
   * Fills a specific occurrence of a field with a value.
   *
   * @param string $position
   *   The ordinal position of the field amongst the ones with same label.
   * @param string $field
   *   The field label.
   * @param string $value
   *   The field value.
   *
   * @throws \Exception
   *   Thrown when the specified occurrence of the field or the field itself is
   *   not found.
   *
   * @Then I fill in the :position :field (field )with :value
   */
  public function fillNthField(string $position, string $field, string $value): void {
    $field = $this->unescapeStepArgument($field);
    $value = $this->unescapeStepArgument($value);
    $position = $this->convertOrdinalToNumber($position) - 1;

    // Find all the fields with the specified name.
    $fields = $this->getSession()->getPage()->findAll('named', ['field', $field]);

    if (!$fields || !isset($fields[$position])) {
      throw new \Exception(sprintf('Could not find field "%s" in position "%s".', $field, $position));
    }

    $fields[$position]->setValue($value);
  }

}
