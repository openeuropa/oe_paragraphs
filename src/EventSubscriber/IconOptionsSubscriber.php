<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\EventSubscriber;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\oe_paragraphs\Event\IconOptionsEvent;

/**
 * Provides options for the icon field.
 */
class IconOptionsSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      IconOptionsEvent::class => 'getIconOptions',
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

}
