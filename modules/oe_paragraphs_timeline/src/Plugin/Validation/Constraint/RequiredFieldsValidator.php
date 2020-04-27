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
    /** @var \Drupal\Core\Field\FieldItemListInterface $value */
    if ($value->isEmpty()) {
      return;
    }

    // @todo To investigate if this is correct.
    // see \Drupal\Core\Field\WidgetBaseInterface::flagErrors()
    $parameters = [
      '%label' => $value->getItemDefinition()->getPropertyDefinition('label')->getLabel(),
      '%title' => $value->getItemDefinition()->getPropertyDefinition('title')->getLabel(),
      '%body' => $value->getItemDefinition()->getPropertyDefinition('body')->getLabel(),
    ];

    foreach ($value as $delta => $item) {
      /** @var \Drupal\oe_content_timeline_field\Plugin\Field\FieldType\TimelineFieldItem $item */
      $value = $item->getValue();
      $is_label_empty = ($value['label'] === '' || $value['label'] === NULL);
      $is_title_empty = ($value['title'] === '' || $value['title'] === NULL);
      $is_body_empty = ($value['body'] === '' || $value['body'] === NULL);

      if ($is_title_empty && $is_label_empty && $is_body_empty) {
        continue;
      }

      /** @var \Drupal\oe_paragraphs_timeline\Plugin\Validation\Constraint\RequiredFields $constraint */
      if (!$is_body_empty && $is_label_empty && $is_title_empty) {
        $this->context->buildViolation($constraint->bodyNotEmpty)
          ->atPath((string) $delta)
          ->setParameters($parameters)
          ->addViolation();
        return;
      }

      if ($is_label_empty && !$is_title_empty) {
        $this->context->buildViolation($constraint->labelRequired)
          ->atPath($delta . '.label')
          ->setParameters($parameters)
          ->addViolation();
        return;
      }

      if (!$is_label_empty && $is_title_empty) {
        $this->context->buildViolation($constraint->titleRequired)
          ->atPath($delta . '.title')
          ->setParameters($parameters)
          ->addViolation();
      }
    }
  }

}
