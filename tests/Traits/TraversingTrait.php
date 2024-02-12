<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

use Behat\Mink\Element\NodeElement;

/**
 * Helper methods to deal with traversing of page elements.
 */
trait TraversingTrait {

  /**
   * Retrieves a select field by label.
   *
   * @param string $select
   *   The name of the select element.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The select element.
   *
   * @throws \Exception
   *   Thrown when no select field is found.
   */
  protected function findSelect($select): NodeElement {
    /** @var \Behat\Mink\Element\NodeElement $element */
    $element = $this->getSession()->getPage()->find('named', ['select', $select]);

    if (empty($element)) {
      throw new \Exception("Select field '{$select}' not found.");
    }

    return $element;
  }

  /**
   * Retrieves the options of a select field.
   *
   * @param \Behat\Mink\Element\NodeElement $select
   *   The select element.
   *
   * @return array
   *   The options text keyed by option value.
   */
  protected function getSelectOptions(NodeElement $select): array {
    $options = [];
    foreach ($select->findAll('xpath', '//option') as $element) {
      /** @var \Behat\Mink\Element\NodeElement $element */
      $options[$element->getValue()] = trim($element->getText());
    }

    return $options;
  }

  /**
   * Retrieves a row of a table element.
   *
   * @param \Behat\Mink\Element\NodeElement $table
   *   The table element.
   * @param int $index
   *   The row number. Zero-based index.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The row element.
   *
   * @throws \Exception
   *   Throw when the specified row is not found.
   */
  protected function findTableRow(NodeElement $table, int $index): NodeElement {
    $row = $table->find('xpath', '/tbody/tr[' . ($index + 1) . ']');

    if (empty($row)) {
      throw new \Exception('Could not find row %s in table.', $index);
    }

    return $row;
  }

}
