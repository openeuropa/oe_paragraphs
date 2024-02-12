<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Behat;

use Drupal\Core\Url;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\Tests\oe_paragraphs\Traits\FieldsTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;

/**
 * Defines generic step definitions.
 */
class DrupalContext extends RawDrupalContext {

  use FieldsTrait;
  use UtilityTrait;

  /**
   * Assert that certain fields are present in the given region.
   *
   * @param string $fields
   *   Fields.
   * @param string $region
   *   The region where to search.
   *
   * @throws \Exception
   *   Thrown when an expected field is not present.
   *
   * @Then (the following )field(s) should be present :fields in the :region region
   */
  public function assertFieldsPresent(string $fields, string $region): void {
    $fields = $this->explodeCommaSeparatedStepArgument($fields);
    $region = $this->getSession()->getPage()->find('region', $region);
    $not_found = [];
    foreach ($fields as $field) {
      $is_found = $this->findField($field, $region);
      if (!$is_found) {
        $not_found[] = $field;
      }
    }
    if ($not_found) {
      throw new \Exception("Field(s) expected, but not found: " . implode(', ', $not_found));
    }
  }

  /**
   * Assert that certain fields are not present in the given region.
   *
   * @param string $fields
   *   Fields.
   * @param string $region
   *   The region where to search.
   *
   * @throws \Exception
   *   Thrown when a column name is incorrect.
   *
   * @Then (the following )field(s) should not be present :fields in the :region region
   */
  public function assertFieldsNotPresent(string $fields, string $region): void {
    $fields = $this->explodeCommaSeparatedStepArgument($fields);
    $region = $this->getSession()->getPage()->find('region', $region);
    foreach ($fields as $field) {
      $is_found = $this->findField($field, $region);
      if ($is_found) {
        throw new \Exception("Field should not be found, but is present: " . $field);
      }
    }
  }

  /**
   * Checks that a given image is present on the page.
   *
   * @param string $filename
   *   The image filename.
   *
   * @Then I (should )see the image :filename
   */
  public function assertImagePresent(string $filename): void {
    // Drupal appends an underscore and a number to the filename when duplicate
    // files are uploaded, for example when a test runs more then once.
    // We split up the filename and extension and match for both.
    $parts = pathinfo($filename);
    $extension = $parts['extension'];
    $filename = $parts['filename'];
    $this->assertSession()->elementExists('css', "img[src*='.$extension'][src*='$filename']");
  }

  /**
   * Checks that an OEmbed is present in the page for a certain url.
   *
   * @param string $url
   *   The video url.
   *
   * @Then I (should )see the embedded video player for :url
   */
  public function assertOembedIframePresent(string $url): void {
    $partial_iframe_url = Url::fromRoute('media.oembed_iframe', [], [
      'query' => [
        'url' => $url,
      ],
    ])->toString();
    $this->assertSession()->elementExists('css', "iframe[src*='$partial_iframe_url']");
  }

  /**
   * Checks that the AV Portal video is rendered.
   *
   * @param string $title
   *   The video title.
   *
   * @Then I should see the AV Portal video :title
   * @todo To be removed once oe_content 1.8.0 is released.
   */
  public function assertAvPortalVideoIframe(string $title): void {
    $media = \Drupal::entityTypeManager()->getStorage('media')->loadByProperties(['name' => $title]);
    if (!$media) {
      throw new \Exception(sprintf('The media named "%s" does not exist', $title));
    }

    $media = reset($media);
    $ref = $media->get('oe_media_avportal_video')->value;

    $iframe = $this->getSession()->getPage()->findAll('css', 'iframe[src*="' . $ref . '"]');
    if (!$iframe) {
      throw new \Exception(sprintf('The video named "%s" was not found on the page.', $title));
    }
  }

  /**
   * Checks that the AV Portal photo is rendered.
   *
   * @param string $title
   *   The photo title.
   * @param string $src
   *   The final photo source.
   *
   * @Then I should see the AV Portal photo :title with source :src
   */
  public function assertAvPortalPhoto(string $title, string $src): void {
    $media = \Drupal::entityTypeManager()->getStorage('media')->loadByProperties(['name' => $title]);
    if (!$media) {
      throw new \Exception(sprintf('The media named "%s" does not exist', $title));
    }

    $image = $this->getSession()->getPage()->findAll('css', 'img.avportal-photo[src*="' . $src . '"]');
    if (!$image) {
      throw new \Exception(sprintf('The imaged named "%s" was not found on the page.', $title));
    }
  }

  /**
   * Presses button with specified id|name|title|alt|value on a given region.
   *
   * @param string $button
   *   The button identifier.
   * @param string $region
   *   The name of the region.
   *
   * @When I press the :button button in the :region region
   *
   * @throws \Exception
   *   If region or button within it cannot be found.
   */
  public function pressRegionButton(string $button, string $region): void {
    $this->getSession()->getPage()->find('region', $region)->pressButton($button);
  }

}
