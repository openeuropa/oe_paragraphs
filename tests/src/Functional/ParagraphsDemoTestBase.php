<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\oe_paragraphs\Traits\FieldsTrait;
use Drupal\Tests\oe_paragraphs\Traits\MediaTrait;
use Drupal\Tests\oe_paragraphs\Traits\ParagraphsDemoTrait;
use Drupal\Tests\oe_paragraphs\Traits\ParagraphsFormTrait;
use Drupal\Tests\oe_paragraphs\Traits\TraversingTrait;
use Drupal\Tests\oe_paragraphs\Traits\UtilityTrait;
use OpenEuropa\TestingUtilities\Traits\CachedDatabaseInstallTrait;

/**
 * Base class to test paragraphs through the demo landing page content type.
 */
abstract class ParagraphsDemoTestBase extends BrowserTestBase {

  use CachedDatabaseInstallTrait;
  use FieldsTrait;
  use MediaTrait;
  use ParagraphsDemoTrait;
  use ParagraphsFormTrait;
  use TraversingTrait;
  use UtilityTrait;

  /**
   * {@inheritdoc}
   *
   * Subclasses should not add modules, so that all the tests share the same
   * cached database install.
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
  }

}
