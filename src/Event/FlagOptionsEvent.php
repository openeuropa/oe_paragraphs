<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event triggered when a flag's options need to be provided.
 */
class FlagOptionsEvent extends Event {

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $flagOptions = [];

  /**
   * Sets the flag options list.
   *
   * @param array $flag_options
   *   Array containing the set of allowed values for flag options.
   */
  public function setFlagOptions(array $flag_options = []): void {
    $this->flagOptions = $flag_options;
  }

  /**
   * Gets the icon option list values.
   *
   * @return array
   *   Array containing the set of allowed values for flag options.
   */
  public function getFlagOptions(): array {
    return $this->flagOptions;
  }

}
