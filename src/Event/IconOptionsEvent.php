<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered when an icon options need to be provided.
 */
class IconOptionsEvent extends Event {

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $iconOptions = [];

  /**
   * Sets the allowed values list.
   *
   * @param array $icon_options
   *   Array containing the set of allowed values.
   */
  public function setIconOptions(array $icon_options = []): void {
    $this->iconOptions = $icon_options;
  }

  /**
   * Gets the allowed values list.
   *
   * @return array
   *   Array containing the set of allowed values.
   */
  public function getIconOptions(): array {
    return $this->iconOptions;
  }

}
