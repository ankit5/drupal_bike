<?php

namespace Drupal\country_state_city\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\country_state_city\Entity\StateList;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * State SettingsForm for importing state data.
 */
class StateSettingsForm extends ConfigFormBase {

  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * Constructs a MyClass object.
   *
   * @param \drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   This is for config storage.
   * @param \drupal\Core\Messenger\MessengerInterface $messenger
   *   This is for message send.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MessengerInterface $messenger, TranslationInterface $string_translation) {
    parent::__construct($config_factory, $messenger, $string_translation);
    // You can skip injecting this service, the trait will fallback to
    // \Drupal::translation() but it is recommended to do so, for easier
    // testability.
    $this->messenger = $messenger;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'), $container->get('messenger'), $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'country_state_city.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'state_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $config = $this->config('country_state_city.settings');.
    $form['import'] = [
      '#title' => $this->t('State data'),
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#description' => $this->t('Import state data.'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $batch = [
      'title' => $this->t('Exporting'),
      'operations' => [
        ['Drupal\country_state_city\Form\StateSettingsForm::importState', []],
      ],
      'finished' => 'importStateDataFinish',
    ];

    batch_set($batch);

    $this->config('country_state_city.settings')
      ->set('import', $form_state->getValue('import'))
      ->save();
  }

  /**
   * Undocumented function.
   *
   * @param array $context
   *   The context param.
   */
  public static function importState(array &$context) {

    $module_path = drupal_get_path('module', 'country_state_city');

    // Importando os dados dos paises.
    $states = json_decode(file_get_contents($module_path . '/data/states.json'));

    if (empty($context['sandbox'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['current_id'] = 0;
      $context['sandbox']['max'] = count($states);
    }

    // Criando um registro na entidade country para cada pais importado.
    foreach ($states as $state) {
      $new_state = StateList::create([
        'id' => $state->id,
        'name' => $state->name,
        'country_id' => $state->country_id,
      ]);
      $new_state->save();

      $context['sandbox']['progress']++;
      $context['sandbox']['current_id'] = $state->id;
    }

    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }

  /**
   * Undocumented function.
   *
   * @param bool $success
   *   The success param.
   * @param array $results
   *   The results param.
   * @param array $operations
   *   The operations param.
   */
  public function importStateDataFinish(bool $success, array $results, array $operations) {

    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = $this->translation()
        ->formatPlural(count($results), 'One post processed.', '@count posts processed.');
    }
    else {
      $message = $this->t('Finished with an error.');
    }
    $this->messenger()->addMessage($message);
  }

}
