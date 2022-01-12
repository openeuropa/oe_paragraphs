<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered when a column's options need to be provided.
 */
class ColumnOptionsEvent extends Event {

  /**
   * The array containing the allowed values.
   *
   * @var array
   */
  protected $columnOptions = [];

  /**
   * Sets the column options list.
   *
   * @param array $column_options
   *   Array containing the set of allowed values for column options.
   */
  public function setColumnOptions(array $column_options = []): void {
    $this->columnOptions = $column_options;
  }

  /**
   * Gets the icon option list values.
   *
   * @return array
   *   Array containing the set of allowed values for column options.
   */
  public function getColumnOptions(): array {
    return $this->columnOptions;
  }

}
