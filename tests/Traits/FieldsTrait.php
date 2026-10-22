<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

use Behat\Mink\Element\NodeElement;

/**
 * Helper methods to deal with fields.
 */
trait FieldsTrait {

  /**
   * Returns the n-th occurrence of a field in a page.
   *
   * @param string $field
   *   The field element label.
   * @param int $position
   *   The position of the field amongst the ones with same label. Zero-based
   *   index.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The field element.
   *
   * @throws \Exception
   *   Thrown when the specified occurrence of the field or the field itself is
   *   not found.
   */
  protected function getNthField(string $field, int $position): NodeElement {
    $fields = $this->getSession()->getPage()->findAll('named', ['field', $field]);

    if (!$fields || !isset($fields[$position])) {
      throw new \Exception(sprintf('Could not find field "%s" in position "%s".', $field, $position));
    }

    return $fields[$position];
  }

  /**
   * Returns the table of a multiple cardinality field given its label.
   *
   * @param string $field
   *   The field label.
   * @param int $position
   *   The position of the field amongst the ones with same label. Zero-based
   *   index. Defaults to 0 (first occurrence). Optional.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The table element.
   *
   * @throws \Exception
   *   Thrown when no field table is found.
   */
  protected function getMultipleCardinalityFieldTable(string $field, int $position = 0): NodeElement {
    // This method requires the invoking class to include the UtilityTrait.
    assert(method_exists($this, 'xpathHasClassSelector'), sprintf('The calling class must use the %s trait.', UtilityTrait::class));

    // Find the table by its header.
    $xpath = '//table' . $this->xpathHasClassSelector('field-multiple-table')
      . '[./thead//h4[text()="' . $field . '"]]';
    $tables = $this->getSession()->getPage()->findAll('xpath', $xpath);

    if (empty($tables) || !isset($tables[$position])) {
      throw new \Exception(sprintf('Could not find field table "%s" in position "%s".', $field, $position));
    }

    return $tables[$position];
  }

  /**
   * Finds a field in a container.
   *
   * Extends the TraversableElement::findField to cover extra scenarios, such as
   * managed fields styled by the Claro admin theme.
   *
   * @param string $label
   *   The field label.
   * @param \Behat\Mink\Element\NodeElement|null $container
   *   The container element. If left empty, the page will be used.
   *
   * @return \Behat\Mink\Element\NodeElement|null
   *   The field if found, NULL otherwise.
   */
  protected function findField(string $label, ?NodeElement $container = NULL): ?NodeElement {
    $container = $container ?? $this->getSession()->getPage();

    // Try to find the field with the standard method.
    $field = $container->findField($label);
    if ($field) {
      return $field;
    }

    // Claro theme wraps all managed files, even with single cardinality, in a
    // <details> element.
    // Find a details element with the given label.
    $details = $container->find('xpath', sprintf('//details[./summary[.="%s"]]', $label));
    if (!$details) {
      return NULL;
    }

    // Make sure that the details element contains a managed file.
    $form_item = $details->find('css', '.form-type--managed-file');
    if (!$form_item) {
      return NULL;
    }

    // Finally, the image field is present if the upload field is found.
    return $form_item->findField('Add a new file');
  }

  /**
   * Asserts that the given fields are present inside a region of the page.
   *
   * @param string[] $fields
   *   The field labels.
   * @param string $region
   *   The CSS selector of the region.
   */
  protected function assertFieldsPresent(array $fields, string $region): void {
    $container = $this->assertSession()->elementExists('css', $region);
    $not_found = [];
    foreach ($fields as $field) {
      if ($this->findField($field, $container) === NULL) {
        $not_found[] = $field;
      }
    }
    $this->assertEmpty($not_found, sprintf('Field(s) expected, but not found: %s', implode(', ', $not_found)));
  }

  /**
   * Asserts that the given fields are not present inside a region of the page.
   *
   * @param string[] $fields
   *   The field labels.
   * @param string $region
   *   The CSS selector of the region.
   */
  protected function assertFieldsNotPresent(array $fields, string $region): void {
    $container = $this->assertSession()->elementExists('css', $region);
    $found = [];
    foreach ($fields as $field) {
      if ($this->findField($field, $container) !== NULL) {
        $found[] = $field;
      }
    }
    $this->assertEmpty($found, sprintf('Field(s) should not be found, but are present: %s', implode(', ', $found)));
  }

  /**
   * Asserts that a select field has exclusively the provided options.
   *
   * The order of the options is not taken into account.
   *
   * @param string $select
   *   The select field locator.
   * @param string[] $expected
   *   The expected option labels.
   */
  protected function assertSelectOptions(string $select, array $expected): void {
    $actual = array_values($this->getSelectOptions($this->findSelect($select)));
    sort($actual);
    sort($expected);
    $this->assertEquals($expected, $actual, sprintf('The "%s" select options don\'t match the expected ones.', $select));
  }

  /**
   * Fills a specific occurrence of a field with a value.
   *
   * @param string $field
   *   The field label.
   * @param int $position
   *   The position of the field amongst the ones with same label. 1-based.
   * @param string $value
   *   The field value.
   */
  protected function fillNthField(string $field, int $position, string $value): void {
    $this->getNthField($field, $position - 1)->setValue($value);
  }

  /**
   * Selects an option in a specific occurrence of a select.
   *
   * @param string $select
   *   The select label.
   * @param int $position
   *   The position of the select amongst the ones with same label. 1-based.
   * @param string $option
   *   The option to select.
   */
  protected function selectNthOption(string $select, int $position, string $option): void {
    $this->getNthField($select, $position - 1)->selectOption($option);
  }

  /**
   * Fills a field of an item of a multiple cardinality field.
   *
   * @param string $multi_value_field
   *   The multiple cardinality field label.
   * @param int $item
   *   The item number. 1-based.
   * @param string $field
   *   The label of the item field to fill.
   * @param string $value
   *   The value.
   */
  protected function fillMultipleCardinalityFieldItem(string $multi_value_field, int $item, string $field, string $value): void {
    $this->getMultipleCardinalityFieldItem($multi_value_field, $item)->fillField($field, $value);
  }

  /**
   * Asserts the required state of a field in a multiple cardinality item.
   *
   * @param string $multi_value_field
   *   The multiple cardinality field label.
   * @param int $item
   *   The item number. 1-based.
   * @param string $field
   *   The label of the item field to check.
   * @param bool $required
   *   Whether the field is expected to be marked as required.
   */
  protected function assertMultipleCardinalityFieldItemRequired(string $multi_value_field, int $item, string $field, bool $required): void {
    $element = $this->getMultipleCardinalityFieldItem($multi_value_field, $item)->findField($field);
    $this->assertNotNull($element, sprintf('Cannot find element "%s" inside item %s of the field table "%s".', $field, $item, $multi_value_field));
    $this->assertSame($required, $element->hasAttribute('required'), sprintf('The element "%s" in item %s is %smarked as required.', $field, $item, $required ? 'not ' : ''));
  }

  /**
   * Returns the row of an item of a multiple cardinality field.
   *
   * @param string $multi_value_field
   *   The multiple cardinality field label.
   * @param int $item
   *   The item number. 1-based.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The table row.
   */
  protected function getMultipleCardinalityFieldItem(string $multi_value_field, int $item): NodeElement {
    return $this->findTableRow($this->getMultipleCardinalityFieldTable($multi_value_field), $item - 1);
  }

}
