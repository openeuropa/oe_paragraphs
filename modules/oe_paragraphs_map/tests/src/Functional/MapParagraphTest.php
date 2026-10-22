<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_map\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the map paragraph.
 *
 * @group batch1
 */
class MapParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a map paragraph.
   */
  public function testMapParagraph(): void {
    $this->createWebtoolsMedia('webtools_map', 'World map', '{"service": "map"}');
    $this->drupalLoginAsEditor();

    $this->startDemoLandingPage('Map paragraph test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Map');
    $this->assertFieldsPresent(['Use existing media'], self::DEMO_PARAGRAPHS_ELEMENT);
    $page->pressButton('Save');
    $this->assertErrorMessages(['Use existing media field is required.']);
    $page->fillField('Use existing media', 'World map');
    $page->pressButton('Save');
    $this->assertHeading('Map paragraph test');
    $this->assertWebtoolsJsonPresent('webtools_map', 'World map');
  }

}
