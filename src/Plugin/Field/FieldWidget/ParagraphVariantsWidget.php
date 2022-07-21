<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Render\Element;
use Drupal\Core\TypedData\TranslationStatusInterface;
use Drupal\field_group\FormatterHelper;
use Drupal\paragraphs\Plugin\Field\FieldWidget\ParagraphsWidget;

/**
 * Allows selection of form modes as paragraph variants.
 *
 * @FieldWidget(
 *   id = "oe_paragraphs_variants",
 *   label = @Translation("Paragraphs with variants"),
 *   description = @Translation("A widget that allows to switch between variants."),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ParagraphVariantsWidget extends ParagraphsWidget {

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   *
   * Based on commit #0fafd516 from paragraphs module.
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $field_name = $this->fieldDefinition->getName();
    $parents = $element['#field_parents'];

    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraphs_entity */
    $paragraphs_entity = NULL;
    $host = $items->getEntity();
    $widget_state = static::getWidgetState($parents, $field_name, $form_state);

    $entity_type_manager = \Drupal::entityTypeManager();
    $target_type = $this->getFieldSetting('target_type');

    $item_mode = $widget_state['paragraphs'][$delta]['mode'] ?? 'edit';
    $default_edit_mode = $this->getSetting('edit_mode');

    $closed_mode_setting = $widget_state['closed_mode'] ?? $this->getSetting('closed_mode');
    $autocollapse_setting = $widget_state['autocollapse'] ?? $this->getSetting('autocollapse');

    $show_must_be_saved_warning = !empty($widget_state['paragraphs'][$delta]['show_warning']);

    if (isset($widget_state['paragraphs'][$delta]['entity'])) {
      $paragraphs_entity = $widget_state['paragraphs'][$delta]['entity'];
    }
    elseif (isset($items[$delta]->entity)) {
      $paragraphs_entity = $items[$delta]->entity;

      // We don't have a widget state yet, get from selector settings.
      if (!isset($widget_state['paragraphs'][$delta]['mode'])) {

        if ($default_edit_mode == 'open' || $widget_state['items_count'] < $this->getSetting('closed_mode_threshold')) {
          $item_mode = 'edit';
        }
        elseif ($default_edit_mode == 'closed') {
          $item_mode = 'closed';
        }
        elseif ($default_edit_mode == 'closed_expand_nested') {
          $item_mode = 'closed';
          $field_definitions = $paragraphs_entity->getFieldDefinitions();

          // If the paragraph contains other paragraphs, then open it.
          foreach ($field_definitions as $field_definition) {
            if ($field_definition->getType() == 'entity_reference_revisions' && $field_definition->getSetting('target_type') == 'paragraph') {
              $item_mode = 'edit';
              break;
            }
          }
        }
      }
    }
    elseif (isset($widget_state['selected_bundle'])) {

      $entity_type = $entity_type_manager->getDefinition($target_type);
      $bundle_key = $entity_type->getKey('bundle');

      $paragraphs_entity = $entity_type_manager->getStorage($target_type)->create([
        $bundle_key => $widget_state['selected_bundle'],
      ]);
      $paragraphs_entity->setParentEntity($host, $field_name);

      $item_mode = 'edit';
    }

    if ($paragraphs_entity) {
      // Detect if we are translating.
      $this->initIsTranslating($form_state, $host);
      $langcode = $form_state->get('langcode');

      if (!$this->isTranslating) {
        // Set the langcode if we are not translating.
        $langcode_key = $paragraphs_entity->getEntityType()->getKey('langcode');
        if ($paragraphs_entity->get($langcode_key)->value != $langcode) {
          // If a translation in the given language already exists, switch to
          // that. If there is none yet, update the language.
          if ($paragraphs_entity->hasTranslation($langcode)) {
            $paragraphs_entity = $paragraphs_entity->getTranslation($langcode);
          }
          else {
            $paragraphs_entity->set($langcode_key, $langcode);
          }
        }
      }
      else {
        // If the node is being translated, the paragraphs should be all open
        // when the form is not being rebuilt (E.g. when clicked on a paragraphs
        // action) and when the translation is being added.
        if (!$form_state->isRebuilding() && $host->getTranslationStatus($langcode) == TranslationStatusInterface::TRANSLATION_CREATED) {
          $item_mode = 'edit';
        }
        // Add translation if missing for the target language.
        if (!$paragraphs_entity->hasTranslation($langcode)) {
          // Get the selected translation of the paragraph entity.
          $entity_langcode = $paragraphs_entity->language()->getId();
          $source = $form_state->get(['content_translation', 'source']);
          $source_langcode = $source ? $source->getId() : $entity_langcode;
          // Make sure the source language version is used if available. It is a
          // the host and fetching the translation without this check could lead
          // valid scenario to have no paragraphs items in the source version of
          // to an exception.
          if ($paragraphs_entity->hasTranslation($source_langcode)) {
            $paragraphs_entity = $paragraphs_entity->getTranslation($source_langcode);
          }
          // The paragraphs entity has no content translation source field if
          // no paragraph entity field is translatable, even if the host is.
          if ($paragraphs_entity->hasField('content_translation_source')) {
            // Initialise the translation with source language values.
            $paragraphs_entity->addTranslation($langcode, $paragraphs_entity->toArray());
            $translation = $paragraphs_entity->getTranslation($langcode);
            $manager = \Drupal::service('content_translation.manager');
            $manager->getTranslationMetadata($translation)->setSource($paragraphs_entity->language()->getId());
          }
        }
        // If any paragraphs type is translatable do not switch.
        if ($paragraphs_entity->hasField('content_translation_source')) {
          // Switch the paragraph to the translation.
          $paragraphs_entity = $paragraphs_entity->getTranslation($langcode);
        }
      }

      // If untranslatable fields are hidden while translating, we are
      // translating the parent and the Paragraph is open, then close the
      // Paragraph if it does not have translatable fields.
      $translating_force_close = FALSE;
      if (\Drupal::moduleHandler()->moduleExists('content_translation')) {
        $manager = \Drupal::service('content_translation.manager');
        $settings = $manager->getBundleTranslationSettings('paragraph', $paragraphs_entity->getParagraphType()->id());
        if (!empty($settings['untranslatable_fields_hide']) && $this->isTranslating) {
          $translating_force_close = TRUE;
          $display = EntityFormDisplay::collectRenderDisplay($paragraphs_entity, $this->getSetting('form_display_mode'));
          // Check if the paragraph has translatable fields.
          foreach (array_keys($display->get('content')) as $field) {
            if ($paragraphs_entity->hasField($field)) {
              $field_definition = $paragraphs_entity->get($field)->getFieldDefinition();
              // Check if we are referencing paragraphs.
              $is_paragraph = ($field_definition->getType() == 'entity_reference_revisions' && $field_definition->getSetting('target_type') == 'paragraph');
              if ($is_paragraph || $field_definition->isTranslatable()) {
                $translating_force_close = FALSE;
                break;
              }
            }
          }

          if ($translating_force_close) {
            $item_mode = 'closed';
          }
        }
      }

      $element_parents = $parents;
      $element_parents[] = $field_name;
      $element_parents[] = $delta;
      $element_parents[] = 'subform';

      $id_prefix = implode('-', array_merge($parents, [$field_name, $delta]));
      $wrapper_id = Html::getUniqueId($id_prefix . '-item-wrapper');

      $element += [
        '#type' => 'container',
        '#element_validate' => [[$this, 'elementValidate']],
        '#paragraph_type' => $paragraphs_entity->bundle(),
        'subform' => [
          '#type' => 'container',
          '#parents' => $element_parents,
        ],
      ];

      $element['#prefix'] = '<div id="' . $wrapper_id . '">';
      $element['#suffix'] = '</div>';

      // Create top section structure with all needed subsections.
      $element['top'] = [
        '#type' => 'container',
        '#weight' => -1000,
        '#attributes' => [
          'class' => [
            'paragraph-top',
            // Add a flag to indicate if the add_above feature is enabled and
            // should be injected client-side.
            $this->isFeatureEnabled('add_above') ? 'add-above-on' : 'add-above-off',
          ],
        ],
        // Section for paragraph type information.
        'type' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['paragraph-type']],
        ],
        // Section for info icons.
        'icons' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['paragraph-info']],
        ],
        'summary' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['paragraph-summary']],
        ],
        // Paragraphs actions element for actions and dropdown actions.
        'actions' => [
          '#type' => 'paragraphs_actions',
        ],
      ];
      // Holds information items.
      $info = [];

      $item_bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($target_type);
      if (isset($item_bundles[$paragraphs_entity->bundle()])) {
        $bundle_info = $item_bundles[$paragraphs_entity->bundle()];

        $element['top']['type']['label'] = ['#markup' => $bundle_info['label']];

        // Type icon and label bundle.
        if ($icon_url = $paragraphs_entity->type->entity->getIconUrl()) {
          $element['top']['type']['icon'] = [
            '#theme' => 'image',
            '#uri' => $icon_url,
            '#attributes' => [
              'class' => ['paragraph-type-icon'],
              'title' => $bundle_info['label'],
            ],
            '#weight' => 0,
            // We set inline height and width so icon don't resize on first load
            // while CSS is still not loaded.
            '#height' => 16,
            '#width' => 16,
          ];
        }
        $element['top']['type']['label'] = [
          '#markup' => '<span class="paragraph-type-label">' . $bundle_info['label'] . '</span>',
          '#weight' => 1,
        ];

        // Widget actions.
        $widget_actions = [
          'actions' => [],
          'dropdown_actions' => [],
        ];

        $widget_actions['dropdown_actions']['duplicate_button'] = [
          '#type' => 'submit',
          '#value' => $this->t('Duplicate'),
          '#name' => $id_prefix . '_duplicate',
          '#weight' => 502,
          '#submit' => [[get_class($this), 'duplicateSubmit']],
          '#limit_validation_errors' => [
            array_merge($parents, [$field_name, $delta]),
          ],
          '#delta' => $delta,
          '#ajax' => [
            'callback' => [get_class($this), 'itemAjax'],
            'wrapper' => $widget_state['ajax_wrapper_id'],
          ],
          '#access' => $this->duplicateButtonAccess($paragraphs_entity),
        ];

        // Force the closed mode when the user cannot edit the Paragraph.
        if (!$paragraphs_entity->access('update')) {
          $item_mode = 'closed';
        }

        if ($item_mode != 'remove') {
          $widget_actions['dropdown_actions']['remove_button'] = [
            '#type' => 'submit',
            '#value' => $this->t('Remove'),
            '#name' => $id_prefix . '_remove',
            '#weight' => 501,
            '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
            // Ignore all validation errors because deleting invalid paragraphs
            // is allowed.
            '#limit_validation_errors' => [],
            '#delta' => $delta,
            '#ajax' => [
              'callback' => [get_class($this), 'itemAjax'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
            ],
            '#access' => $this->removeButtonAccess($paragraphs_entity),
            '#paragraphs_mode' => 'remove',
          ];
        }

        if ($item_mode == 'edit') {
          if (isset($paragraphs_entity)) {
            $widget_actions['actions']['collapse_button'] = [
              '#value' => $this->t('Collapse'),
              '#name' => $id_prefix . '_collapse',
              '#weight' => 1,
              '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
              '#limit_validation_errors' => [
                array_merge($parents, [$field_name, $delta]),
              ],
              '#delta' => $delta,
              '#ajax' => [
                'callback' => [get_class($this), 'itemAjax'],
                'wrapper' => $widget_state['ajax_wrapper_id'],
              ],
              '#access' => $paragraphs_entity->access('update') && !$translating_force_close,
              '#paragraphs_mode' => 'closed',
              '#paragraphs_show_warning' => TRUE,
              '#attributes' => [
                'class' => [
                  'paragraphs-icon-button',
                  'paragraphs-icon-button-collapse',
                ],
                'title' => $this->t('Collapse'),
              ],
            ];
          }
        }
        else {
          $widget_actions['actions']['edit_button'] = $this->expandButton([
            '#type' => 'submit',
            '#value' => $this->t('Edit'),
            '#name' => $id_prefix . '_edit',
            '#weight' => 1,
            '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
            '#limit_validation_errors' => [
              array_merge($parents, [$field_name, $delta]),
            ],
            '#delta' => $delta,
            '#ajax' => [
              'callback' => [get_class($this), 'itemAjax'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
            ],
            '#access' => $paragraphs_entity->access('update') && !$translating_force_close,
            '#paragraphs_mode' => 'edit',
            '#attributes' => [
              'class' => [
                'paragraphs-icon-button',
                'paragraphs-icon-button-edit',
              ],
              'title' => $this->t('Edit'),
            ],
          ]);

          if ($show_must_be_saved_warning && $paragraphs_entity->isChanged()) {
            $info['changed'] = [
              '#theme' => 'paragraphs_info_icon',
              '#message' => $this->t('You have unsaved changes on this @title item.', ['@title' => $this->getSetting('title')]),
              '#icon' => 'changed',
            ];
          }

          if (!$paragraphs_entity->isPublished()) {
            $info['preview'] = [
              '#theme' => 'paragraphs_info_icon',
              '#message' => $this->t('Unpublished'),
              '#icon' => 'view',
            ];
          }
        }

        // If update is disabled we will show lock icon in actions section.
        if (!$paragraphs_entity->access('update')) {
          $widget_actions['actions']['edit_disabled'] = [
            '#theme' => 'paragraphs_info_icon',
            '#message' => $this->t('You are not allowed to edit or remove this @title.', ['@title' => $this->getSetting('title')]),
            '#icon' => 'lock',
            '#weight' => 1,
          ];
        }

        if (!$paragraphs_entity->access('update') && $paragraphs_entity->access('delete')) {
          $info['edit'] = [
            '#theme' => 'paragraphs_info_icon',
            '#message' => $this->t('You are not allowed to edit this @title.', ['@title' => $this->getSetting('title')]),
            '#icon' => 'edit-disabled',
          ];
        }
        elseif (!$paragraphs_entity->access('delete') && $paragraphs_entity->access('update')) {
          $info['remove'] = [
            '#theme' => 'paragraphs_info_icon',
            '#message' => $this->t('You are not allowed to remove this @title.', ['@title' => $this->getSetting('title')]),
            '#icon' => 'delete-disabled',
          ];
        }

        $context = [
          'form' => $form,
          'widget' => self::getWidgetState($parents, $field_name, $form_state, $widget_state),
          'items' => $items,
          'delta' => $delta,
          'element' => $element,
          'form_state' => $form_state,
          'paragraphs_entity' => $paragraphs_entity,
          'is_translating' => $this->isTranslating,
          'allow_reference_changes' => $this->allowReferenceChanges(),
        ];

        // Allow modules to alter widget actions.
        \Drupal::moduleHandler()->alter('paragraphs_widget_actions', $widget_actions, $context);

        if (count($widget_actions['actions'])) {
          // Expand all actions to proper submit elements and add it to top
          // actions sub component.
          $element['top']['actions']['actions'] = array_map([
            $this,
            'expandButton',
          ], $widget_actions['actions']);
        }

        if (count($widget_actions['dropdown_actions'])) {
          // Expand all dropdown actions to proper submit elements and add
          // them to top dropdown actions sub component.
          $element['top']['actions']['dropdown_actions'] = array_map([
            $this,
            'expandButton',
          ], $widget_actions['dropdown_actions']);
        }
      }

      // Re-use the entity form display selected in previous form builds.
      if (isset($widget_state['paragraphs'][$delta]['display'])) {
        $display = $widget_state['paragraphs'][$delta]['display'];
      }
      else {
        // Extract from the current entity value or use the one configured in
        // the widget.
        $display = EntityFormDisplay::collectRenderDisplay(
          $paragraphs_entity,
          $paragraphs_entity->get('oe_paragraphs_variant')->first()->value ?? $this->getSetting('form_display_mode')
        );
      }

      // @todo Remove as part of https://www.drupal.org/node/2640056
      if (\Drupal::moduleHandler()->moduleExists('field_group')) {
        $context = [
          'entity_type' => $paragraphs_entity->getEntityTypeId(),
          'bundle' => $paragraphs_entity->bundle(),
          'entity' => $paragraphs_entity,
          'context' => 'form',
          'display_context' => 'form',
          'mode' => $display->getMode(),
        ];

        field_group_attach_groups($element['subform'], $context);
        $element['subform']['#process'][] = [
          FormatterHelper::class,
          'formProcess',
        ];
      }

      if ($item_mode == 'edit') {
        $display->buildForm($paragraphs_entity, $element['subform'], $form_state);
        $hide_untranslatable_fields = $paragraphs_entity->isDefaultTranslationAffectedOnly();

        $summary = $paragraphs_entity->getSummaryItems();
        if (!empty($summary)) {
          $element['top']['summary']['fields_info'] = [
            '#theme' => 'paragraphs_summary',
            '#summary' => $summary,
            '#expanded' => TRUE,
            '#access' => $paragraphs_entity->access('update') || $paragraphs_entity->access('view'),
          ];
        }
        $info = array_merge($info, $paragraphs_entity->getIcons());

        foreach (Element::children($element['subform']) as $field) {
          if ($paragraphs_entity->hasField($field)) {
            $field_definition = $paragraphs_entity->get($field)->getFieldDefinition();

            // Do a check if we have to add a class to the form element. We need
            // those classes (paragraphs-content and paragraphs-behavior)
            // to show and hide elements, depending of the active perspective.
            // We need them to filter out entity reference revisions fields that
            // reference paragraphs, cause otherwise we have problems
            // with showing and hiding the right fields in nested paragraphs.
            $is_paragraph_field = FALSE;
            if ($field_definition->getType() == 'entity_reference_revisions') {
              // Check if we are referencing paragraphs.
              if ($field_definition->getSetting('target_type') == 'paragraph') {
                $is_paragraph_field = TRUE;
              }
            }

            if (!$is_paragraph_field) {
              $element['subform'][$field]['#attributes']['class'][] = 'paragraphs-content';
              $element['top']['summary']['fields_info'] = [
                '#theme' => 'paragraphs_summary',
                '#summary' => $summary,
                '#expanded' => TRUE,
                '#access' => $paragraphs_entity->access('update') || $paragraphs_entity->access('view'),
              ];
            }
            $translatable = $field_definition->isTranslatable();
            // Hide untranslatable fields when configured to do so except
            // paragraph fields.
            if (!$translatable && $this->isTranslating && !$is_paragraph_field) {
              if ($hide_untranslatable_fields) {
                $element['subform'][$field]['#access'] = FALSE;
              }
              else {
                $element['subform'][$field]['widget']['#after_build'][] = [
                  static::class,
                  'addTranslatabilityClue',
                ];
              }
            }
          }
        }

        // Build the behavior plugins fields, do not display behaviors when
        // translating and untranslatable fields are hidden.
        $paragraphs_type = $paragraphs_entity->getParagraphType();
        if ($paragraphs_type && \Drupal::currentUser()->hasPermission('edit behavior plugin settings') && (!$this->isTranslating || !$hide_untranslatable_fields)) {
          $element['behavior_plugins']['#weight'] = -99;
          foreach ($paragraphs_type->getEnabledBehaviorPlugins() as $plugin_id => $plugin) {
            $element['behavior_plugins'][$plugin_id] = [
              '#type' => 'container',
              '#group' => implode('][', array_merge($element_parents, ['paragraph_behavior'])),
            ];
            $subform_state = SubformState::createForSubform($element['behavior_plugins'][$plugin_id], $form, $form_state);
            if ($plugin_form = $plugin->buildBehaviorForm($paragraphs_entity, $element['behavior_plugins'][$plugin_id], $subform_state)) {
              $element['behavior_plugins'][$plugin_id] = $plugin_form;
              // Add the paragraphs-behavior class, so that we are able to show
              // and hide behavior fields, depending on the active perspective.
              $element['behavior_plugins'][$plugin_id]['#attributes']['class'][] = 'paragraphs-behavior';
            }
          }
        }
      }
      elseif ($item_mode == 'closed') {
        $element['subform'] = [];
        $element['behavior_plugins'] = [];
        if ($closed_mode_setting === 'preview') {
          // The closed paragraph is displayed as a rendered preview.
          $view_builder = $entity_type_manager->getViewBuilder('paragraph');

          $element['preview'] = $view_builder->view($paragraphs_entity, 'preview', $paragraphs_entity->language()->getId());
          $element['preview']['#access'] = $paragraphs_entity->access('view');
        }
        else {
          // The closed paragraph is displayed as a summary.
          if ($paragraphs_entity) {
            $summary = $paragraphs_entity->getSummaryItems();
            if (!empty($summary)) {
              $element['top']['summary']['fields_info'] = [
                '#theme' => 'paragraphs_summary',
                '#summary' => $summary,
                '#expanded' => FALSE,
                '#access' => $paragraphs_entity->access('update') || $paragraphs_entity->access('view'),
              ];
            }

            $info = array_merge($info, $paragraphs_entity->getIcons());
          }
        }
      }
      else {
        $element['subform'] = [];
      }

      // If we have any info items lets add them to the top section.
      if (count($info)) {
        foreach ($info as $info_item) {
          if (!isset($info_item['#access']) || $info_item['#access']) {
            $element['top']['icons']['items'] = $info;
            break;
          }
        }
      }

      $element['subform']['#attributes']['class'][] = 'paragraphs-subform';
      $element['subform']['#access'] = $paragraphs_entity->access('update');

      if ($item_mode == 'remove') {
        $element['#access'] = FALSE;
      }

      $widget_state['paragraphs'][$delta]['entity'] = $paragraphs_entity;
      $widget_state['paragraphs'][$delta]['display'] = $display;
      $widget_state['paragraphs'][$delta]['mode'] = $item_mode;
      $widget_state['closed_mode'] = $closed_mode_setting;
      $widget_state['autocollapse'] = $autocollapse_setting;
      $widget_state['autocollapse_default'] = $this->getSetting('autocollapse');

      static::setWidgetState($parents, $field_name, $form_state, $widget_state);

      if ($item_mode === 'edit') {
        $view_modes = \Drupal::service('entity_display.repository')->getFormModeOptionsByBundle($paragraphs_entity->getEntityTypeId(), $paragraphs_entity->bundle());
        if (count($view_modes) > 1) {
          $element['variant'] = [
            '#type' => 'select',
            '#title' => $this->t('Variant'),
            '#options' => $view_modes,
            '#default_value' => $display->getMode(),
            '#weight' => -0.0002,
            '#executes_submit_callback' => TRUE,
            '#submit' => [[$this, 'submitVariant']],
            '#ajax' => [
              'callback' => [$this, 'ajaxChangeVariantCallback'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
            ],
            '#limit_validation_errors' => [
              array_merge($parents, [$field_name, $delta, 'variant']),
            ],
          ];
          $element['variant_submit'] = [
            '#type' => 'submit',
            '#name' => str_replace('-', '_', $id_prefix) . '_variant_submit',
            '#value' => t('Change variant'),
            '#weight' => -0.0001,
            '#submit' => [[$this, 'submitVariant']],
            '#limit_validation_errors' => [
              array_merge($parents, [$field_name, $delta, 'variant']),
            ],
            '#attributes' => [
              'class' => ['js-hide'],
            ],
          ];
        }
      }
    }
    else {
      $element['#access'] = FALSE;
    }

    return $element;
  }

  /**
   * Ajax callback to update the widget after changing variant.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form portion to render.
   */
  public function ajaxChangeVariantCallback(array $form, FormStateInterface $form_state): array {
    $select = $form_state->getTriggeringElement();
    $element = NestedArray::getValue($form, array_slice($select['#array_parents'], 0, -2));

    $element['#prefix'] = '<div class="ajax-new-content">' . ($element['#prefix'] ?? '');
    $element['#suffix'] = ($element['#suffix'] ?? '') . '</div>';

    return $element;
  }

  /**
   * Submit handler for the change variant select.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitVariant(array $form, FormStateInterface $form_state): void {
    // The triggering element can be either the "Change variant" button or the
    // select itself.
    $triggering_element = $form_state->getTriggeringElement();
    // Retrieve the field item and select element.
    $item = NestedArray::getValue($form, array_slice($triggering_element['#array_parents'], 0, -1));
    $select = $item['variant'];

    $widget = NestedArray::getValue($form, array_slice($item['#array_parents'], 0, -1));
    $field_name = $this->fieldDefinition->getName();
    $parents = $widget['#field_parents'];
    $field_state = static::getWidgetState($parents, $field_name, $form_state);

    $delta = $item['#delta'];
    $paragraphs_entity = $field_state['paragraphs'][$delta]['entity'];
    $form_mode = $form_state->getValue($select['#parents']);
    $display = EntityFormDisplay::collectRenderDisplay($paragraphs_entity, $form_mode);
    $field_state['paragraphs'][$delta]['display'] = $display;

    static::setWidgetState($parents, $field_name, $form_state, $field_state);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state): array {
    $values = parent::massageFormValues($values, $form, $form_state);

    foreach ($values as $delta => $item) {
      if (isset($values[$delta]['entity']) && isset($item['variant'])) {
        // Save variants in the dedicated field.
        $values[$delta]['entity']->set('oe_paragraphs_variant', $item['variant'] ?? 'default');
      }
    }

    return $values;
  }

}
