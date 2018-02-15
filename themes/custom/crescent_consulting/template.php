<?php

/**
 * @file
 * template.php
 *
 * Contains theme override functions and preprocess functions for the theme.
 */

define("CRESCENT_SERVICES_WS_NID", 4);
define("CRESCENT_CAREERS_WEBFORM_NID", 12);

/**
 * Implements hook_preprocess_html().
 */
function crescent_consulting_preprocess_html(&$vars) {
  $handheldhriendly = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'HandheldFriendly',
      'content' => 'false',
    ),
  );
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'user-scalable=yes, width=device-width',
    ),
  );
  // Setup IE meta tag to force IE rendering mode.
  $meta_ie_render_engine = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge',
    ),
    '#weight' => '-99999',
    '#prefix' => '<!--[if IE]>',
    '#suffix' => '<![endif]-->',
  );

  drupal_add_html_head($handheldhriendly, 'handheldhriendly');
  drupal_add_html_head($viewport, 'viewport');
  drupal_add_html_head($meta_ie_render_engine, 'meta_ie_render_engine');

  $vars['classes_array'][] = 'page';
  if ($node = menu_get_object()) {
    switch ($node->type) {
      case 'home':
        $vars['classes_array'][] = 'page-home';
        break;
      case 'water_services':
        $vars['classes_array'][] = 'page-services';
        break;
      case 'consultants':
        $vars['classes_array'][] = 'page-consultants';
        break;
    }
  }
}

/**
 * Implements hook_preprocess_page().
 */
function crescent_consulting_preprocess_page(&$vars) {
  $block = module_invoke('crescent_base', 'block_view', 'crescent_login_btn');
  $vars['enviroedge_button'] = render($block['content']);


  if ($node = menu_get_object()) {
    switch ($node->type) {
      case 'home':
        break;
    }
    $top_fields = array(
      'field_common_top_image',
    );
    $top_fields_value = _crescent_consulting_get_rows_from_node($node, $top_fields);
    if (isset($top_fields_value) && !empty($top_fields_value)) {
      $top_image = isset($top_fields_value['field_common_top_image']) ? $top_fields_value['field_common_top_image'] : '';

      if ($top_image) {
    $vars['top_image'] = file_create_url($node->field_common_top_image[LANGUAGE_NONE][0]['uri']);
      }
    }
  }

  $vars['footer_text'] = variable_get('crescent_base_settings_footer', '');
}

/**
 * Implements hook_preprocess_node().
 */
function crescent_consulting_preprocess_node(&$vars) {
  $node = $vars['node'];
//  kpr($node->type);
  if (!$vars['page']) {
    $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'];
  }

  if (isset($vars['content']['field_common_top_image'])) {
    $vars['content']['field_common_top_image']['#access'] = FALSE;
  }

  switch ($node->type) {
    case 'ws_item':
      if (isset($vars['view_mode']) && $vars['view_mode'] == 'full') {
        drupal_goto("node/" . CRESCENT_SERVICES_WS_NID);
      }

      if (isset($vars['content']['field_ws_item_gallery']) && !empty($vars['content']['field_ws_item_gallery'])) {
        $vars['add_class'] = 'text-slider';
      }
      break;
    case 'careers':
    case 'consultant_solutions':
    case 'consultants':
      $vars['careers_webform_link'] = l(t('Submit Resume'), 'node/' . CRESCENT_CAREERS_WEBFORM_NID, array('attributes' => array('class' => 'btn')));
      break;
    case 'solutions':
      try {
        $node_wrapper = entity_metadata_wrapper('node', $node);
        if ($node_wrapper->__isset('field_resume_link') && $node_wrapper->field_resume_link->value() !== NULL) {
          $vars['careers_webform_link'] = l(t('Submit Resume'), 'node/' . CRESCENT_CAREERS_WEBFORM_NID, array('attributes' => array('class' => 'btn')));
        }
      }
      catch (EntityMetadataWrapperException $exc) {
        watchdog(
          'crescent_consulting',
          'See ' . __FUNCTION__ . '() <pre>' . $exc->getTraceAsString() . '</pre>',
          NULL, WATCHDOG_ERROR
        );
      }
      break;
    case 'water_services':
      $tab = (isset($node->field_ws_tabs) && !empty($node->field_ws_tabs[LANGUAGE_NONE][0]['moddelta'])) ?
        $node->field_ws_tabs[LANGUAGE_NONE][0]['moddelta'] : '';
      if ($tab) {
        $tab = str_replace("quicktabs:", "", $tab);
        $vars['classes_array'][] = 'node-' . $tab;
      }
      break;

  }
}

