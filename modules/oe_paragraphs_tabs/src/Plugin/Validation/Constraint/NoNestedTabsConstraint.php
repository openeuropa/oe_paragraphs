<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that a Tab does not contain Tabs, at any depth.
 *
 * @Constraint(
 *   id = "OeParagraphsNoNestedTabs",
 *   label = @Translation("No Tabs inside a Tab", context = "Validation")
 * )
 */
class NoNestedTabsConstraint extends Constraint {

  /**
   * The violation message.
   *
   * @var string
   */
  public string $message = 'A Tab cannot contain Tabs.';

}
