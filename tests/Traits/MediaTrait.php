<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;

/**
 * Helper methods to create media entities and assert their rendering.
 */
trait MediaTrait {

  /**
   * Creates a media entity.
   *
   * @param string $bundle
   *   The media bundle.
   * @param array $values
   *   The media field values.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createMedia(string $bundle, array $values): MediaInterface {
    $media = \Drupal::entityTypeManager()->getStorage('media')->create([
      'bundle' => $bundle,
      'status' => 1,
    ] + $values);
    $media->save();

    return $media;
  }

  /**
   * Creates an image media.
   *
   * @param string $name
   *   The media name, used also as alt and title of the image.
   * @param string $file_name
   *   The name of the file in the fixtures folder.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createImageMedia(string $name, string $file_name = 'example_1.jpeg'): MediaInterface {
    return $this->createMedia('image', [
      'name' => $name,
      'oe_media_image' => [
        'target_id' => $this->createFixtureFile($file_name)->id(),
        'alt' => $name,
        'title' => $name,
      ],
    ]);
  }

  /**
   * Creates a local document media.
   *
   * @param string $name
   *   The media name.
   * @param string $file_name
   *   The name of the file in the fixtures folder.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createLocalDocumentMedia(string $name, string $file_name = 'example_1.pdf'): MediaInterface {
    return $this->createMedia('document', [
      'name' => $name,
      'oe_media_file_type' => 'local',
      'oe_media_file' => [
        'target_id' => $this->createFixtureFile($file_name)->id(),
      ],
    ]);
  }

  /**
   * Creates a remote document media.
   *
   * @param string $name
   *   The media name, used also as link text.
   * @param string $url
   *   The URL of the remote file.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createRemoteDocumentMedia(string $name, string $url): MediaInterface {
    return $this->createMedia('document', [
      'name' => $name,
      'oe_media_file_type' => 'remote',
      'oe_media_remote_file' => [
        'uri' => $url,
        'title' => $name,
      ],
    ]);
  }

  /**
   * Creates an AV Portal video media.
   *
   * @param string $url
   *   The AV Portal video URL.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createAvPortalVideoMedia(string $url): MediaInterface {
    return $this->createMedia('av_portal_video', [
      'oe_media_avportal_video' => $this->getAvPortalReference('av_portal_video', $url),
    ]);
  }

  /**
   * Creates an AV Portal photo media.
   *
   * @param string $url
   *   The AV Portal photo URL.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createAvPortalPhotoMedia(string $url): MediaInterface {
    return $this->createMedia('av_portal_photo', [
      'oe_media_avportal_photo' => $this->getAvPortalReference('av_portal_photo', $url),
    ]);
  }

  /**
   * Creates a remote video media.
   *
   * @param string $url
   *   The video URL.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createRemoteVideoMedia(string $url): MediaInterface {
    return $this->createMedia('remote_video', [
      'oe_media_oembed_video' => $url,
    ]);
  }

  /**
   * Creates a Webtools media.
   *
   * @param string $bundle
   *   The Webtools media bundle.
   * @param string $name
   *   The media name.
   * @param string $snippet
   *   The Webtools JSON snippet.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createWebtoolsMedia(string $bundle, string $name, string $snippet): MediaInterface {
    return $this->createMedia($bundle, [
      'name' => $name,
      'oe_media_webtools' => $snippet,
    ]);
  }

  /**
   * Creates an iframe based media.
   *
   * @param string $bundle
   *   The media bundle, either "iframe" or "video_iframe".
   * @param string $name
   *   The media name.
   * @param string $iframe
   *   The iframe markup.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function createIframeMedia(string $bundle, string $name, string $iframe): MediaInterface {
    return $this->createMedia($bundle, [
      'name' => $name,
      'oe_media_iframe' => $iframe,
    ]);
  }

  /**
   * Creates a permanent file entity from a file in the fixtures folder.
   *
   * @param string $file_name
   *   The name of the file in the fixtures folder.
   *
   * @return \Drupal\file\FileInterface
   *   The file entity.
   */
  protected function createFixtureFile(string $file_name): FileInterface {
    $file = \Drupal::service('file.repository')->writeData(
      file_get_contents(dirname(__DIR__) . '/fixtures/' . $file_name),
      'public://' . $file_name
    );
    $file->setPermanent();
    $file->save();

    return $file;
  }

