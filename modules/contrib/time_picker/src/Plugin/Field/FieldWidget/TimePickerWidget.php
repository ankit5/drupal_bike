<?php

namespace Drupal\time_picker\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'time_picker_widget' widget.
 *
 * @FieldWidget(
 *   id = "time_picker_widget",
 *   label = @Translation("Time picker"),
 *   field_types = {
 *     "time_picker"
 *   }
 * )
 */
class TimePickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [] + parent::defaultSettings();
  }

  /**
   * Gets the initial values for the widget.
   *
   * This is a replacement for the disabled default values functionality.
   *
   * @return array
   *   The initial values, keyed by property.
   */
  protected function getInitialValues() {
    $initial_values = [
      'time' => '',
    ];

    return $initial_values;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->time) ? $items[$delta]->time : '';
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += [
        '#type' => 'fieldset',
        '#attributes' => ['class' => ['time_fieldset']],
      ];
    }
    $element['time'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Time'),
      '#default_value' => $value,
      '#attributes' => ['class' => ['timepicker']],
      '#size' => 10,
      '#maxlength' => 10,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    $element['#attached']['library'][] = 'time_picker/time_picker';
    $element['#attached']['drupalSettings']['time_picker'] = ['hour_format' => $this->getFieldSetting('hour_format'), 'theme_color' => $this->getFieldSetting('time_picker_theme')];
    return $element;
  }

  /**
   * Validate the color text field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) === 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
    $hour_format = $this->getFieldSetting('hour_format');
    if ($hour_format === '12h') {
      if (!preg_match('/^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/', $value)) {
        $form_state->setError($element, $this->t('Please enter valid time formate.'));
      }
    }
    elseif ($hour_format === '24h') {
      if (!preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
        $form_state->setError($element, $this->t('Please enter valid time formate.'));
      }
    }
  }

}
