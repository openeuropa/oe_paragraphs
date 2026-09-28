<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

/**
 * Checks that a Tab does not contain Tabs, at any depth.
 */
#[Constraint(
  id: 'OeParagraphsNoNestedTabs',
  label: new TranslatableMarkup('No Tabs inside a Tab', [], ['context' => 'Validation']),
)]
class NoNestedTabsConstraint extends SymfonyConstraint {

  /**
   * The violation message.
   *
   * @var string
   */
  public string $message = 'A Tab cannot contain Tabs.';

}
