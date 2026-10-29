<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Drupal\Core\Entity\FieldableEntityInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the OeParagraphsTabNoInpageNavigation constraint.
 */
class NoInpageNavigationConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $items, Constraint $constraint): void {
    foreach ($items as $item) {
      $paragraph = $item->entity;
      if (!$paragraph instanceof FieldableEntityInterface || $paragraph->bundle() !== 'oe_content_row') {
        continue;
      }
      if ($paragraph->get('oe_paragraphs_variant')->value === 'inpage_navigation') {
        $this->context->addViolation($constraint->message);
        return;
      }
    }
  }

}
