<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_tabs\Plugin\Validation\Constraint;

use Drupal\Core\Entity\FieldableEntityInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the OeParagraphsNoNestedTabs constraint.
 */
class NoNestedTabsConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $items, Constraint $constraint): void {
    foreach ($items as $item) {
      if ($item->entity instanceof FieldableEntityInterface && $this->hasTabs($item->entity)) {
        $this->context->addViolation($constraint->message);
        return;
      }
    }
  }

  /**
   * Checks if a paragraph is, or contains, a Tabs paragraph.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $paragraph
   *   The paragraph.
   *
   * @return bool
   *   TRUE if Tabs are found.
   */
  protected function hasTabs(FieldableEntityInterface $paragraph): bool {
    if ($paragraph->bundle() === 'oe_tabs') {
      return TRUE;
    }
    foreach ($paragraph->getFields() as $field) {
      if ($field->getFieldDefinition()->getType() !== 'entity_reference_revisions') {
        continue;
      }
      foreach ($field as $child) {
        if ($child->entity instanceof FieldableEntityInterface && $this->hasTabs($child->entity)) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

}
