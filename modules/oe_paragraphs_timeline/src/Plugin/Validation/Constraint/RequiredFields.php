<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_timeline\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint plugin for the Timeline widget.
 *
 * Label and title fields should be required if any of the widget's fields are
 * filled in.
 *
 * @Constraint(
 *   id = "oe_paragraphs_timeline_required_fields",
 *   label = @Translation("Label and title fields of Timeline widget become required if any of the fields are filled in", context = "Validation"),
 *   type = "string"
 * )
 */
class RequiredFields extends Constraint {

  /**
   * The error message shown when body is filled in.
   *
   * @var string
   */
  public $bodyNotEmpty = 'The %label and %title fields are required when the %body field is specified.';

  /**
   * The error message shown when title is filled in and label is empty.
   *
   * @var string
   */
  public $labelRequired = 'The %label field is required when the %title field is specified.';

  /**
   * The error message shown when label is filled in and title is empty.
   *
   * @var string
   */
  public $titleRequired = 'The %title field is required when the %label field is specified.';

}
