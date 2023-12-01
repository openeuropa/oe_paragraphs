<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides step definitions related to media testing.
 */
class MediaContext extends RawDrupalContext {

  /**
   * Enables modules that mock responses for remote videos.
   *
   * It differs from similar implementations in oe_media and media_avportal in
   * terms of the enabled modules.
   *
   * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
   *   The scope.
   *
   * @beforeScenario @remote-video-services
   */
  public function enableRemoteVideoServicesMock(BeforeScenarioScope $scope): void {
    \Drupal::service('module_installer')->install([
      'media_avportal_mock',
      'oe_media_oembed_mock',
    ]);
  }

  /**
   * Disables modules that mock responses for remote videos.
   *
   * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
   *   The scope.
   *
   * @afterScenario @remote-video-services
   */
  public function disableRemoteVideoServicesMock(AfterScenarioScope $scope): void {
    \Drupal::service('module_installer')->uninstall([
      'media_avportal_mock',
      'oe_media_oembed_mock',
    ]);
  }

}
