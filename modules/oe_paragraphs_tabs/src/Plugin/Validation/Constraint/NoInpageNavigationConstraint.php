<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that a Content row in a Tab does not use in-page navigation.
 *
 * @Constraint(
 *   id = "OeParagraphsTabNoInpageNavigation",
 *   label = @Translation("No in-page navigation Content row inside a Tab", context = "Validation")
 * )
 */
class NoInpageNavigationConstraint extends Constraint {

  /**
   * The violation message.
   *
   * @var string
   */
  public string $message = 'A Content row inside a Tab cannot use the in-page navigation variant.';

}