  /**
   * Checks that a given image is present on the page.
   *
   * @param string $file_name
   *   The image file name.
   */
  protected function assertImagePresent(string $file_name): void {
    // Drupal appends an underscore and a number to the filename when duplicate
    // files are uploaded. We split up the filename and extension and match for
    // both.
    $parts = pathinfo($file_name);
    $this->assertSession()->elementExists('css', "img[src*='.{$parts['extension']}'][src*='{$parts['filename']}']");
  }

  /**
   * Checks that an oEmbed iframe is present in the page for a certain URL.
   *
   * @param string $url
   *   The video URL.
   */
  protected function assertOembedIframePresent(string $url): void {
    $partial_iframe_url = Url::fromRoute('media.oembed_iframe', [], [
      'query' => [
        'url' => $url,
      ],
    ])->toString();
    $this->assertSession()->elementExists('css', "iframe[src*='$partial_iframe_url']");
  }

  /**
   * Checks that an AV Portal video is rendered.
   *
   * @param string $title
   *   The video media name.
   */
  protected function assertAvPortalVideoPresent(string $title): void {
    $media = $this->loadMediaByName($title);
    $reference = $media->get('oe_media_avportal_video')->value;
    $this->assertSession()->elementExists('css', 'iframe[src*="' . $reference . '"]');
  }

  /**
   * Checks that an AV Portal photo is rendered.
   *
   * @param string $title
   *   The photo media name.
   * @param string $src
   *   The final photo source.
   */
  protected function assertAvPortalPhotoPresent(string $title, string $src): void {
    // Make sure that the media exists.
    $this->loadMediaByName($title);
    $this->assertSession()->elementExists('css', 'img.avportal-photo[src*="' . $src . '"]');
  }

  /**
   * Checks that the JSON of a Webtools media is present on the page.
   *
   * @param string $bundle
   *   The Webtools media bundle.
   * @param string $title
   *   The media name.
   */
  protected function assertWebtoolsJsonPresent(string $bundle, string $title): void {
    $media = $this->loadMediaByName($title, $bundle);
    // Run the escaping on the JSON data.
    $snippet = Json::encode(Json::decode($media->get('oe_media_webtools')->value));
    // Escape ' for the xpath expression.
    $xpath_query = "//script[@type='application/json'][.='" . addcslashes($snippet, '\'') . "']";
    $this->assertSession()->elementsCount('xpath', $xpath_query, 1);
  }

  /**
   * Loads a media entity by its name.
   *
   * @param string $name
   *   The media name.
   * @param string|null $bundle
   *   The media bundle. Optional.
   *
   * @return \Drupal\media\MediaInterface
   *   The media entity.
   */
  protected function loadMediaByName(string $name, ?string $bundle = NULL): MediaInterface {
    $properties = ['name' => $name];
    if ($bundle) {
      $properties['bundle'] = $bundle;
    }
    $media = \Drupal::entityTypeManager()->getStorage('media')->loadByProperties($properties);
    $this->assertNotEmpty($media, sprintf('The media named "%s" does not exist.', $name));

    return reset($media);
  }

  /**
   * Transforms an AV Portal URL into the reference stored in the media.
   *
   * @param string $bundle
   *   The AV Portal media bundle.
   * @param string $url
   *   The AV Portal URL.
   *
   * @return string
   *   The AV Portal reference.
   */
  protected function getAvPortalReference(string $bundle, string $url): string {
    /** @var \Drupal\media_avportal\Plugin\media\Source\MediaAvPortalSourceInterface $source */
    $source = \Drupal::entityTypeManager()->getStorage('media_type')->load($bundle)->getSource();
    return $source->transformUrlToReference($url);
  }

}
