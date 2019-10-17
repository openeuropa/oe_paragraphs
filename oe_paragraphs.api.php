<?php

/**
 * @file
 * Hooks for the OE Paragraphs module.
 */

declare(strict_types = 1);

/**
 * Alters the allowed values for "Content row: Column layout".
 *
 * @param array $allowed_values
 *   Array of possible key and value options.
 *
 * @see _oe_paragraphs_allowed_values_content_row_column_layout()
 */
function hook_oe_paragraphs_allowed_values_content_row_column_layout_alter(array &$allowed_values) {
  $allowed_values = [
    '2' => t('2 columns (equal width | 6-6)'),
    '6-3-3' => t('3 columns (non-equal width | 6-3-3)'),
    '4' => t('4 columns (equal width | 3-3-3-3)'),
  ];
}
