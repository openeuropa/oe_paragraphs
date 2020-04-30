<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\Tests\oe_paragraphs\Traits\FieldsTrait;
use Drupal\Tests\oe_paragraphs\Traits\TraversingTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Provides extra steps definitions to handle fields.
 */
class FieldsContext extends RawDrupalContext {

  use FieldsTrait;
  use TraversingTrait;
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
   * @Then I fill in the :position :field (field )with :value
   */
  public function fillNthField(string $position, string $field, string $value): void {
    $field = $this->unescapeStepArgument($field);
    $value = $this->unescapeStepArgument($value);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $this->getNthField($field, $position)->setValue($value);
  }

  /**
   * Checks that a select field has exclusively the provided options.
   *
   * @param string $select
   *   The name of the select element.
   * @param \Behat\Gherkin\Node\TableNode $table
   *   The list of expected options.
   *
   * @Then the available options in the :select select should be:
   */
  public function assertSelectOptions(string $select, TableNode $table): void {
    $field = $this->findSelect($this->unescapeStepArgument($select));
    $available_options = $this->getSelectOptions($field);
    sort($available_options);

    $options = array_map([$this, 'unescapeStepArgument'], $table->getColumn(0));
    sort($options);

    Assert::assertEquals($options, $available_options, "The '{$select}' select options don't match the expected ones.");
  }

  /**
   * Selects an option at a specific occurrence of a list.
   *
   * @param string $option
   *   The value to be selected.
   * @param string $position
   *   The ordinal position of the list amongst the ones with same label.
   * @param string $select
   *   The select element name.
   *
   * @Then I select :option from the :position :select
   */
  public function selectNthOption(string $option, string $position, string $select): void {
    $field = $this->unescapeStepArgument($select);
    $option = $this->unescapeStepArgument($option);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $this->getNthField($field, $position)->selectOption($option);
  }

  /**
   * Asserts that a field of a multi-value field item is marked as required.
   *
   * @param string $multi_value_field
   *   The multiple cardinality field name.
   * @param string $field
   *   The name of the item field to assert.
   * @param string $item
   *   The ordinal number of the item. Defaults to the first item.
   *
   * @throws \Exception
   *   Thrown when the element is not found.
   * @throws \PHPUnit\Framework\ExpectationFailedException
   *   Thrown when the element is not marked as required.
   *
   * @Then the :field field in the :item item of the :multi_value_field field should be marked as required
   * @Then the :field field in the :multi_value_field field item should be marked as required
   */
  public function assertMultipleCardinalityFieldItemFieldMarkedAsRequired(string $multi_value_field, string $field, string $item = '1st'): void {
    $multi_value_field = $this->unescapeStepArgument($multi_value_field);
    $field = $this->unescapeStepArgument($field);
    $item = $this->convertOrdinalToNumber($item) - 1;
    $field_table = $this->getMultipleCardinalityFieldTable($multi_value_field);
    $row = $this->findTableRow($field_table, $item);

    $element_node = $row->findField($field);
    if (!$element_node) {
      throw new \Exception(sprintf('Cannot find element "%s" inside the %s item of the field table "%s".', $field, $item, $multi_value_field));
    }

    if (!$element_node->hasAttribute('required')) {
      throw new ExpectationFailedException(sprintf('The element "%s" is not marked as required.', $field));
    }
  }

  /**
   * Asserts that a field of a multi-value field item is not marked as required.
   *
   * @param string $multi_value_field
   *   The multiple cardinality field name.
   * @param string $field
   *   The name of the item field to assert.
   * @param string $item
   *   The ordinal number of the item. Defaults to the first item.
   *
   * @throws \PHPUnit\Framework\ExpectationFailedException
   *   Thrown when the field is not marked as required.
   *
   * @Then the :field field in the :item item of the :multi_value_field field should not be marked as required
   * @Then the :field field in the :multi_value_field field item should not be marked as required
   */
  public function assertMultipleCardinalityFieldItemFieldNotMarkedAsRequired(string $multi_value_field, string $field, string $item = '1st'): void {
    try {
      $this->assertMultipleCardinalityFieldItemFieldMarkedAsRequired($multi_value_field, $field, $item);
    }
    catch (ExpectationFailedException $exception) {
      return;
    }

    throw new ExpectationFailedException(sprintf('The element "%s" is marked as required.', $field));
  }

}
