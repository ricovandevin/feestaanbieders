<?php
/*FORM ELEMENTS*/
function feestaanbieders_textfield($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  $extra = '';
  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';

    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }

  $output = '<span class="form-input-wrapper"><input' . drupal_attributes($element['#attributes']) . ' /></span>';

  return $output . $extra;
}

function feestaanbieders_password($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'password';
  element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  return '<span class="form-input-wrapper"><input' . drupal_attributes($element['#attributes']) . ' /></span>';
}

function feestaanbieders_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<span class="form-button-wrapper ' . $element['#id'] . '"><span class="btn_icon"></span><input' . drupal_attributes($element['#attributes']) . ' /></span>';
}

function feestaanbieders_form_mailchimp_lists_user_subscribe_form_2_alter(&$form, &$form_state, $form_id){
    unset($form['mailchimp_lists']['mailchimp_2']['title']);
    $form['mailchimp_lists']['mailchimp_2']['#prefix'] = $form['mailchimp_lists']['mailchimp_2']['#prefix'] . '<div class="block_header">Aanmelden <span>nieuwsbrief</span></div>';
}

function feestaanbieders_html_tag($variables){
    if(strpos($variables['element']['#value'], '_gaq') !== false){
        //cookieconsent
        $variables['element']['#attributes']['class'][] = 'cc-onconsent-analytics';
    }
    
    return theme_html_tag($variables);
}