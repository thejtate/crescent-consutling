<?php


/**
 * @file
 * Settings forms
 */

/**
 * Create settings form in drupal configuration settings section for module.
 */
function crescent_base_admin_settings() {
  // Links to social sites
  $form['crescent_base_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Crescent Consulting Settings'),
    '#collapsible' => TRUE,
  );

  $form['crescent_base_settings']['crescent_base_settings_footer'] = array(
    '#type' => 'textfield',
    '#size' => 50,
    '#title' => t('Footer'),
    '#default_value' => variable_get('crescent_base_settings_footer', ""),
  );

  return system_settings_form($form);
}
