<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Mink\Element\NodeElement;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;

/**
 * Provides steps definitions to interact with paragraphs.
 */
class ParagraphsContext extends RawDrupalContext {

  use UtilityTrait;

  /**
   * Fills a field of a specific paragraph.
   *
   * Only fields that directly belong to the paragraph are targeted.
   *
   * @param string $field
   *   The field label.
   * @param string $value
   *   The field value.
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param string $position
   *   The ordinal position of the paragraph amongst its type.
   *
   * @When I fill in :field with :value in the :position :paragraph_type( paragraph)
   */
  public function fillParagraphField(string $field, string $value, string $paragraph_type, string $position): void {
    $paragraph_type = $this->unescapeStepArgument($paragraph_type);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $paragraph = $this->findParagraph($paragraph_type, $position);
    $collection = $this->getParagraphFormFieldCollection($paragraph);

    $collection->fillField($this->unescapeStepArgument($field), $this->unescapeStepArgument($value));
  }

  /**
   * Press a button of a specific paragraph.
   *
   * Only buttons that directly belong to the paragraph are targeted.
   *
   * @param string $button
   *   The button selector.
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param string $position
   *   The ordinal position of the paragraph amongst its type.
   *
   * @When I press :button in the :position :paragraph_type( paragraph)
   */
  public function pressParagraphButton(string $button, string $paragraph_type, string $position): void {
    $paragraph_type = $this->unescapeStepArgument($paragraph_type);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $paragraph = $this->findParagraph($paragraph_type, $position);
    $collection = $this->getParagraphFormFieldCollection($paragraph);

    $collection->pressButton($this->unescapeStepArgument($button));
  }

  /**
   * Press a button in a specific paragraph actions.
   *
   * @param string $button
   *   The button selector.
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param string $position
   *   The ordinal position of the paragraph amongst its type.
   *
   * @throws \Exception
   *   Thrown when no actions are found for the given paragraph.
   *
   * @When I press :button in the :position :paragraph_type (paragraph )actions
   */
  public function pressParagraphActionsButton(string $button, string $paragraph_type, string $position): void {
    $paragraph_type = $this->unescapeStepArgument($paragraph_type);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $paragraph = $this->findParagraph($paragraph_type, $position);
    $actions = $paragraph->find('css', 'div.paragraph-top div.paragraphs-actions');

    if (!$actions) {
      throw new \Exception(sprintf('Could not find actions for "%s" in position "%s".', $paragraph_type, $position));
    }

    $actions->pressButton($this->unescapeStepArgument($button));
  }

  /**
   * Selects an option in a select field of a specific paragraph.
   *
   * Only selects that directly belong to the paragraph are targeted.
   *
   * @param string $select
   *   The select field.
   * @param string $option
   *   The option value.
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param string $position
   *   The ordinal position of the paragraph amongst its type.
   *
   * @When I select :option from :select in the :position :paragraph_type( paragraph)
   */
  public function selectOptionInParagraph(string $select, string $option, string $paragraph_type, string $position): void {
    $paragraph_type = $this->unescapeStepArgument($paragraph_type);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $paragraph = $this->findParagraph($paragraph_type, $position);
    $collection = $this->getParagraphFormFieldCollection($paragraph);

    $collection->selectFieldOption($this->unescapeStepArgument($select), $this->unescapeStepArgument($option));
  }

  /**
   * Asserts that a specific option is selected in a paragraph select field.
   *
   * Only selects that directly belong to the paragraph are targeted.
   *
   * @param string $select
   *   The select field.
   * @param string $option
   *   The option value.
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param string $position
   *   The ordinal position of the paragraph amongst its type.
   *
   * @throws \Exception
   *   Thrown when the select cannot be found, when no options or the wrong one
   *   selected is.
   *
   * @Then the option :option should be selected in the :select select of the :position :paragraph_type( paragraph)
   */
  public function assertSelectedOptionInParagraph(string $select, string $option, string $paragraph_type, string $position): void {
    $paragraph_type = $this->unescapeStepArgument($paragraph_type);
    $position = $this->convertOrdinalToNumber($position) - 1;

    $paragraph = $this->findParagraph($paragraph_type, $position);
    $collection = $this->getParagraphFormFieldCollection($paragraph);

    $element = $collection->find('named', ['select', $this->unescapeStepArgument($select)]);

    if (empty($element)) {
      throw new \Exception(sprintf('Could not find select "%s".', $select));
    }

    $option_element = $element->find('xpath', '//option[@selected="selected"]');
    if (!$option_element) {
      throw new \Exception(sprintf('No option is selected for the "%s" select.', $select));
    }

    if ($option_element->getText() !== $option) {
      throw new \Exception(sprintf('The option "%s" was not selected, "%s" was selected.', $option, $option_element->getHtml()));
    }
  }

  /**
   * Finds and returns the paragraph at a given position.
   *
   * @param string $paragraph_type
   *   The label of the paragraph type.
   * @param int $position
   *   The position of the paragraph amongst its type, 0-index based.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   A node element whose XPath targets only the direct fields of the
   *   paragraph.
   *
   * @throws \Exception
   *   Thrown when the paragraph is not found.
   */
  protected function findParagraph(string $paragraph_type, int $position): NodeElement {
    // Find all paragraphs of the specified type, using the displayed label.
    $xpath = '//span' . $this->xpathHasClassSelector('paragraph-type-label') . '[text()="' . $paragraph_type . '"]'
      // Find the closest "top" wrapper.
      . '/ancestor-or-self::div' . $this->xpathHasClassSelector('paragraph-top')
      // The parent is the paragraph wrapper.
      . '/..';

    $paragraphs_of_type = $this->getSession()->getPage()->findAll('xpath', $xpath);

    if (!isset($paragraphs_of_type[$position])) {
      throw new \Exception(sprintf('Cannot find "%s" in position "%s".', $paragraph_type, $position));
    }

    return $paragraphs_of_type[$position];
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
    $collection = new NodeElement($direct_fields . ' | ' . $variant_field, $this->getSession());

    return $collection;
  }

}
