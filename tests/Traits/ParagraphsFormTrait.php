<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

use Behat\Mink\Element\NodeElement;

/**
 * Helper methods to interact with the paragraph forms.
 */
trait ParagraphsFormTrait {

  /**
   * Fills a field of a specific paragraph.
   *
   * Only fields that directly belong to the paragraph are targeted.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $field
   *   The field label.
   * @param string $value
   *   The field value.
   */
  protected function fillParagraphField(string $paragraph_type, int $position, string $field, string $value): void {
    $this->getParagraphFormFieldCollection($this->findParagraph($paragraph_type, $position))->fillField($field, $value);
  }

  /**
   * Presses a button of a specific paragraph.
   *
   * Only buttons that directly belong to the paragraph are targeted.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $button
   *   The button locator.
   */
  protected function pressParagraphButton(string $paragraph_type, int $position, string $button): void {
    $this->getParagraphFormFieldCollection($this->findParagraph($paragraph_type, $position))->pressButton($button);
  }

  /**
   * Presses a button in the actions of a specific paragraph.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $button
   *   The button locator.
   */
  protected function pressParagraphActionsButton(string $paragraph_type, int $position, string $button): void {
    $actions = $this->findParagraph($paragraph_type, $position)->find('css', 'div.paragraph-top div.paragraphs-actions');
    $this->assertNotNull($actions, sprintf('Could not find actions for "%s" in position "%s".', $paragraph_type, $position));
    $actions->pressButton($button);
  }

  /**
   * Selects an option in a select field of a specific paragraph.
   *
   * Only selects that directly belong to the paragraph are targeted.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $select
   *   The select locator.
   * @param string $option
   *   The option to select.
   */
  protected function selectParagraphOption(string $paragraph_type, int $position, string $select, string $option): void {
    $this->getParagraphFormFieldCollection($this->findParagraph($paragraph_type, $position))->selectFieldOption($select, $option);
  }

  /**
   * Asserts that a specific option is selected in a paragraph select field.
   *
   * Only selects that directly belong to the paragraph are targeted.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $select
   *   The select locator.
   * @param string $option
   *   The label of the option expected to be selected.
   */
  protected function assertParagraphSelectedOption(string $paragraph_type, int $position, string $select, string $option): void {
    $collection = $this->getParagraphFormFieldCollection($this->findParagraph($paragraph_type, $position));
    $element = $collection->find('named', ['select', $select]);
    $this->assertNotNull($element, sprintf('Could not find select "%s".', $select));

    $option_element = $element->find('xpath', '//option[@selected="selected"]');
    $this->assertNotNull($option_element, sprintf('No option is selected for the "%s" select.', $select));
    $this->assertEquals($option, $option_element->getText());
  }

  /**
   * Asserts the paragraph types that a paragraph reference field can add.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   * @param string $field
   *   The label of the paragraph reference field.
   * @param string[] $expected
   *   The labels of the paragraph types that can be referenced.
   */
  protected function assertParagraphReferenceableTypes(string $paragraph_type, int $position, string $field, array $expected): void {
    $element = $this->getParagraphReferenceField($this->findParagraph($paragraph_type, $position), $field);

    // The add buttons are rendered after the multi-value field table. Their
    // wrapper depends on the theme, so match any following sibling. The table
    // is excluded, so add buttons of nested paragraphs are not matched.
    $xpath = '/table/following-sibling::*' .
      '//input' . $this->xpathHasClassSelector('field-add-more-submit');
    $buttons = $element->findAll('xpath', $xpath);

    $actual = [];
    foreach ($buttons as $button) {
      // Remove the "Add " prefix from the button label.
      $actual[] = substr($button->getValue(), 4);
    }
    sort($actual);
    sort($expected);

    $this->assertEquals($expected, $actual);
  }

  /**
   * Finds and returns the paragraph at a given position.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The paragraph wrapper element.
   */
  protected function findParagraph(string $paragraph_type, int $position): NodeElement {
    // Find all paragraphs of the specified type, using the displayed label.
    $xpath = '//span' . $this->xpathHasClassSelector('paragraph-type-label') . '[text()="' . $paragraph_type . '"]'
      // Find the closest "top" wrapper.
      . '/ancestor::div' . $this->xpathHasClassSelector('paragraph-top')
      // The parent is the paragraph wrapper.
      . '/..';

    $paragraphs_of_type = $this->getSession()->getPage()->findAll('xpath', $xpath);
    $this->assertArrayHasKey($position - 1, $paragraphs_of_type, sprintf('Cannot find "%s" in position "%s".', $paragraph_type, $position));

    return $paragraphs_of_type[$position - 1];
  }

  /**
   * Returns a collection of fields that belong directly to a paragraph.
   *
   * @param \Behat\Mink\Element\NodeElement $paragraph
   *   The paragraph element.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   A node element whose XPath targets only the direct fields of the
   *   paragraph.
   */
  protected function getParagraphFormFieldCollection(NodeElement $paragraph): NodeElement {
    $direct_fields = $paragraph->getXpath()
      // Find the paragraph subform wrapper which contains the fields.
      . '/div' . $this->xpathHasClassSelector('paragraphs-subform')
      // Exclude all the sub-paragraph forms that might be there.
      . '/*[not(' . $this->xpathHasClassSelector('field--type-entity-reference-revisions', FALSE) . ')]';

    // Include the variant select which is outside the form display.
    $variant_field = $paragraph->getXpath()
      . '/div' . $this->xpathHasClassSelector('form-item') . '[.//label[text()="Variant"]]';

    // Create a node element which is actually pointing to a collection of
    // elements minus the sub-paragraphs.
    return new NodeElement($direct_fields . ' | ' . $variant_field, $this->getSession());
  }

  /**
   * Finds a paragraph reference field in a paragraph element.
   *
   * @param \Behat\Mink\Element\NodeElement $paragraph
   *   The paragraph element.
   * @param string $label
   *   The label of the paragraph reference field.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The paragraph reference field element.
   */
  protected function getParagraphReferenceField(NodeElement $paragraph, string $label): NodeElement {
    $subform = $paragraph->find('xpath', '/div' . $this->xpathHasClassSelector('paragraphs-subform'));

    // Find the table by its header.
    $xpath = '//table[./thead//h4[text()="' . $label . '"]]' .
      // Get the closest form item wrapper.
      '//ancestor::div' . $this->xpathHasClassSelector('form-item') .
      // Assure that this form item is rendered by a paragraph widget.
      '[./parent::div' . $this->xpathHasClassSelector('paragraphs-tabs-wrapper') .
      // Find the closest paragraph field. Due to ajax we cannot check for the
      // direct parent, but we already asserted that this is a paragraph widget,
      // so we stop at the first one found.
      '/ancestor::div' . $this->xpathHasClassSelector('field--type-entity-reference-revisions') . '[1]' .
      // Verify that this field belongs to the correct paragraph.
      '/parent::div[@id="' . $subform->getAttribute('id') . '"]]';

    $field = $paragraph->find('xpath', $xpath);
    $this->assertNotNull($field, sprintf('Could not find field %s in the specified paragraph.', $label));

    return $field;
  }

}
