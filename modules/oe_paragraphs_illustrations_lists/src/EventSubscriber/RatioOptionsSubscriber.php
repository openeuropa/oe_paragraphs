<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists\EventSubscriber;

use Drupal\oe_paragraphs_illustrations_lists\Event\RatioOptionsEvent;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides options for the ratio field.
 */
class RatioOptionsSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      RatioOptionsEvent::class => 'getRatioOptions',
    ];
  }

  /**
   * Gets the ratio options.
   *
   * @param \Drupal\oe_paragraphs_illustrations_lists\Event\RatioOptionsEvent $event
   *   Allowed format event object.
   */
  public function getRatioOptions(RatioOptionsEvent $event): void {
    $event->setRatioOptions([
      'landscape' => $this->t('Landscape'),
      'square' => $this->t('Square'),
    ]);
  }

}
