<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_options_event_test\EventSubscriber;

use Drupal\oe_paragraphs\Event\FlagOptionsEvent;
use Drupal\oe_paragraphs\Event\IconOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to provide test values for the icon and flag options event.
 */
class OptionsEventTestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      IconOptionsEvent::class => ['getIconOptions', -1],
      FlagOptionsEvent::class => ['getFlagOptions', -1],
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
