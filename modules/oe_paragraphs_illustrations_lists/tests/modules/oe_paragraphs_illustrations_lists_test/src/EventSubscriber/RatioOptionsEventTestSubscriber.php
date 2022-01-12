<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists_test\EventSubscriber;

use Drupal\oe_paragraphs_illustrations_lists\Event\RatioOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the ratio options event.
 */
class RatioOptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      RatioOptionsEvent::class => ['getRatioOptions', -1],
    ];
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
