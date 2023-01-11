<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Class EditcustomerForm for edit customer data.
 *
 * @package Drupal\user_module\Form
 */
class EditcustomerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'editcustomer_form';
  }



  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    Database::setActiveConnection('external');
    $conn = Database::getConnection();
    $record = [];
    $num = $_GET['num'];
    $typee = $_GET['type'];
    $type = 'type';
    if (isset($num)) {
      $query = $conn->select('user_logins', 'm')
        ->condition('id', $num)
        ->fields('m');
      $record = $query->execute()->fetchAssoc();
    }
    if (isset($typee) && $typee == 'email') {
      $form[$typee] = [
        '#'.$type.'' => $typee,
        '#title' => t('Email ID:'),
        '#required' => TRUE,
        '#default_value' => (isset($record[$typee]) && $num) ? $record[$typee] : '',
      ];
    }
    if (isset($typee) && $typee == 'block_status') {
      $form[$typee] = [
        '#'.$type.''          => 'checkbox',
        '#title'         => t('Block Status'),
        '#default_value' => (isset($record[$typee]) && $num) ? $record[$typee] : '',
        '#description' => t('Admin needs to Uncheck if he wants to Block Customer.'),
      ];
    }

    $form['submit'] = [
      '#'.$type.'' => 'submit',
      '#value' => 'save',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $num = $_GET['num'];
    $typee = $_GET['type'];
    if (isset($num) && isset($typee)) {
      $field = [
        $typee => $field[$typee],

      ];
    }
     $url = Url::fromUserInput('/admin/customer', [], ['absolute' => 'true']);
    if (isset($num) && isset($typee)) {
      Database::setActiveConnection('external');

      $query = Database::getConnection();

      $query->update('user_logins')
        ->fields($field)
        ->condition('id', $num)
        ->execute();
      drupal_set_message("succesfully updated");
      $response = new RedirectResponse($url->toString());
      $response->send();
    }
    
  }

}
