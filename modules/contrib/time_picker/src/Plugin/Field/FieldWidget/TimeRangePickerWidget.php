<?php

namespace Drupal\time_picker\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Plugin implementation of the 'time_range_picker_widget' widget.
 *
 * @FieldWidget(
 *   id = "time_range_picker_widget",
 *   label = @Translation("Time range picker"),
 *   field_types = {
 *     "time_range_picker"
 *   }
 * )
 */
class TimeRangePickerWidget extends WidgetBase {

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
      'start' => '',
      'end' => '',
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
    $start = isset($items[$delta]->start) ? $items[$delta]->start : '';
    $end = isset($items[$delta]->end) ? $items[$delta]->end : '';
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += [
        '#type' => 'fieldset',
        '#attributes' => ['class' => ['time_range_fieldset']],
      ];
    }
    $element['start'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Start time'),
      '#default_value' => $start,
      '#attributes' => ['class' => ['time_range_picker']],
      '#size' => 10,
      '#maxlength' => 10,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    $element['end'] = [
      '#type' => 'textfield',
      '#title' => $this->t('End time'),
      '#default_value' => $end,
      '#attributes' => ['class' => ['time_range_picker']],
      '#size' => 10,
      '#maxlength' => 10,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    $element['#element_validate'][] = [$this, 'validateStartEnd'];
    $element['#attached']['library'][] = 'time_picker/time_picker';
    $element['#attached']['drupalSettings']['time_range_picker'] = ['hour_format' => $this->getFieldSetting('hour_format'), 'theme_color' => $this->getFieldSetting('time_picker_theme')];
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

  /**
   * Element_validate callback to ensure that the start date <= the end date.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   generic form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   */
  public function validateStartEnd(array &$element, FormStateInterface $form_state, array &$complete_form) {
    $start_time = $element['start']['#value'];
    $end_time = $element['end']['#value'];
    if (!empty($start_time) && !empty($end_time)) {
      $tz = date_default_timezone_get();
      $start = new DrupalDateTime($start_time, $tz);
      $end = new DrupalDateTime($end_time, $tz);
      if ($start > $end) {
        $form_state->setError($element, $this->t('Please enter valid time formate : End time greater than Start time.'));
      }
    }
  }

}
