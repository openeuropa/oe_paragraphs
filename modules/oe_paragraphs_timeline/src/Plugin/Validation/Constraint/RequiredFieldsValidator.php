<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_timeline\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates required fields for Timeline paragraph.
 */
class RequiredFieldsValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  public function validate($value, Constraint $constraint) {
    foreach ($value as $delta => $item) {
      $is_label_empty = empty($item['label'] ? $item['label'] : '');
      $is_title_empty = empty($item['title'] ? $item['title'] : '');
      $is_body_empty = empty($item['body'] ? $item['body'] : '');
      if ($is_title_empty && $is_label_empty && $is_body_empty) {
        continue;
      }
      if ($is_label_empty && $is_title_empty && !$is_body_empty) {
        $this->context->buildViolation($constraint->bodyNotEmpty)
          ->atPath((string) $delta)
          ->setParameter('%label', '')
          ->setParameter('%title', '')
          ->addViolation();
      }
      if ($is_label_empty && !$is_title_empty) {
        $this->context->buildViolation($constraint->labelRequired)
          ->atPath((string) $delta)
          ->setParameter('%label', '')
          ->addViolation();
      }
      if (!$is_label_empty && $is_title_empty) {
        $this->context->buildViolation($constraint->titleRequired)
          ->atPath((string) $delta)
          ->setParameter('%title', '')
          ->addViolation();
      }
    }
  }

}
