<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_description_list\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates required fields for Description List paragraph.
 */
class DescriptionListRequiredFieldsValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public function validate($value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\FieldItemListInterface $value */
    if ($value->isEmpty()) {
      return;
    }

    $parameters = [
      '%term' => $value->getItemDefinition()->getPropertyDefinition('term')->getLabel(),
      '%description' => $value->getItemDefinition()->getPropertyDefinition('description')->getLabel(),
    ];

    foreach ($value as $delta => $item) {
      /** @var \Drupal\description_list_field\Plugin\Field\FieldType\DescriptionListFieldItem $item */
      $value = $item->getValue();
      $is_term_empty = ($value['term'] === '' || $value['term'] === NULL);
      $is_description_empty = ($value['description'] === '' || $value['description'] === NULL);

      if ($is_term_empty && $is_description_empty) {
        continue;
      }

      /** @var \Drupal\oe_paragraphs_description_list\Plugin\Validation\Constraint\RequiredFields $constraint */
      if ($is_term_empty && !$is_description_empty) {
        $this->context->buildViolation($constraint->termRequired)
          ->atPath($delta . '.term')
          ->setParameters($parameters)
          ->addViolation();
        return;
      }

      if (!$is_term_empty && $is_description_empty) {
        $this->context->buildViolation($constraint->descriptionRequired)
          ->atPath($delta . '.description')
          ->setParameters($parameters)
          ->addViolation();
      }
    }
  }

}
