<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_chart\Functional;

use Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase;

/**
 * Tests the chart paragraph.
 *
 * @group batch1
 */
class ChartParagraphTest extends ParagraphsDemoTestBase {

  /**
   * Tests the creation of a chart paragraph.
   */
  public function testChartParagraph(): void {
    $this->createWebtoolsMedia('webtools_chart', 'Basic chart', '{"service":"charts","data":{"series":[{"name":"Y","data":[{"name":"1","y":0.5}]}]},"provider":"highcharts"}');
    $this->drupalLoginAsEditor();

    $this->startDemoLandingPage('Chart paragraph test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Chart');
    $this->assertFieldsPresent(['Use existing media'], self::DEMO_PARAGRAPHS_ELEMENT);
    $page->pressButton('Save');
    $this->assertErrorMessages(['Use existing media field is required.']);
    $page->fillField('Use existing media', 'Basic chart');
    $page->pressButton('Save');
    $this->assertHeading('Chart paragraph test');
    $this->assertWebtoolsJsonPresent('webtools_chart', 'Basic chart');
  }

}
