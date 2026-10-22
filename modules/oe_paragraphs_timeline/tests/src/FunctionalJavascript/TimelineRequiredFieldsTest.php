<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs_timeline\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\oe_paragraphs\Traits\FieldsTrait;
use Drupal\Tests\oe_paragraphs\Traits\ParagraphsDemoTrait;
use Drupal\Tests\oe_paragraphs\Traits\TraversingTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;
use OpenEuropa\TestingUtilities\Traits\CachedDatabaseInstallTrait;

/**
 * Tests that the timeline item fields are marked visually as required.
 *
 * @group batch2
 */
class TimelineRequiredFieldsTest extends WebDriverTestBase {

  use CachedDatabaseInstallTrait;
  use FieldsTrait;
  use ParagraphsDemoTrait;
  use TraversingTrait;
  use UtilityTrait;

  /**
   * {@inheritdoc}
   *
   * The same modules as the non-JavaScript demo tests are installed, so that
   * all the tests share the same cached database install.
   *
   * @see \Drupal\Tests\oe_paragraphs\Functional\ParagraphsDemoTestBase
   */
  protected static $modules = [
    'block',
    'oe_paragraphs_demo',
    'media_avportal_mock',
    'oe_media_oembed_mock',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->cacheDbInstall = TRUE;
    parent::setUp();

    $this->placeDemoBlocks();
    // The toolbar is fixed at the top of the page and intercepts the clicks on
    // the elements scrolled underneath it, such as the "Save" button.
    user_role_revoke_permissions('editor', ['access toolbar']);
  }

  /**
   * Tests the required state of the timeline item fields.
   */
  public function testTimelineRequiredFields(): void {
    $this->drupalLoginAsEditor();
    $this->startDemoLandingPage('Timeline paragraph fields test');
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $page->pressButton('List additional actions');
    $page->pressButton('Add Timeline');
    $assert_session->assertWaitOnAjaxRequest();
    $this->assertItemRequiredFields(1, TRUE);

    // Fill the first item with values, so we can add a new item.
    $this->fillMultipleCardinalityFieldItem('Items', 1, 'Label', 'Item 1 label');
    $this->fillMultipleCardinalityFieldItem('Items', 1, 'Title', 'Item 1 title');
    $assert_session->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Add another item');
    $assert_session->assertWaitOnAjaxRequest();
    // When all the fields are empty, no field is marked as required.
    $this->assertItemRequiredFields(2, FALSE);

    // Filling the content field doesn't trigger any required state, as the
    // form API states don't behave well with fields that host editors.
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Content', 'Item 2 content');
    $this->assertItemRequiredFields(2, FALSE);
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Content', '');

    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', 'Item 2 label');
    $this->assertItemRequiredFields(2, TRUE);
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', '');
    $this->assertItemRequiredFields(2, FALSE);

    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', 'Item 2 title');
    $this->assertItemRequiredFields(2, TRUE);
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', '');
    $this->assertItemRequiredFields(2, FALSE);

    // Add another item to verify that the correct items are marked when
    // multiple field items are available.
    $assert_session->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Add another item');
    $assert_session->assertWaitOnAjaxRequest();
    $this->fillMultipleCardinalityFieldItem('Items', 3, 'Label', 'Item 3 label');
    $this->assertItemRequiredFields(3, TRUE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Label', FALSE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Title', FALSE);

    $this->fillMultipleCardinalityFieldItem('Items', 3, 'Label', '');
    $this->assertMultipleCardinalityFieldItemRequired('Items', 3, 'Label', FALSE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 3, 'Title', FALSE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Label', FALSE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Title', FALSE);

    // Fill the second item.
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', 'Item 2 label');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', 'Item 2 title');
    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph fields test has been created.', 'status');

    // Re-edit the page and verify that on load fields are marked are required.
    $this->clickLink('Edit');
    $assert_session->elementExists('css', self::DEMO_PARAGRAPHS_ELEMENT)->pressButton('Edit all');
    $assert_session->assertWaitOnAjaxRequest();
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Label', TRUE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Title', TRUE);

    // Clearing the fields of the second item will unmark them and allow to
    // save.
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Label', '');
    $this->fillMultipleCardinalityFieldItem('Items', 2, 'Title', '');
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Label', FALSE);
    $this->assertMultipleCardinalityFieldItemRequired('Items', 2, 'Title', FALSE);

    $page->pressButton('Save');
    $assert_session->statusMessageContains('Demo landing page Timeline paragraph fields test has been updated.', 'status');
  }

  /**
   * Asserts the required state of the label and title fields of an item.
   *
   * The content field is never marked as required.
   *
   * @param int $item
   *   The item number. 1-based.
   * @param bool $required
   *   Whether label and title are expected to be marked as required.
   */
  protected function assertItemRequiredFields(int $item, bool $required): void {
    $this->assertMultipleCardinalityFieldItemRequired('Items', $item, 'Label', $required);
    $this->assertMultipleCardinalityFieldItemRequired('Items', $item, 'Title', $required);
    $this->assertMultipleCardinalityFieldItemRequired('Items', $item, 'Content', FALSE);
  }

}
