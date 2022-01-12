<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs_illustrations_lists\EventSubscriber;

use Drupal\oe_paragraphs_illustrations_lists\Event\ColumnOptionsEvent;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides options for the column field.
 */
class ColumnOptionsSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ColumnOptionsEvent::class => 'getColumnOptions',
    ];
  }

  /**
   * Gets the column options.
   *
   * @param \Drupal\oe_paragraphs_illustrations_lists\Event\ColumnOptionsEvent $event
   *   Allowed format event object.
   */
  public function getColumnOptions(ColumnOptionsEvent $event): void {
    $event->setColumnOptions([
      2 => $this->t('Two columns'),
      3 => $this->t('Three columns'),
      4 => $this->t('Four columns'),
    ]);
  }

}
