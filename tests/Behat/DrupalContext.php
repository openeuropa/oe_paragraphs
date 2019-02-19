<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines generic step definitions.
 */
class DrupalContext extends RawDrupalContext {

  /**
   * Assert that certain fields are present on the page.
   *
   * @param string $fields
   *   Fields.
   *
   * @throws \Exception
   *   Thrown when an expected field is not present.
   *
   * @Then (the following )field(s) should be present :fields
   */
  public function assertFieldsPresent($fields): void {
    $fields = $this->explodeCommaSeparatedStepArgument($fields);
    $page = $this->getSession()->getPage();
    $not_found = [];
    foreach ($fields as $field) {
      $is_found = $page->findField($field);
      if (!$is_found) {
        $not_found[] = $field;
      }
    }
    if ($not_found) {
      throw new \Exception("Field(s) expected, but not found: " . implode(', ', $not_found));
    }
  }

  /**
   * Assert that certain fields are not present on the page.
   *
   * @param string $fields
   *   Fields.
   *
   * @throws \Exception
   *   Thrown when a column name is incorrect.
   *
   * @Then (the following )field(s) should not be present :fields
   */
  public function assertFieldsNotPresent($fields): void {
    $fields = $this->explodeCommaSeparatedStepArgument($fields);
    $page = $this->getSession()->getPage();
    foreach ($fields as $field) {
      $is_found = $page->findField($field);
      if ($is_found) {
        throw new \Exception("Field should not be found, but is present: " . $field);
      }
    }
  }

  /**
   * Explodes and sanitizes a comma separated step argument.
   *
   * @param string $argument
   *   The string argument.
   *
   * @return array
   *   The argument as array, with trimmed non-empty values.
   */
  protected function explodeCommaSeparatedStepArgument($argument): array {
    $argument = explode(',', $argument);
    $argument = array_map('trim', $argument);
    $argument = array_filter($argument);

    return $argument;
  }

}
