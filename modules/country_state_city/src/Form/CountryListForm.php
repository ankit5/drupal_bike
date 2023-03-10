<?php

namespace Drupal\country_state_city\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the countrylist entity edit forms.
 *
 * @ingroup country_state_city
 */
class CountryListForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\country_state_city\Entity\Slider */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;
    $form['langcode'] = [
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->language()->getId(),
      '#languages' => Language::STATE_ALL,
      '#access' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.countrylist.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
