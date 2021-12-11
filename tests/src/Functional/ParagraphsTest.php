<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_paragraphs\Functional;

use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests paragraphs forms.
 */
class ParagraphsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field_ui',
    'oe_paragraphs',
  ];

  /**
   * The administration theme name.
   *
   * @var string
   */
  protected $adminTheme = 'stark';

  /**
   * A user with administration permissions.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser([
      'access content',
      'access administration pages',
      'administer site configuration',
      'administer users',
      'administer permissions',
      'administer content types',
      'administer node fields',
      'administer node display',
      'administer nodes',
      'bypass node access',
    ]);
    $this->drupalGet(Url::fromRoute('user.login'));
    $this->drupalLogin($this->adminUser);
    $this->drupalCreateContentType([
      'type' => 'paragraphs_test',
      'name' => 'Paragraphs Test',
    ]);
    $this->addParagraphsField();
  }

  /**
   * Test Facts and figures paragraphs form.
   */
  public function testFactsAndFiguresParagraph(): void {
    $this->drupalGet('/node/add/paragraphs_test');
    $page = $this->getSession()->getPage();
    $page->pressButton('Add Facts and figures');
    // Assert the Facts and figures fields appear.
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_link][0][uri]');
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_link][0][title]');
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_title][0][value]');
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_title][0][value]');
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_subtitle][0][value]');
    $this->assertSession()->fieldExists('oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_plain_text_long][0][value]');

    $allowed_values = [
      '_none',
      'arrow-down',
      'external',
      'arrow-up',
      'audio',
      'book',
      'breadcrumb',
      'brochure',
      'budget',
      'calendar',
      'camera',
      'check',
      'close',
      'close-dark',
      'copy',
      'data',
      'digital',
      'down',
      'download',
      'edit',
      'energy',
      'error',
      'euro',
      'facebook',
      'faq',
      'feedback',
      'file',
      'generic-lang',
      'global',
      'googleplus',
      'growth',
      'image',
      'in',
      'info',
      'infographic',
      'language',
      'left',
      'linkedin',
      'livestreaming',
      'location',
      'multiple-files',
      'organigram',
      'package',
      'presentation',
      'regulation',
      'right',
      'rss',
      'search',
      'share',
      'slides',
      'spinner',
      'spreadsheet',
      'success',
      'tag-close',
      'twitter',
      'up',
      'video',
      'warning',
    ];
    foreach ($allowed_values as $allowed_value) {
      $this->assertSession()->elementsCount('css', 'option[value="' . $allowed_value . '"]', 1);
    }

    $values = [
      'title[0][value]' => 'Test Fact and figures node title',
      'oe_paragraphs[0][subform][field_oe_title][0][value]' => 'Fact and figures block',
      'oe_paragraphs[0][subform][field_oe_link][0][uri]' => 'https://www.google.com',
      'oe_paragraphs[0][subform][field_oe_link][0][title]' => 'Read more',
      'oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_title][0][value]' => "Fact title",
      'oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_subtitle][0][value]' => "Fact subtitle",
      'oe_paragraphs[0][subform][field_oe_paragraphs][0][subform][field_oe_plain_text_long][0][value]' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nisl lacus, ultrices vel interdum ultrices, tempus in justo. Duis semper rhoncus ex, accumsan pharetra lacus feugiat et.",
    ];

    $this->submitForm($values, 'Save');
    $this->drupalGet('/node/1');

    // Assert paragraph values are displayed correctly.
    $this->assertSession()->pageTextContains('Fact and figures block');
    $this->assertSession()->pageTextContains('Read more');
    $this->assertSession()->pageTextContains('Fact title');
    $this->assertSession()->pageTextContains('Fact subtitle');
    $this->assertSession()->pageTextContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nisl lacus, ultrices vel interdum ultrices, tempus in justo. Duis semper rhoncus ex, accumsan pharetra lacus feugiat et.');
  }

  /**
   * Create content type with paragraphs field.
   */
  protected function addParagraphsField() {
    // Add a paragraphs field.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'oe_paragraphs',
      'entity_type' => 'node',
      'type' => 'entity_reference_revisions',
      'cardinality' => '-1',
      'settings' => [
        'target_type' => 'paragraph',
      ],
    ]);
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'paragraphs_test',
      'settings' => [
        'handler' => 'default:paragraph',
        'handler_settings' => ['target_bundles' => NULL],
      ],
    ]);
    $field->save();

    $form_display = \Drupal::service('entity_display.repository')->getFormDisplay('node', 'paragraphs_test');
    $form_display = $form_display->setComponent('oe_paragraphs', ['type' => 'paragraphs']);
    $form_display->save();
  }

}
