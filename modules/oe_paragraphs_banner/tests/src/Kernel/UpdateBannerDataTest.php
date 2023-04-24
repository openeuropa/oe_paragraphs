<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs_banner\Kernel;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsTestBase;
use Drush\TestTraits\DrushTestTrait;

/**
 * Test the oe-paragraphs-update-banner-data:run drush command.
 */
class UpdateBannerDataTest extends ParagraphsTestBase {

  use DrushTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'oe_paragraphs_banner',
  ];

  /**
   * Ensures the drush command migrates the data from the deprecated field.
   */
  public function testUpdatePersonData(): void {
    $paragraph_storage = $this->container->get('entity_type.manager')->getStorage('paragraph');
    $banners_data = [
      1 => [
        'title' => 'Page banner centered aligned',
        'revision_title' => 'Page banner centered aligned - updated',
        'description' => 'Centered aligned',
        'type' => 'page_center',
        'alignment' => 'centered',
        'size' => 'medium',
      ],
      2 => [
        'title' => 'Hero banner centered aligned',
        'revision_title' => 'Hero banner centered aligned - updated',
        'description' => 'Centered aligned',
        'type' => 'hero_center',
        'alignment' => 'centered',
        'size' => 'large',
      ],
      3 => [
        'title' => 'Page banner left aligned',
        'revision_title' => 'Page banner left aligned - updated',
        'description' => 'Left aligned',
        'type' => 'page_left',
        'alignment' => 'left',
        'size' => 'medium',
      ],
      4 => [
        'title' => 'Hero banner left aligned',
        'revision_title' => 'Hero banner left aligned - updated',
        'description' => 'Left aligned',
        'type' => 'hero_left',
        'alignment' => 'left',
        'size' => 'large',
      ],
    ];
    // Create 4 Banner paragraphs and add a new revision for each one.
    for ($i = 1; $i <= 4; $i++) {
      $banner = $paragraph_storage->create([
        'type' => 'oe_banner',
        'field_oe_title' => $banners_data[$i]['title'],
        'field_oe_text' => $banners_data[$i]['description'],
        'field_oe_link' => [
          'uri' => 'https://example.com',
          'title' => "CTA $i",
        ],
        'field_oe_banner_type' => $banners_data[$i]['type'],
      ]);
      $banner->save();
      $banner->set('field_oe_title', $banners_data[$i]['revision_title']);
      $banner->setNewRevision();
      $banner->save();
    }

    // Assert we have 8 total paragraph revisions.
    $revision_ids = $paragraph_storage->getQuery()
      ->allRevisions()
      ->execute();
    $this->assertEquals(8, count($revision_ids));

    // Run the command and assert no new revision was created.
    $this->drush('oe-paragraphs-update-banner-data:run');
    $revision_ids = $paragraph_storage->getQuery()
      ->allRevisions()
      ->execute();
    $this->assertEquals(8, count($revision_ids));

    // Load all the revisions.
    $revisions = $paragraph_storage->loadMultipleRevisions(array_keys($revision_ids));
    $this->assertNotEmpty($revisions);
    foreach ($revisions as $revision) {
      $title = $revision->get('field_oe_title')->value;
      for ($i = 1; $i <= 4; $i++) {
        if ($title !== $banners_data[$i]['title'] || $title !== $banners_data[$i]['revision_title']) {
          continue;
        }
        // Assert the old "Banner type" field value.
        $this->assertEquals($banners_data[$i]['type'], $revision->get('field_oe_banner_type')->value);
        // Assert the value of the new fields was updated.
        $this->assertEquals($banners_data[$i]['alignment'], $revision->get('field_oe_banner_alignment')->value);
        $this->assertEquals($banners_data[$i]['size'], $revision->get('field_oe_banner_size')->value);
      }
    }
  }

}
