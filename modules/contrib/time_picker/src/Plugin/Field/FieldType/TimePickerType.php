<?php

namespace Drupal\time_picker\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'time_picker' field type.
 *
 * @FieldType(
 *   category= @Translation("General"),
 *   id = "time_picker",
 *   label = @Translation("Time Picker"),
 *   description = @Translation("Time picker field"),
 *   default_widget = "time_picker_widget",
 *   default_formatter = "time_picker_formatter"
 * )
 */
class TimePickerType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'is_ascii' => FALSE,
      'case_sensitive' => FALSE,
      'time_picker_theme' => 'theme_default',
      'hour_format' => '12h',
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['time'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Time'))
      ->setRequired(FALSE);

    $properties['locate'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Locate'))
      ->setRequired(FALSE);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'time' => [
          'type' => 'varchar',
          'length' => 255,
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $values = [];
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];
    $elements['time_picker_theme'] = [
      '#type' => 'select',
      '#title' => $this->t('Time picker theme'),
      '#description' => $this->t('Choose theme for time picker.'),
      '#default_value' => $this->getSetting('time_picker_theme'),
      '#options' => [
        'theme_default' => $this->t('Default theme'),
        'theme_sky_blue' => $this->t('Sky blue'),
        'theme_iris_blue' => $this->t('Iris blue theme'),
        'theme_parrot_green' => $this->t('Parrot green theme'),
        'theme_dark_gray' => $this->t('Dark gray theme'),
      ],
    ];
    $elements['hour_format'] = [
      '#type' => 'select',
      '#title' => $this->t('Hours Format'),
      '#description' => $this->t('Select the hours format'),
      '#options' => [
        '12h' => $this->t('12 Hours'),
        '24h' => $this->t('24 Hours'),
      ],
      '#default_value' => $this->getSetting('hour_format'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $time = empty($this->get('time')->getValue());

    return $time;
  }

}
