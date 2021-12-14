<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered when an icon's options need to be provided.
 */
class IconOptionsEvent extends Event {

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $iconOptions = [];

  /**
   * Sets the icon options list.
   *
   * @param array $icon_options
   *   Array containing the set of allowed values for icon options.
   */
  public function setIconOptions(array $icon_options = []): void {
    $this->iconOptions = $icon_options;
  }

  /**
   * Gets the icon option list values.
   *
   * @return array
   *   Array containing the set of allowed values for icon options.
   */
  public function getIconOptions(): array {
    return $this->iconOptions;
  }

}
