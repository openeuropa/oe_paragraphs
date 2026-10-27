<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

/**
 * Contains utility methods.
 */
trait UtilityTrait {

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

}
