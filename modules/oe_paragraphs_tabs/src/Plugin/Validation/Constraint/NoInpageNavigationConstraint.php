<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

/**
 * Checks that a Content row in a Tab does not use in-page navigation.
 */
#[Constraint(
  id: 'OeParagraphsTabNoInpageNavigation',
  label: new TranslatableMarkup('No in-page navigation Content row inside a Tab', [], ['context' => 'Validation']),
)]
class NoInpageNavigationConstraint extends SymfonyConstraint {

  /**
   * The violation message.
   *
   * @var string
   */
  public string $message = 'A Content row inside a Tab cannot use the in-page navigation variant.';

}
