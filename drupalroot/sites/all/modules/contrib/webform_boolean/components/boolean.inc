<?php

/**
 * @file
 * A boolean field for webform.
 */

/**
 * Implements _webform_defaults_component().
 */
function _webform_defaults_boolean() {
  return array(
    'name' => '',
    'mandatory' => FALSE,
    'value' => '',
    'extra' => array(
      'title_display' => 0,
      'description' => '',
      'on_value' => '',
      'off_value' => '',
      'field_prefix' => '',
      'field_suffix' => '',
      'private' => '',
    ),
  );
}

/**
 * Implements _webform_render_component().
 */
function _webform_render_boolean($component, $value = NULL) {
  $element = array(
    '#type' => 'checkbox',
    '#title' => _webform_filter_xss($component['name']),
    '#title_display' => $component['extra']['title_display'] ? $component['extra']['title_display'] : 'before',
    '#default_value' => _webform_filter_xss($component['value']),
    '#description' => _webform_filter_descriptions($component['extra']['description']),
    '#required' => $component['mandatory'],
    '#weight' => $component['weight'],
    '#field_prefix' => empty($component['extra']['field_prefix']) ? NULL : _webform_filter_xss($component['extra']['field_prefix']),
    '#field_suffix' => empty($component['extra']['field_suffix']) ? NULL : _webform_filter_xss($component['extra']['field_suffix']),
    '#theme_wrappers' => array('checkboxes', 'webform_element'),
    '#translatable' => array('title', 'description'),
  );
  if (isset($value)) {
    $element['#default_value'] = $value[0];
  }
  return $element;
}

/**
 * Implements _webform_edit_component().
 */
function _webform_edit_boolean($component) {
  $form = array();

  $form['extra'] = array(
    '#type' => 'group',
    '#title' => t('Field Settings'),
  );

  $form['extra']['on_value'] = array(
    '#type' => 'textfield',
    '#title' => t('On value'),
    '#default_value' => $component['extra']['on_value'],
    '#description' => t('If left empty, "1" will be used.'),
    '#size' => 60,
    '#maxlength' => 255,
    '#weight' => 1,
  );

  $form['extra']['off_value'] = array(
    '#type' => 'textfield',
    '#title' => t('Off value'),
    '#default_value' => $component['extra']['off_value'],
    '#description' => t('If left empty, "0" will be used.'),
    '#size' => 60,
    '#maxlength' => 255,
    '#weight' => 2,
  );

  return $form;
}

/**
 * Implements _webform_display_component().
 */
function _webform_display_boolean($component, $value, $format = 'html') {
  return array(
    '#title' => $component['name'],
    '#weight' => $component['weight'],
    '#theme' => 'webform_display_boolean',
    '#theme_wrappers' => $format == 'html' ? array('webform_element') : array('webform_element_text'),
    '#format' => $format,
    '#value' => (array) $value,
    '#translatable' => array('title'),
  );
}

/**
 * Implements _webform_theme_component().
 */
function _webform_theme_boolean() {
  return array(
    'webform_display_boolean' => array(
      'render element' => 'element',
      'path' => drupal_get_path('module', 'webform_boolean'),
      'file' => 'components/boolean.inc',
    ),
  );
}

/**
 * Implements theme_hook().
 */
function theme_webform_display_boolean($variables) {
  $component_extra = $variables['element']['#webform_component']['extra'];
  $value = (int) $variables['element']['#value'][0];

  return ($value) ? $component_extra['on_value'] : $component_extra['off_value'];
}
