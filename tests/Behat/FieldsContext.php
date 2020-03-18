<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
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
   * @param string $select
   *   The value to be selected.
   * @param string $position
   *   The ordinal position of the list amongst the ones with same label.
   * @param string $option
   *   The list of options.
   *
   * @throws \Exception
   *   Thrown when the specified occurrence of the list or the list itself is
   *   not found.
   *
   * @Then I select :select from the :position :option
   */
  public function selectNthOption(string $select, string $position, string $option): void {
    $field = $this->unescapeStepArgument($option);
    $select = $this->unescapeStepArgument($select);
    $position = $this->convertOrdinalToNumber($position) - 1;

    // Find all the fields with the specified name.
    $fields = $this->getSession()->getPage()->findAll('named', ['field', $field]);
    if (!$fields || !isset($fields[$position])) {
      throw new \Exception(sprintf('Could not find field "%s" in position "%s".', $field, $position));
    }

    $fields[$position]->selectOption($select);
  }

  /**
   * Step to fill in multi value fields with columns.
   *
   * @Given I fill in :column with :value in the :row :field field element
   */
  public function fillInMultivalueField($column, $value, $row, $field) {
    $table = $this->getMultiColumnFieldTable($field);
    $row_map = [
      'first' => '1',
      'second' => '2',
      'third' => '3',
      'fourth' => '4',
      'fifth' => '5',
      'sixth' => '6',
    ];

    $row = $table->find('xpath', "//tbody//tr[position()={$row_map[$row]}]");
    if (!$row) {
      throw new \Exception(sprintf('The %s row for the field %field could not be found.', $row, $field));
    }

    $row->fillField($column, $value);
  }

  /**
   * Finds the table that holds a multiple columned field.
   *
   * @param string $field
   *   The field.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The table element.
   */
  protected function getMultiColumnFieldTable(string $field): ?NodeElement {
    $xpath = '//table[contains(concat(" ", normalize-space(@class), " "), " field-multiple-table ")]/descendant::h4[contains(text(), ' . $field . ')]';
    $heading = $this->getSession()->getPage()->find('xpath', $xpath);

    if (!$heading) {
      throw new \Exception(sprintf('Table for %s field not found', $field));
    }

    return $heading->getParent()->getParent()->getParent()->getParent();
  }

}
