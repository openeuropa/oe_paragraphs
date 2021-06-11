<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Traits;

use Exception;
/**
 * Contains utility methods.
 */
trait UtilityTrait {

  /**
   * Unescapes step arguments.
   *
   * @param string $argument
   *   The argument value.
   *
   * @return string
   *   The unescaped value.
   */
  protected function unescapeStepArgument(string $argument): string {
    return str_replace('\\"', '"', $argument);
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
  protected function explodeCommaSeparatedStepArgument(string $argument): array {
    $argument = explode(',', $argument);
    $argument = array_map('trim', $argument);
    $argument = array_filter($argument);

    return $argument;
  }

  /**
   * Creates an XPath selector to match by class.
   *
   * @param string $class
   *   The class.
   * @param bool $wrap
   *   When true, wraps the expression between square brackets to be used
   *   directly as selector. Defaults to true.
   *
   * @return string
   *   The xpath selector.
   */
  protected function xpathHasClassSelector(string $class, bool $wrap = TRUE): string {
    $exp = '@class and contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")';
    return $wrap ? "[{$exp}]" : $exp;
  }

  /**
   * Converts an ordinal number to its integer value.
   *
   * E.g.: converts 1st to 1, 7th to 7.
   *
   * @param string $ordinal
   *   The ordinal string.
   *
   * @return int
   *   The integer value.
   *
   * @throws \Exception
   *   Thrown when an integer portion cannot be extracted.
   */
  protected function convertOrdinalToNumber(string $ordinal): int {
    preg_match('/^(\d+)(st|nd|rd|th)$/i', $ordinal, $matches);

    if (!isset($matches[1])) {
      throw new Exception("Could not extract a number from '$ordinal'.");
    }

    return (int) $matches[1];
  }

}
