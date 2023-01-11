<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Class EditorderForm for edit order data.
 *
 * @package Drupal\user_module\Form
 */
class EditorderForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'editorder_form';
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
      $query = $conn->select('cust_orders', 'm')
        ->condition('id', $num)
        ->fields('m');
      $record = $query->execute()->fetchAssoc();
    }
    if (isset($typee) && $typee == 'sanctioned_loan_amount') {
      $form[$typee] = [
        '#'.$type.'' => 'textfield',
        '#attributes' => array(
        ' type' => 'number',
    ),
        '#title' => t('Sanctioned Loan Amount:'),
        '#required' => TRUE,
        '#default_value' => (isset($record[$typee]) && $num) ? $record[$typee] : '',
      ];
    }

    if (isset($typee) && $typee == 'loan_application_number') {
      $form[$typee] = [
        '#'.$type.'' => 'textfield',
        '#title' => t('Loan Application Number:'),
        '#default_value' => (isset($record[$typee]) && $num) ? $record[$typee] : '',
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
    $url = Url::fromUserInput('/admin/order', [], ['absolute' => 'true']);
    if (isset($num) && isset($typee)) {
      Database::setActiveConnection('external');

      $query = Database::getConnection();

      $query->update('cust_orders')
        ->fields($field)
        ->condition('id', $num)
        ->execute();
      drupal_set_message("succesfully updated");
      $response = new RedirectResponse($url->toString());
      $response->send();
    }
    
  }

}
