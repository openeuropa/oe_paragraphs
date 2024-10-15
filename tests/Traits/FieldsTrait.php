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

}
