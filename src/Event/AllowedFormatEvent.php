<?php

/**
 * @file
 * Allowed format event.
 */

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Event that is fired when an allowed values list is checked.
 */
class AllowedFormatEvent extends Event {

  const EVENT_CHECK = 'oe_paragraphs.allowed_formats_check';

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $allowed_values = [];

  /**
   * Sets the allowed values list.
   *
   * @param array $allowed_values
   *   Array containing the set of allowed values.
   */
  public function setAllowedValues(array $allowed_values = []): void {
    $this->allowed_values = $allowed_values;
  }

  /**
   * Gets the allowed values list.
   *
   * @return array
   *   Array containing the set of allowed values.
   */
  public function getAllowedValues(): array {
    return $this->allowed_values;
  }
}
