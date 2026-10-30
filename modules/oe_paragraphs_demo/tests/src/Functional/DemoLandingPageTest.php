<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_demo\Functional;

use Drupal\field\Entity\FieldConfig;
use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the demo landing page content type.
 *
 * @group batch1
 */
class DemoLandingPageTest extends ParagraphsDemoTestBase {

  /**
   * Tests that all the supported paragraph types are available.
   */
  public function testAvailableParagraphTypes(): void {
    $expected = [
      'Accordion',
      'Chart',
      'Carousel',
      'Content row',
      'Gallery',
      'Iframe',
      'Links block',
      'Listing item',
      'Listing item block',
      'Quote',
      'Rich text',
      'Media',
      'Banner',
      'Contextual navigation',
      'Social media follow',
      'Text with Featured media',
      'Description list',
      'Facts and figures',
      'Timeline',
      'Map',
      'Social feed - Deprecated',
      'Document',
      'Contact',
      'Illustration list with flags',
      'Illustration list with icons',
      'Illustration list with images',
      'Tabs',
    ];

    $target_bundles = FieldConfig::loadByName('node', 'oe_demo_landing_page', 'field_oe_demo_body')->getSetting('handler_settings')['target_bundles'];
    $paragraph_types = \Drupal::entityTypeManager()->getStorage('paragraphs_type')->loadMultiple($target_bundles);
    $actual = [];
    foreach ($paragraph_types as $paragraph_type) {
      $actual[] = (string) $paragraph_type->label();
    }

    sort($expected);
    sort($actual);
    $this->assertEquals($expected, $actual);
  }

}
