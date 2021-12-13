<?php

namespace Drupal\oe_paragraphs_icon_options_event_test;

use Drupal\oe_paragraphs\Event\IconOptionsEvent;
use Drupal\cas\Service\CasHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribe to Icon options event for testing.
 */
class IconOptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[IconOptionsEvent::class][] = ['getIconOptions', 0];
    return $events;
  }

  /**
   * Change the username of the user being registered.
   *
   * @param \Drupal\custom_events\Event\IconOptionsEvent $event
   *   Allowed format event object.
   */
  public function getIconOptions(IconOptionsEvent $event) {
    $event->setIconOptions([
      'item-test-1' => 'Item test 1',
      'item-test-2' => 'Item test 1',
      'item-test-3' => 'Item test 1',
      'item-test-4' => 'Item test 1',
      'item-test-5' => 'Item test 1',
    ]);
  }

}
