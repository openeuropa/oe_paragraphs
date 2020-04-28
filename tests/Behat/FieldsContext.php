<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\Tests\oe_paragraphs\Traits\TraversingTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;
use PHPUnit\Framework\Assert;

/**
 * Provides extra steps definitions to handle fields.
 */
class FieldsContext extends RawDrupalContext {

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
   * @throws \Exception
   *   Thrown when the specified occurrence of the list or the list itself is
   *   not found.
   *
   * @Then I select :option from the :position :select
   */
  public function selectNthOption(string $option, string $position, string $select): void {
    $field = $this->unescapeStepArgument($select);
    $option = $this->unescapeStepArgument($option);
    $position = $this->convertOrdinalToNumber($position) - 1;

    // Find all the fields with the specified name.
    $fields = $this->getSession()->getPage()->findAll('named', ['field', $field]);
    if (!$fields || !isset($fields[$position])) {
      throw new \Exception(sprintf('Could not find field "%s" in position "%s".', $field, $position));
    }

    $fields[$position]->selectOption($option);
  }

}
