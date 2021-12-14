<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_icon_options_event_test;

use Drupal\oe_paragraphs\Event\IconOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the icon options event.
 */
class IconOptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[IconOptionsEvent::class][] = ['getIconOptions', 0];
    return $events;
  }

  /**
   * Change the username of the user being registered.
   *
   * @param \Drupal\custom_events\Event\IconOptionsEvent $event
   *   Allowed format event object.
   */
  public function getIconOptions(IconOptionsEvent $event): void {
    $event->setIconOptions([
      'item-test-1' => 'Item test 1',
      'item-test-2' => 'Item test 1',
      'item-test-3' => 'Item test 1',
      'item-test-4' => 'Item test 1',
      'item-test-5' => 'Item test 1',
    ]);
  }

}
