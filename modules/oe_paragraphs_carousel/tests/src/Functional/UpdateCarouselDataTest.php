<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs_carousel\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsTestBase;
use Drush\TestTraits\DrushTestTrait;

/**
 * Test the oe-paragraphs-update-carousel-data:run drush command.
 */
class UpdateCarouselDataTest extends ParagraphsTestBase {

  use DrushTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'oe_paragraphs_carousel',
    'composite_reference',
  ];

  /**
   * Ensures the drush command sets the 'medium' value for the new 'Size' field.
   */
  public function testUpdateCarouselData(): void {
    $paragraph_storage = $this->container->get('entity_type.manager')->getStorage('paragraph');
    $carousel_items = [];
    // Create 4 Carousel items.
    for ($i = 1; $i <= 4; $i++) {
      $carousel_item = $paragraph_storage->create([
        'type' => 'oe_carousel_item',
        'field_oe_title' => 'Carousel item ' . $i,
        'field_oe_link' => [
          'uri' => 'https://example.com',
          'title' => "CTA $i",
        ],
      ]);
      $carousel_item->save();
      $carousel_items[] = $carousel_item;
    }
    $carousel_paragraph = $paragraph_storage->create([
      'type' => 'oe_carousel',
      'field_oe_carousel_items' => $carousel_items,
    ]);
    $carousel_paragraph->save();

    // Create another Carousel item.
    $carousel_item = $paragraph_storage->create([
      'type' => 'oe_carousel_item',
      'field_oe_title' => 'Carousel item 5',
      'field_oe_link' => [
        'uri' => 'https://example.com',
        'title' => 'CTA 5',
      ],
    ]);
    $carousel_item->save();
    $carousel_items[] = $carousel_item;
    // Update the Carousel paragraph referenced items to create a new revision.
    $carousel_paragraph->set('field_oe_carousel_items', $carousel_items);
    $carousel_paragraph->setNewRevision();
    $carousel_paragraph->save();
    // Create another revision for the same data.
    $carousel_paragraph->setNewRevision();
    $carousel_paragraph->save();

    // Assert we have 18 total paragraph revisions: the 5 carousel items, the
    // main carousel paragraph with 3 revisions (each revision of the main
    // paragraph creates a new revision of the referenced entity).
    $revision_ids = $paragraph_storage->getQuery()
      ->allRevisions()
      ->accessCheck(FALSE)
      ->execute();
    $this->assertEquals(18, count($revision_ids));

    // Run the command and assert no new revision was created.
    $this->drush('oe-paragraphs-update-carousel-data:run');
    $revision_ids = $paragraph_storage->getQuery()
      ->allRevisions()
      ->accessCheck(FALSE)
      ->execute();
    $this->assertEquals(18, count($revision_ids));

    // Load all the revisions.
    $revisions = $paragraph_storage->loadMultipleRevisions(array_keys($revision_ids));
    $this->assertNotEmpty($revisions);
    foreach ($revisions as $revision) {
      if ($revision->bundle() !== 'oe_carousel') {
        // Skip the oe_carousel_item paragraphs since the 'Size' field is in
        // the main oe_carousel paragraph.
        continue;
      }
      // Assert the value of the new field was updated.
      $this->assertEquals('medium', $revision->get('field_oe_carousel_size')->value);
    }
  }

}
