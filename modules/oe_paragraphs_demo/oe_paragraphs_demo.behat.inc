<?php

/**
 * @file
 * Contains \OeParagraphsDemoSubContext.
 */

declare(strict_types = 1);

use Drupal\DrupalExtension\Context\DrupalSubContextBase;

/**
 * Behat step definitions for testing the OE Paragraphs demo module.
 */
class OeParagraphsDemoSubContext extends DrupalSubContextBase {

  /**
   * Navigates to the add form of a demo landing page.
   *
   * @When I go to the add form of the demo landing page
   */
  public function visitDemoLandingPageAddForm(): void {
    $this->visitPath('node/add/oe_demo_landing_page');
  }

}
