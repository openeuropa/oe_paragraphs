<?php

declare(strict_types=1);

namespace Drupal\oe_paragraphs\EventSubscriber;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\oe_paragraphs\Event\FlagOptionsEvent;
use Drupal\oe_paragraphs\Event\IconOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
      'arrow-down' => $this->t('Arrow down'),
      'external' => $this->t('External'),
      'arrow-up' => $this->t('Arrow up'),
      'audio' => $this->t('Audio'),
      'book' => $this->t('Book'),
      'breadcrumb' => $this->t('Breadcrumb'),
      'brochure' => $this->t('Brochure'),
      'budget' => $this->t('Budget'),
      'calendar' => $this->t('Calendar'),
      'camera' => $this->t('Camera'),
      'check' => $this->t('Check'),
      'close' => $this->t('Close'),
      'close-dark' => $this->t('Close dark'),
      'copy' => $this->t('Copy'),
      'data' => $this->t('Data'),
      'digital' => $this->t('Digital'),
      'down' => $this->t('Down'),
      'download' => $this->t('Download'),
      'edit' => $this->t('Edit'),
      'energy' => $this->t('Energy'),
      'error' => $this->t('Error'),
      'euro' => $this->t('Euro'),
      'facebook' => $this->t('Facebook'),
      'faq' => $this->t('Faq'),
      'feedback' => $this->t('Feedback'),
      'file' => $this->t('File'),
      'generic-lang' => $this->t('Generic language'),
      'global' => $this->t('Global'),
      'googleplus' => $this->t('Google Plus (deprecated)'),
      'growth' => $this->t('Growth'),
      'image' => $this->t('Image'),
      'in' => $this->t('In'),
      'info' => $this->t('Info'),
      'infographic' => $this->t('Infographic'),
      'language' => $this->t('Language'),
      'left' => $this->t('Left'),
      'linkedin' => $this->t('LinkedIn'),
      'livestreaming' => $this->t('Live streaming'),
      'location' => $this->t('Location'),
      'multiple-files' => $this->t('Multiple files'),
      'organigram' => $this->t('Organigram'),
      'package' => $this->t('Package'),
      'presentation' => $this->t('Presentation'),
      'regulation' => $this->t('Regulation'),
      'right' => $this->t('Right'),
      'rss' => $this->t('RSS'),
      'search' => $this->t('Search'),
      'share' => $this->t('Share'),
      'slides' => $this->t('Slides (deprecated)'),
      'spinner' => $this->t('Spinner'),
      'spreadsheet' => $this->t('Spreadsheet'),
      'success' => $this->t('Success'),
      'tag-close' => $this->t('Tag close'),
      'twitter' => $this->t('Twitter'),
      'up' => $this->t('Up'),
      'video' => $this->t('Video'),
      'warning' => $this->t('Warning'),
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
