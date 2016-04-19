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
    '#theme_wrappers' => array('checkbox', 'webform_element'),
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
 * Implements _webform_table_component().
 */
function _webform_table_boolean($component, $value) {
  return _webform_boolean_report_value($component, $value);
}

/**
 * Implements _webform_csv_headers_component().
 */
function _webform_csv_headers_boolean($component, $export_options) {
  $header = array();
  $header[0] = '';
  $header[1] = '';
  $header[2] = $component['name'];
  return $header;
}

/**
 * Implements _webform_csv_data_component().
 */
function _webform_csv_data_boolean($component, $export_options, $value) {
  return _webform_boolean_report_value($component, $value);
}

/**
 * Helper function for generating ON/OFF value based on given user value.
 * @param $component
 *   A Webform component array.
 * @param $value
 *   An array of information containing the submission result, directly
 *   correlating to the webform_submitted_data database schema.
 * @return
 *   An ON/OFF string value for being used in CSV export and table value.
 */
function _webform_boolean_report_value($component, $value) {
  $return = '';
  if(isset($value[0])) {

    // In case of missing on/off values (optional setting), let's use standard
    // numbers 1 and 0.
    $on_value = empty($component['extra']['on_value']) ? '1' : $component['extra']['on_value'];
    $off_value = empty($component['extra']['off_value']) ? '0' : $component['extra']['off_value'];

    $return = $value[0] ? $on_value : $off_value;

  }
  return $return;
}

/**
 * Implements theme_hook().
 */
function theme_webform_display_boolean($variables) {
  $component_extra = $variables['element']['#webform_component']['extra'];
  $value = (int) $variables['element']['#value'][0];

  return ($value) ? $component_extra['on_value'] : $component_extra['off_value'];
}
