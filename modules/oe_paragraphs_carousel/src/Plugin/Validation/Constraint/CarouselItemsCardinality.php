<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_carousel\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint plugin for the 'Items' field of the Carousel paragraph.
 *
 * @Constraint(
 *   id = "CarouselItemsCardinality",
 *   label = @Translation("The 'Carousel' paragraph should reference at least 2 'Carousel item' paragraphs.", context = "Validation"),
 *   type = "string"
 * )
 */
class CarouselItemsCardinality extends Constraint {

  /**
   * The error message.
   *
   * @var string
   */
  public $message = 'The Carousel paragraph should contain at least 2 items.';

}