/**
 * Implements hook_preprocess_field().
 */
function crescent_consulting_preprocess_field(&$vars, $hook) {
  $element = $vars['element'];
  switch ($element['#field_name']) {
    case 'field_solutions_list':
      $parent = !empty($vars['element']['#object']) ? $vars['element']['#object']
        : '';
      if ($parent) {
        $list_type = _crescent_consulting_get_rows_from_node($parent, array('field_solutions_list_type'));
        if (!empty($list_type['field_solutions_list_type'])) {
          switch ($list_type['field_solutions_list_type']) {
            case 'one_column':
              $vars['classes_array'][] = 'style-b';
              break;
            case 'two_columns':
              $vars['classes_array'][] = 'style-a';
              break;
          }
        }
      }
      break;
  }
}

/**
 * Implements hook_quicktabs_alter().
 */
function crescent_consulting_quicktabs_alter($info) {
  $param_name = isset($info->machine_name) ? $info->machine_name : '';
  $parameter = isset($_GET['qt']) ? $_GET['qt'] : '';
  if (is_numeric($parameter) && $param_name) {
    $_GET['qt-' . $param_name] = $parameter;
    unset($_GET['qt']);
  }
}

/* main ul */
function crescent_consulting_menu_tree__main_menu($variables) {
  return '<ul class="menu">' . $variables['tree'] . '</ul>';
}

/* inner ul */
function crescent_consulting_menu_tree__main_menu_inner($variables) {
  return '<div class="sublevel"><ul class="menu">' . $variables['tree'] . '</ul></div>';
}

/* inner li */
function crescent_consulting_menu_link__main_menu_inner($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $element['#below']['#theme_wrappers'][0] = 'menu_tree__main_menu_inner';  // 3 level
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/* main li */
function crescent_consulting_menu_link__main_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    foreach ($element['#below'] as $key => $val) {
      if (is_numeric($key)) {
        $element['#below'][$key]['#theme'] = 'menu_link__main_menu_inner'; // 2 level
      }
    }
    $element['#below']['#theme_wrappers'][0] = 'menu_tree__main_menu_inner';  // 2 level
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function crescent_consulting_menu_link__menu_services(array $variables) {
  $element = $variables['element'];

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return ' <li class="el-with-animation animate-left-to-right animate-opacity' . drupal_attributes($element['#attributes']) . '>' . $output . "</li>\n";
}


/**
 * Get rows from node.
 *
 * @param $node
 * @param $field_array
 * @return array|void
 */
function _crescent_consulting_get_rows_from_node($node, $field_array) {

  if (!is_object($node)) {
    return;
  }

  try {
    $node_wrapper = entity_metadata_wrapper('node', $node);
    $properties = $node_wrapper->getPropertyInfo();
    $rows = array();

    foreach ($field_array as $field) {
      if (array_key_exists($field, $properties)) {
        $rows[$field] = $node_wrapper->$field->value();
      }
    }
  }
  catch (EntityMetadataWrapperException $exc) {
    watchdog('oai', 'See ' . __FUNCTION__ . '() <pre>' . $exc->getTraceAsString() . '</pre>', NULL, WATCHDOG_ERROR);
  }

  return $rows;
}

/**
 * Implements hook_preprocess_qt_quicktabs().
 */
function crescent_consulting_preprocess_qt_quicktabs(&$vars) {

  $tab = !empty($vars['element']['#options']['attributes']['id']) ?
    $vars['element']['#options']['attributes']['id'] : '';
  if ($tab) {
    $tab = str_replace("quicktabs-", "", $tab);
  }

  switch ($tab) {
    case 'solutions':
      $vars['element']['tabs']['#theme'] = 'crescent_base_qt_quicktabs_tabset';
      break;
  }

  $tab_links = !empty($vars['element']['tabs']['tablinks']) ?
    $vars['element']['tabs']['tablinks'] : array();

  foreach($tab_links as $key => $link) {
    $vars['element']['tabs']['tablinks'][$key]['#options']['html'] = TRUE;
  }
}
