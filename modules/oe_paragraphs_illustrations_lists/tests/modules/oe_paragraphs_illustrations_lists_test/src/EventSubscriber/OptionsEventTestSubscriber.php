<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs_illustrations_lists_test\EventSubscriber;

use Drupal\oe_paragraphs_illustrations_lists\Event\ColumnOptionsEvent;
use Drupal\oe_paragraphs_illustrations_lists\Event\RatioOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the column and ratio options event.
 */
class OptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ColumnOptionsEvent::class => ['getColumnOptions', -1],
      RatioOptionsEvent::class => ['getRatioOptions', -1],
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

  /**
   * Gets the ratio options.
   *
   * @param \Drupal\oe_paragraphs_illustrations_lists\Event\RatioOptionsEvent $event
   *   The event object.
   */
  public function getRatioOptions(RatioOptionsEvent $event): void {
    $event->setRatioOptions([
      'ratio-test-1' => 'Ratio 1',
      'ratio-test-2' => 'Ratio 2',
      'ratio-test-3' => 'Ratio 3',
    ]);
  }

}
