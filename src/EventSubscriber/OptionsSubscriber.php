<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\oe_paragraphs\Event\IconOptionsEvent;
use Drupal\oe_paragraphs\Event\FlagOptionsEvent;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides options for the icon and flag fields.
 */
class OptionsSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      IconOptionsEvent::class => 'getIconOptions',
      FlagOptionsEvent::class => 'getFlagOptions',
    ];
  }

  /**
   * Gets the icon options.
   *
   * @param \Drupal\oe_paragraphs\Event\IconOptionsEvent $event
   *   Allowed format event object.
   */
  public function getIconOptions(IconOptionsEvent $event): void {
    $event->setIconOptions([
      'arrow-down' => 'Arrow down',
      'external' => 'External',
      'arrow-up' => 'Arrow up',
      'audio' => 'Audio',
      'book' => 'Book',
      'breadcrumb' => 'Breadcrumb',
      'brochure' => 'Brochure',
      'budget' => 'Budget',
      'calendar' => 'Calendar',
      'camera' => 'Camera',
      'check' => 'Check',
      'close' => 'Close',
      'close-dark' => 'Close dark',
      'copy' => 'Copy',
      'data' => 'Data',
      'digital' => 'Digital',
      'down' => 'Down',
      'download' => 'Download',
      'edit' => 'Edit',
      'energy' => 'Energy',
      'error' => ' Error',
      'euro' => 'Euro',
      'facebook' => 'Facebook',
      'faq' => 'Faq',
      'feedback' => 'Feedback',
      'file' => 'File',
      'generic-lang' => 'Generic language',
      'global' => 'Global',
      'googleplus' => 'Google Plus (deprecated)',
      'growth' => 'Growth',
      'image' => 'Image',
      'in' => 'In',
      'info' => 'Info',
      'infographic' => 'Infographic',
      'language' => 'Language',
      'left' => 'Left',
      'linkedin' => 'LinkedIn',
      'livestreaming' => 'Live streaming',
      'location' => 'Location',
      'multiple-files' => 'Multiple files',
      'organigram' => 'Organigram',
      'package' => 'Package',
      'presentation' => 'Presentation',
      'regulation' => 'Regulation',
      'right' => 'Right',
      'rss' => 'RSS',
      'search' => 'Search',
      'share' => 'Share',
      'slides' => 'Slides (deprecated)',
      'spinner' => 'Spinner',
      'spreadsheet' => 'Spreadsheet',
      'success' => 'Success',
      'tag-close' => 'Tag close',
      'twitter' => 'Twitter',
      'up' => 'Up',
      'video' => 'Video',
      'warning' => 'Warning',
    ]);
  }

  /**
   * Gets the flag options.
   *
   * @param \Drupal\oe_paragraphs\Event\FlagOptionsEvent $event
   *   Allowed format event object.
   */
  public function getFlagOptions(FlagOptionsEvent $event): void {
    $event->setFlagOptions([
      'austria' => $this->t('Austria'),
      'belgium' => $this->t('Belgium'),
      'bulgaria' => $this->t('Bulgaria'),
      'croatia' => $this->t('Croatia'),
      'cyprus' => $this->t('Cyprus'),
      'czech-republic' => $this->t('Czech republic'),
      'denmark' => $this->t('Denmark'),
      'estonia' => $this->t('Estonia'),
      'EU' => $this->t('EU'),
      'finland' => $this->t('Finland'),
      'france' => $this->t('France'),
      'germany' => $this->t('Germany'),
      'greece' => $this->t('Greece'),
      'hungary' => $this->t('Hungary'),
      'ireland' => $this->t('Ireland'),
      'italy' => $this->t('Italy'),
      'latvia' => $this->t('Latvia'),
      'lithuania' => $this->t('Lithuania'),
      'luxembourg' => $this->t('Luxembourg'),
      'malta' => $this->t('Malta'),
      'netherlands' => $this->t('Netherlands'),
      'poland' => $this->t('Poland'),
      'portugal' => $this->t('Portugal'),
      'romania' => $this->t('Romania'),
      'slovakia' => $this->t('Slovakia'),
      'slovenia' => $this->t('Slovenia'),
      'spain' => $this->t('Spain'),
      'sweden' => $this->t('Sweden'),
    ]);
  }

}
