<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\oe_paragraphs\Event\IconOptionsEvent;

/**
 * Provides options for the icon field.
 */
class IconOptionsSubscriber implements EventSubscriberInterface {

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
   * @param \Drupal\custom_events\Event\IconOptionsEvent $event
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

}
