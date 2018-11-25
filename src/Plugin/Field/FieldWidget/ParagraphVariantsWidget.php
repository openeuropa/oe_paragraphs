<?php

declare(strict_types = 1);

namespace Drupal\oe_paragraphs\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Render\Element;
use Drupal\paragraphs\Plugin\Field\FieldWidget\InlineParagraphsWidget;

/**
 * Class ParagraphVariantsWidget
 *
 * @FieldWidget(
 *   id = "oe_paragraphs_variants",
 *   label = @Translation("Paragraphs with variants"),
 *   description = @Translation("A widget that allows to switch between variants."),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class ParagraphVariantsWidget extends InlineParagraphsWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $field_name = $this->fieldDefinition->getName();
    $parents = $element['#field_parents'];
    $info = [];

    /** @var \Drupal\paragraphs\ParagraphInterface $paragraphs_entity */
    $paragraphs_entity = NULL;
    $host = $items->getEntity();
    $widget_state = static::getWidgetState($parents, $field_name, $form_state);

    $entity_manager = \Drupal::entityTypeManager();
    $target_type = $this->getFieldSetting('target_type');

    $item_mode = isset($widget_state['paragraphs'][$delta]['mode']) ? $widget_state['paragraphs'][$delta]['mode'] : 'edit';
    $default_edit_mode = $this->getSetting('edit_mode');

    $show_must_be_saved_warning = !empty($widget_state['paragraphs'][$delta]['show_warning']);

    if (isset($widget_state['paragraphs'][$delta]['entity'])) {
      $paragraphs_entity = $widget_state['paragraphs'][$delta]['entity'];
    }
    elseif (isset($items[$delta]->entity)) {
      $paragraphs_entity = $items[$delta]->entity;

      // We don't have a widget state yet, get from selector settings.
      if (!isset($widget_state['paragraphs'][$delta]['mode'])) {

        if ($default_edit_mode == 'open') {
          $item_mode = 'edit';
        }
        elseif ($default_edit_mode == 'closed') {
          $item_mode = 'closed';
        }
        elseif ($default_edit_mode == 'preview') {
          $item_mode = 'preview';
        }
      }
    }
    elseif (isset($widget_state['selected_bundle'])) {

      $entity_type = $entity_manager->getDefinition($target_type);
      $bundle_key = $entity_type->getKey('bundle');

      $paragraphs_entity = $entity_manager->getStorage($target_type)->create(array(
        $bundle_key => $widget_state['selected_bundle'],
      ));
      $paragraphs_entity->setParentEntity($items->getEntity(), $field_name);

      $item_mode = 'edit';
    }

    if ($item_mode == 'collapsed') {
      $item_mode = $default_edit_mode;
    }

    if ($item_mode == 'closed') {
      // Validate closed paragraphs and expand if needed.
      // @todo Consider recursion.
      $violations = $paragraphs_entity->validate();
      $violations->filterByFieldAccess();
      if (count($violations) > 0) {
        $item_mode = 'edit';
        $messages = [];
        foreach ($violations as $violation) {
          $messages[] = $violation->getMessage();
        }
        $info['validation_error'] = array(
          '#type' => 'container',
          '#markup' => $this->t('@messages', ['@messages' => strip_tags(implode('\n', $messages))]),
          '#attributes' => ['class' => ['messages', 'messages--warning']],
        );
      }
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
        // Add translation if missing for the target language.
        if (!$paragraphs_entity->hasTranslation($langcode)) {
          // Get the selected translation of the paragraph entity.
          $entity_langcode = $paragraphs_entity->language()->getId();
          $source = $form_state->get(['content_translation', 'source']);
          $source_langcode = $source ? $source->getId() : $entity_langcode;
          // Make sure the source language version is used if available. It is a
          // valid scenario to have no paragraphs items in the source version of
          // the host and fetching the translation without this check could lead
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

      $element_parents = $parents;
      $element_parents[] = $field_name;
      $element_parents[] = $delta;
      $element_parents[] = 'subform';

      $id_prefix = implode('-', array_merge($parents, array($field_name, $delta)));
      $wrapper_id = Html::getUniqueId($id_prefix . '-item-wrapper');

      $element += array(
        '#type' => 'container',
        '#element_validate' => array(array($this, 'elementValidate')),
        '#paragraph_type' => $paragraphs_entity->bundle(),
        'subform' => array(
          '#type' => 'container',
          '#parents' => $element_parents,
        ),
      );

      $element['#prefix'] = '<div id="' . $wrapper_id . '">';
      $element['#suffix'] = '</div>';

      $item_bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($target_type);
      if (isset($item_bundles[$paragraphs_entity->bundle()])) {
        $bundle_info = $item_bundles[$paragraphs_entity->bundle()];

        $element['top'] = array(
          '#type' => 'container',
          '#weight' => -1000,
          '#attributes' => array(
            'class' => array(
              'paragraph-type-top',
            ),
          ),
        );

        $element['top']['paragraph_type_title'] = array(
          '#type' => 'container',
          '#weight' => 0,
          '#attributes' => array(
            'class' => array(
              'paragraph-type-title',
            ),
          ),
        );

        $element['top']['paragraph_type_title']['info'] = array(
          '#markup' => $bundle_info['label'],
        );

        $actions = array();
        $links = array();

        // Hide the button when translating.
        $button_access = $paragraphs_entity->access('delete') && !$this->isTranslating;
        if ($item_mode != 'remove') {
          $links['remove_button'] = [
            '#type' => 'submit',
            '#value' => $this->t('Remove'),
            '#name' => strtr($id_prefix, '-', '_') . '_remove',
            '#weight' => 501,
            '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
            '#limit_validation_errors' => [array_merge($parents, [$field_name, 'add_more'])],
            '#delta' => $delta,
            '#ajax' => [
              'callback' => [get_class($this), 'itemAjax'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
              'effect' => 'fade',
            ],
            '#access' => $button_access,
            '#prefix' => '<li class="remove">',
            '#suffix' => '</li>',
            '#paragraphs_mode' => 'remove',
          ];

        }

        if ($item_mode == 'edit') {

          if (isset($items[$delta]->entity) && ($default_edit_mode == 'preview' || $default_edit_mode == 'closed')) {
            $links['collapse_button'] = array(
              '#type' => 'submit',
              '#value' => $this->t('Collapse'),
              '#name' => strtr($id_prefix, '-', '_') . '_collapse',
              '#weight' => 499,
              '#submit' => array(array(get_class($this), 'paragraphsItemSubmit')),
              '#delta' => $delta,
              '#limit_validation_errors' => [array_merge($parents, [$field_name, 'add_more'])],
              '#ajax' => array(
                'callback' => array(get_class($this), 'itemAjax'),
                'wrapper' => $widget_state['ajax_wrapper_id'],
                'effect' => 'fade',
              ),
              '#access' => $paragraphs_entity->access('update'),
              '#prefix' => '<li class="collapse">',
              '#suffix' => '</li>',
              '#paragraphs_mode' => 'collapsed',
              '#paragraphs_show_warning' => TRUE,
            );
          }

          $info['edit_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to edit this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('update') && $paragraphs_entity->access('delete'),
          );

          $info['remove_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to remove this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('delete') && $paragraphs_entity->access('update'),
          );

          $info['edit_remove_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to edit or remove this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('update') && !$paragraphs_entity->access('delete'),
          );
        }
        elseif ($item_mode == 'preview' || $item_mode == 'closed') {
          $links['edit_button'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Edit'),
            '#name' => strtr($id_prefix, '-', '_') . '_edit',
            '#weight' => 500,
            '#submit' => array(array(get_class($this), 'paragraphsItemSubmit')),
            '#limit_validation_errors' => array(array_merge($parents, array($field_name, 'add_more'))),
            '#delta' => $delta,
            '#ajax' => array(
              'callback' => array(get_class($this), 'itemAjax'),
              'wrapper' => $widget_state['ajax_wrapper_id'],
              'effect' => 'fade',
            ),
            '#access' => $paragraphs_entity->access('update'),
            '#prefix' => '<li class="edit">',
            '#suffix' => '</li>',
            '#paragraphs_mode' => 'edit',
          );

          if ($show_must_be_saved_warning) {
            $info['must_be_saved_info'] = array(
              '#type' => 'container',
              '#markup' => $this->t('You have unsaved changes on this @title item.', array('@title' => $this->getSetting('title'))),
              '#attributes' => ['class' => ['messages', 'messages--warning']],
            );
          }

          $info['preview_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to view this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('view'),
          );

          $info['edit_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to edit this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('update') && $paragraphs_entity->access('delete'),
          );

          $info['remove_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to remove this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('delete') && $paragraphs_entity->access('update'),
          );

          $info['edit_remove_button_info'] = array(
            '#type' => 'container',
            '#markup' => $this->t('You are not allowed to edit or remove this @title.', array('@title' => $this->getSetting('title'))),
            '#attributes' => ['class' => ['messages', 'messages--warning']],
            '#access' => !$paragraphs_entity->access('update') && !$paragraphs_entity->access('delete'),
          );
        }
        elseif ($item_mode == 'remove') {

          $element['top']['paragraph_type_title']['info'] = [
            '#markup' => $this->t('Deleted @title: %type', ['@title' => $this->getSetting('title'), '%type' => $bundle_info['label']]),
          ];

          $links['confirm_remove_button'] = [
            '#type' => 'submit',
            '#value' => $this->t('Confirm removal'),
            '#name' => strtr($id_prefix, '-', '_') . '_confirm_remove',
            '#weight' => 503,
            '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
            '#limit_validation_errors' => [array_merge($parents, [$field_name, 'add_more'])],
            '#delta' => $delta,
            '#ajax' => [
              'callback' => [get_class($this), 'itemAjax'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
              'effect' => 'fade',
            ],
            '#prefix' => '<li class="confirm-remove">',
            '#suffix' => '</li>',
            '#paragraphs_mode' => 'removed',
          ];

          $links['restore_button'] = [
            '#type' => 'submit',
            '#value' => $this->t('Restore'),
            '#name' => strtr($id_prefix, '-', '_') . '_restore',
            '#weight' => 504,
            '#submit' => [[get_class($this), 'paragraphsItemSubmit']],
            '#limit_validation_errors' => [array_merge($parents, [$field_name, 'add_more'])],
            '#delta' => $delta,
            '#ajax' => [
              'callback' => [get_class($this), 'itemAjax'],
              'wrapper' => $widget_state['ajax_wrapper_id'],
              'effect' => 'fade',
            ],
            '#prefix' => '<li class="restore">',
            '#suffix' => '</li>',
            '#paragraphs_mode' => 'edit',
          ];
        }

        if (count($links)) {
          $show_links = 0;
          foreach($links as $link_item) {
            if (!isset($link_item['#access']) || $link_item['#access']) {
              $show_links++;
            }
          }

          if ($show_links > 0) {

            $element['top']['links'] = $links;
            if ($show_links > 1) {
              $element['top']['links']['#theme_wrappers'] = array('dropbutton_wrapper', 'paragraphs_dropbutton_wrapper');
              $element['top']['links']['prefix'] = array(
                '#markup' => '<ul class="dropbutton">',
                '#weight' => -999,
              );
              $element['top']['links']['suffix'] = array(
                '#markup' => '</li>',
                '#weight' => 999,
              );
            }
            else {
              $element['top']['links']['#theme_wrappers'] = array('paragraphs_dropbutton_wrapper');
              foreach($links as $key => $link_item) {
                unset($element['top']['links'][$key]['#prefix']);
                unset($element['top']['links'][$key]['#suffix']);
              }
            }
            $element['top']['links']['#weight'] = 2;
          }
        }

        if (count($info)) {
          $show_info = FALSE;
          foreach($info as $info_item) {
            if (!isset($info_item['#access']) || $info_item['#access']) {
              $show_info = TRUE;
              break;
            }
          }

          if ($show_info) {
            $element['info'] = $info;
            $element['info']['#weight'] = 998;
          }
        }

        if (count($actions)) {
          $show_actions = FALSE;
          foreach($actions as $action_item) {
            if (!isset($action_item['#access']) || $action_item['#access']) {
              $show_actions = TRUE;
              break;
            }
          }

          if ($show_actions) {
            $element['actions'] = $actions;
            $element['actions']['#type'] = 'actions';
            $element['actions']['#weight'] = 999;
          }
        }
      }

      $display = $widget_state['paragraphs'][$delta]['display'] ?? EntityFormDisplay::collectRenderDisplay($paragraphs_entity, $this->getSetting('form_display_mode'));

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
        $element['subform']['#pre_render'][] = 'field_group_form_pre_render';
      }

      if ($item_mode == 'edit') {
        $display->buildForm($paragraphs_entity, $element['subform'], $form_state);
        $hide_untranslatable_fields = $paragraphs_entity->isDefaultTranslationAffectedOnly();
        foreach (Element::children($element['subform']) as $field) {
          if ($paragraphs_entity->hasField($field)) {
            $field_definition = $paragraphs_entity->getFieldDefinition($field);
            $translatable = $paragraphs_entity->{$field}->getFieldDefinition()->isTranslatable();

            // Do a check if we have to add a class to the form element. We need
            // those classes (paragraphs-content and paragraphs-behavior) to show
            // and hide elements, depending of the active perspective.
            // We need them to filter out entity reference revisions fields that
            // reference paragraphs, cause otherwise we have problems with showing
            // and hiding the right fields in nested paragraphs.
            $is_paragraph_field = FALSE;
            if ($field_definition->getType() == 'entity_reference_revisions') {
              // Check if we are referencing paragraphs.
              if ($field_definition->getSetting('target_type') == 'paragraph') {
                $is_paragraph_field = TRUE;
              }
            }

            // Hide untranslatable fields when configured to do so except
            // paragraph fields.
            if (!$translatable && $this->isTranslating && !$is_paragraph_field) {
              if ($hide_untranslatable_fields) {
                $element['subform'][$field]['#access'] = FALSE;
              }
              else {
                $element['subform'][$field]['widget']['#after_build'][] = [
                  static::class,
                  'addTranslatabilityClue'
                ];
              }
            }
          }
        }
      }
      elseif ($item_mode == 'preview') {
        $element['subform'] = array();
        $element['behavior_plugins'] = [];
        $element['preview'] = entity_view($paragraphs_entity, 'preview', $paragraphs_entity->language()->getId());
        $element['preview']['#access'] = $paragraphs_entity->access('view');
      }
      elseif ($item_mode == 'closed') {
        $element['subform'] = array();
        $element['behavior_plugins'] = [];
        if ($paragraphs_entity) {
          $summary = $paragraphs_entity->getSummary();
          $element['top']['paragraph_summary']['fields_info'] = [
            '#markup' => $summary,
            '#prefix' => '<div class="paragraphs-collapsed-description">',
            '#suffix' => '</div>',
          ];
        }
      }
      else {
        $element['subform'] = array();
      }

      $element['subform']['#attributes']['class'][] = 'paragraphs-subform';
      $element['subform']['#access'] = $paragraphs_entity->access('update');

      if ($item_mode == 'removed') {
        $element['#access'] = FALSE;
      }

      $widget_state['paragraphs'][$delta]['entity'] = $paragraphs_entity;
      $widget_state['paragraphs'][$delta]['display'] = $display;
      $widget_state['paragraphs'][$delta]['mode'] = $item_mode;

      static::setWidgetState($parents, $field_name, $form_state, $widget_state);

      if ($item_mode === 'edit') {
        $view_modes = \Drupal::service('entity_display.repository')->getFormModeOptionsByBundle($paragraphs_entity->getEntityTypeId(), $paragraphs_entity->bundle());
        $element['variant'] = [
          '#type' => 'select',
          '#title' => $this->t('Variant'),
          '#options' => $view_modes,
          '#default_value' => $display->id(),
          '#weight' => -0.0001,
          '#executes_submit_callback' => TRUE,
          '#submit' => [[$this, 'submitVariant']],
          '#ajax' => [
            'callback' => [$this, 'ajaxChangeVariantCallback'],
            'wrapper' => $widget_state['ajax_wrapper_id'],
          ],
        ];
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

    $element['#prefix'] = '<div class="ajax-new-content">' . (isset($element['#prefix']) ? $element['#prefix'] : '');
    $element['#suffix'] = (isset($element['#suffix']) ? $element['#suffix'] : '') . '</div>';

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
    $select = $form_state->getTriggeringElement();

    $widget = NestedArray::getValue($form, array_slice($select['#array_parents'], 0, -2));
    $field_name = $this->fieldDefinition->getName();
    $parents = $widget['#field_parents'];
    $field_state = static::getWidgetState($parents, $field_name, $form_state);

    $element = NestedArray::getValue($form, array_slice($select['#array_parents'], 0, -1));
    $delta = $element['#delta'];
    $paragraphs_entity = $field_state['paragraphs'][$delta]['entity'];
    $form_mode = $form_state->getValue($select['#parents']);
    $display = EntityFormDisplay::collectRenderDisplay($paragraphs_entity, $form_mode);
    $field_state['paragraphs'][$delta]['display'] = $display;

    static::setWidgetState($parents, $field_name, $form_state, $field_state);
    $form_state->setRebuild();
  }

}
