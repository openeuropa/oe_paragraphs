<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_paragraphs\Traits;

/**
 * Helpers to test the paragraphs through the demo landing page content type.
 */
trait ParagraphsDemoTrait {

  /**
   * The CSS selector of the paragraphs field of the demo landing page form.
   */
  protected const DEMO_PARAGRAPHS_ELEMENT = '.field--name-field-oe-demo-body';

  /**
   * The content owner used to create demo landing pages.
   */
  protected const DEMO_CONTENT_OWNER = 'Committee on Agriculture and Rural Development';

  /**
   * Places the blocks needed to navigate the demo landing pages.
   */
  protected function placeDemoBlocks(): void {
    $this->drupalPlaceBlock('page_title_block');
    $this->drupalPlaceBlock('local_tasks_block');
  }

  /**
   * Creates a user with the "Editor" role of the demo module and logs in.
   */
  protected function drupalLoginAsEditor(): void {
    $user = $this->drupalCreateUser();
    $user->addRole('editor');
    $user->save();
    $this->drupalLogin($user);
  }

  /**
   * Opens the demo landing page creation form and fills the required fields.
   *
   * @param string $title
   *   The page title.
   */
  protected function startDemoLandingPage(string $title): void {
    $this->drupalGet('/node/add/oe_demo_landing_page');
    $page = $this->getSession()->getPage();
    $page->fillField('Title', $title);
    $page->fillField('Content owner', self::DEMO_CONTENT_OWNER);
  }

  /**
   * Asserts that a heading with the given text is present on the page.
   *
   * @param string $heading
   *   The heading text.
   */
  protected function assertHeading(string $heading): void {
    $xpath = $this->assertSession()->buildXPathQuery('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6][normalize-space()=:heading]', [
      ':heading' => $heading,
    ]);
    $this->assertSession()->elementExists('xpath', $xpath);
  }

  /**
   * Asserts that the given error messages are shown.
   *
   * @param string[] $messages
   *   The error messages.
   */
  protected function assertErrorMessages(array $messages): void {
    foreach ($messages as $message) {
      $this->assertSession()->statusMessageContains($message, 'error');
    }
  }

  /**
   * Asserts that the given texts are present on the page.
   *
   * @param string[] $texts
   *   The texts.
   */
  protected function assertTextsPresent(array $texts): void {
    foreach ($texts as $text) {
      $this->assertSession()->pageTextContains($text);
    }
  }

  /**
   * Asserts that the given texts are not present on the page.
   *
   * @param string[] $texts
   *   The texts.
   */
  protected function assertTextsNotPresent(array $texts): void {
    foreach ($texts as $text) {
      $this->assertSession()->pageTextNotContains($text);
    }
  }

}
