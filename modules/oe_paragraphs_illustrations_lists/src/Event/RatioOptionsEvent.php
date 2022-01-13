<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered when a ratio's options need to be provided.
 */
class RatioOptionsEvent extends Event {

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $ratioOptions = [];

  /**
   * Sets the ratio options list.
   *
   * @param array $ratio_options
   *   Array containing the set of allowed values for ratio options.
   */
  public function setRatioOptions(array $ratio_options = []): void {
    $this->ratioOptions = $ratio_options;
  }

  /**
   * Gets the ratio option list values.
   *
   * @return array
   *   Array containing the set of allowed values for ratio options.
   */
  public function getRatioOptions(): array {
    return $this->ratioOptions;
  }

}
