<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists_test\EventSubscriber;

use Drupal\oe_paragraphs_illustrations_lists\Event\ColumnOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the column options event.
 */
class ColumnOptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ColumnOptionsEvent::class => ['getColumnOptions', -1],
    ];
  }

  /**
   * Gets the column options.
   *
   * @param \Drupal\oe_paragraphs_illustrations_lists\Event\ColumnOptionsEvent $event
   *   The event object.
   */
  public function getColumnOptions(ColumnOptionsEvent $event): void {
    $event->setColumnOptions([
      'column-test-1' => 'Column 1',
      'column-test-2' => 'Column 2',
      'column-test-3' => 'Column 3',
    ]);
  }

}
