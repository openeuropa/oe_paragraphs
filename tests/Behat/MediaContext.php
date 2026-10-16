<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\file\FileInterface;

/**
 * Provides step definitions related to media testing.
 */
class MediaContext extends RawDrupalContext {

  /**
   * Keep track of medias so they can be cleaned up.
   *
   * @var \Drupal\Core\Entity\ContentEntityInterface[]
   */
  protected $media = [];

  /**
   * Keep track of files so they can be cleaned up.
   *
   * @var \Drupal\Core\Entity\ContentEntityInterface[]
   */
  protected $files = [];

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

  /**
   * Creates media documents with the specified file names.
   *
   * Usage example:
   *
   * Given the following documents:
   *   | name   | file   |
   *   | name 1 | file 1 |
   *   |   ...  |   ...  |
   *
   * @Given the following document(s):
   */
  public function createMediaDocuments(TableNode $table): void {
    // Retrieve the url table from the test scenario.
    $files = $table->getColumnsHash();
    foreach ($files as $properties) {
      $file = $this->createFileEntity($properties['file']);
      $media = \Drupal::entityTypeManager()
        ->getStorage('media')->create([
          'bundle' => 'document',
          'name' => $properties['name'],
          'oe_media_file' => [
            'target_id' => (int) $file->id(),
          ],
          'status' => 1,
        ]);
      $media->save();

      // Store for cleanup.
      $this->media[] = $media;
    }
  }

  /**
   * Creates media images with the specified field values.
   *
   * Usage example:
   *
   * Given the following images:
   *   | name   | file   | alt   | title   |
   *   | name 1 | file 1 | alt 1 | title 1 |
   *   | ...    | ...    | ...   | ...     |
   *
   * Properties "alt" and "title" are optional, "name" will be used if they
   * are not provided.
   *
   * @Given the following image(s):
   */
  public function createMediaImages(TableNode $table): void {
    // Retrieve the url table from the test scenario.
    $files = $table->getColumnsHash();
    foreach ($files as $properties) {
      $file = $this->createFileEntity($properties['file']);
      $media = \Drupal::entityTypeManager()
        ->getStorage('media')->create([
          'bundle' => 'image',
          'name' => $properties['name'],
          'oe_media_image' => [
            'target_id' => (int) $file->id(),
            'alt' => $properties['alt'] ?? $properties['name'],
            'title' => $properties['title'] ?? $properties['name'],
          ],
          'status' => 1,
        ]);
      $media->save();

      // Store for cleanup.
      $this->media[] = $media;
    }
  }

  /**
   * Creates media AVPortal video with the specified URLs.
   *
   * Usage example:
   *
   * Given the following AV Portal videos:
   *   | url   |
   *   | url 1 |
   *   |  ...  |
   *
   * @Given the following AV Portal video(s):
   */
  public function createMediaAvPortalVideo(TableNode $table): void {
    /** @var \Drupal\media_avportal\Plugin\media\Source\MediaAvPortalSourceInterface $media_source */
    $media_source = \Drupal::entityTypeManager()
      ->getStorage('media_type')
      ->load('av_portal_video')
      ->getSource();

    // Retrieve the url table from the test scenario.
    foreach ($table->getColumnsHash() as $hash) {
      $media = \Drupal::entityTypeManager()
        ->getStorage('media')->create([
          'bundle' => 'av_portal_video',
          'oe_media_avportal_video' => $media_source->transformUrlToReference($hash['url']),
          'status' => 1,
        ]);
      $media->save();

      // Store for cleanup.
      $this->media[] = $media;
    }
  }

  /**
   * Creates media AVPortal photos with the specified URLs.
   *
   * Usage example:
   *
   * Given the following AV Portal photos:
   *   | url   |
   *   | url 1 |
   *   |  ...  |
   *
   * @Given the following AV Portal photo(s):
   */
  public function createMediaAvPortalPhotos(TableNode $table): void {
    /** @var \Drupal\media_avportal\Plugin\media\Source\MediaAvPortalSourceInterface $media_source */
    $media_source = \Drupal::entityTypeManager()
      ->getStorage('media_type')
      ->load('av_portal_photo')
      ->getSource();

    // Retrieve the url table from the test scenario.
    foreach ($table->getColumnsHash() as $hash) {
      $media = \Drupal::entityTypeManager()
        ->getStorage('media')->create([
          'bundle' => 'av_portal_photo',
          'oe_media_avportal_photo' => $media_source->transformUrlToReference($hash['url']),
          'status' => 1,
        ]);
      $media->save();

      // Store for cleanup.
      $this->media[] = $media;
    }
  }

  /**
   * Creates media Remote video with the specified URLs.
   *
   * Usage example:
   *
   * Given the following remote videos:
   *   | url   |
   *   | url 1 |
   *   |  ...  |
   *
   * @Given the following remote video(s):
   */
  public function createMediaRemoteVideo(TableNode $table): void {
    foreach ($table->getColumnsHash() as $hash) {
      $media = \Drupal::entityTypeManager()
        ->getStorage('media')->create([
          'bundle' => 'remote_video',
          'oe_media_oembed_video' => $hash['url'],
          'status' => 1,
        ]);
      $media->save();

      // Store for cleanup.
      $this->media[] = $media;
    }
  }

  /**
   * Create a file entity from given file and return its object.
   *
   * @param string $file_name
   *   File name, relative to Mink 'files_path' location.
   *
   * @return \Drupal\file\FileInterface
   *   File entity object.
   */
  protected function createFileEntity(string $file_name): FileInterface {
    $file = \Drupal::service('file.repository')->writeData(
      file_get_contents($this->getMinkParameter('files_path') . $file_name),
      'public://' . basename($file_name)
    );
    $file->setPermanent();
    $file->save();

    // Store for cleanup.
    $this->files[] = $file;

    return $file;
  }

  /**
   * Remove any created media.
   *
   * @AfterScenario
   */
  public function cleanMedias(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('media');
    foreach ($this->media as $media) {
      // Tests might manually delete entities created via this step.
      // Here we check if the entity still exists before deleting it.
      if ($storage->load($media->id()) !== NULL) {
        $media->delete();
      }
    }

    // Reset entity array.
    $this->media = [];
  }

  /**
   * Remove any created files.
   *
   * @AfterScenario
   */
  public function cleanFiles(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('file');
    foreach ($this->files as $file) {
      // Tests might manually delete entities created via this step.
      // Here we check if the entity still exists before deleting it.
      if ($storage->load($file->id()) !== NULL) {
        $file->delete();
      }
    }

    // Reset entity array.
    $this->files = [];
  }

}
