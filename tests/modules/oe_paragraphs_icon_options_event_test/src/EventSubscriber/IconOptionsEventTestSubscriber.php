<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_icon_options_event_test\EventSubscriber;

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
    return [
      IconOptionsEvent::class => ['getIconOptions', -1],
    ];
  }

  /**
   * Gets the icon options.
   *
   * @param \Drupal\oe_paragraphs\Event\IconOptionsEvent $event
   *   The event object.
   */
  public function getIconOptions(IconOptionsEvent $event): void {
    $event->setIconOptions([
      'item-test-1' => 'Item test 1',
      'item-test-2' => 'Item test 2',
      'item-test-3' => 'Item test 3',
    ]);
  }

}
