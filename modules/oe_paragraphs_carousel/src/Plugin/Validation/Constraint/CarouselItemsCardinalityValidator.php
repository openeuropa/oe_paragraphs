<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_carousel\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates that the Carousel paragraph contains at least 2 items.
 */
class CarouselItemsCardinalityValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (count($value->getValue()) < 2) {
      $this->context->buildViolation($constraint->message)->addViolation();
    }
  }

}
