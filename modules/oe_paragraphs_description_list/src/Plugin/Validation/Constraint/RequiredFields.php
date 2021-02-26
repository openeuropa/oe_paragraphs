<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_description_list\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint plugin for the Description list field widget.
 *
 * Term and description fields should be required if any of the widget's fields
 * are filled in.
 *
 * @Constraint(
 *   id = "oe_paragraphs_description_list_required_fields",
 *   label = @Translation("Term and description fields of Description list widget become required if any of the fields are filled in", context = "Validation"),
 *   type = "string"
 * )
 */
class RequiredFields extends Constraint {

  /**
   * The error message shown when term is filled in.
   *
   * @var string
   */
  public $descriptionRequired = 'The %description field is required when the %term field is specified.';

  /**
   * The error message shown when description is filled in.
   *
   * @var string
   */
  public $termRequired = 'The %term field is required when the %description field is specified.';

}
