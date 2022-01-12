<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_options_event_test\EventSubscriber;

use Drupal\oe_paragraphs\Event\FlagOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the flag options event.
 */
class FlagOptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      FlagOptionsEvent::class => ['getFlagOptions', -1],
    ];
  }

  /**
   * Gets the flag options.
   *
   * @param \Drupal\oe_paragraphs\Event\FlagOptionsEvent $event
   *   The event object.
   */
  public function getFlagOptions(FlagOptionsEvent $event): void {
    $event->setFlagOptions([
      'flag-test-1' => 'Flag 1',
      'flag-test-2' => 'Flag 2',
      'flag-test-3' => 'Flag 3',
    ]);
  }

}
