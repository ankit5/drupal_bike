<?php

namespace Drupal\toolbar_link\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\link\LinkItemInterface;

/**
 * Form handler for the Example add and edit forms.
 */
class ToolbarLinkItemForm extends EntityForm {

  /**
   * Constructs an ExampleForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $link_item = $this->entity;

    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link text'),
      '#maxlength' => 50,
      '#default_value' => $link_item->text,
      '#description' => $this->t(""),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $link_item->id(),
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#disabled' => !$link_item->isNew(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
        'source' => ['text'],
      ],
      '#description' => t('A unique machine-readable name for this item. It must only contain lowercase letters, numbers, and underscores.'),
    ];

    if ($link_item->isNew()) {
      $form['id']['#field_prefix'] = 'toolbar_link_';
    }

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#maxlength' => 255,
      '#default_value' => $link_item->url,
      '#description' => $this->t(""),
      '#required' => TRUE,
      '#element_validate' => [['Drupal\toolbar_link\Entity\ToolbarLinkItem', 'validateUriElement']],
    ];

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => isset($link_item->enabled) ? $link_item->enabled : TRUE,
    ];

    $form['attributes'] = [
      '#type' => 'details',
      '#title' => $this->t('Attributes'),
      '#open' => TRUE, // Controls the HTML5 'open' attribute. Defaults to FALSE.
      '#tree' => TRUE,
    ];

    $form['attributes']['target'] = [
      '#type' => 'select',
      '#title' => $this->t('Target'),
      '#default_value' => isset($link_item->attributes['target']) ? $link_item->attributes['target']: NULL,
      '#options' => [
        '_self' => $this->t('Same window (_self)'),
        '_blank' => $this->t('New window (_blank)'),
      ],
      '#empty_value' => '',
    ];

    $form['attributes']['rel'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rel'),
      '#default_value' => isset($link_item->attributes['rel']) ? $link_item->attributes['rel']: '',
    ];

    $form['attributes']['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class'),
      '#default_value' => isset($link_item->attributes['class']) ? $link_item->attributes['class']: '',
      '#description' => '<p>' . $this->t('This is useful to set icon by specifying icon classes. These can be possible values to set an icon:') . '</p>'
        . '<ul>'
        .   '<li>toolbar-icon toolbar-icon-escape-admin</li>'
        .   '<li>toolbar-icon toolbar-icon-help</li>'
        .   '<li>toolbar-icon toolbar-icon-help-main</li>'
        .   '<li>toolbar-icon toolbar-icon-menu</li>'
        .   '<li>toolbar-icon toolbar-icon-system-admin-content</li>'
        .   '<li>toolbar-icon toolbar-icon-system-admin-structure</li>'
        .   '<li>toolbar-icon toolbar-icon-system-themes-page</li>'
        .   '<li>toolbar-icon toolbar-icon-entity-user-collection</li>'
        .   '<li>toolbar-icon toolbar-icon-system-modules-list</li>'
        .   '<li>toolbar-icon toolbar-icon-system-admin-config</li>'
        .   '<li>toolbar-icon toolbar-icon-system-admin-reports</li>'
        .   '<li>toolbar-icon toolbar-icon-user</li>'
        . '</ul>',
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#default_value' => isset($link_item->weight) ? $link_item->weight : 0,
    ];

    // You will need additional form elements for your custom properties.
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $link_item = $this->entity;

    if ($link_item->isNew()) {
      $link_item->id = 'toolbar_link_' . $link_item->id;
    }

    $status = $link_item->save();

    if ($status) {
      $this->messenger()->addMessage($this->t('Saved the %label Example.', [
        '%label' => $link_item->text,
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label Example was not saved.', [
        '%label' => $link_item->text,
      ]), MessengerInterface::TYPE_ERROR);
    }

    $form_state->setRedirect('entity.toolbar_link_item.collection');
  }

  /**
   * Helper function to check whether an Example configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('toolbar_link_item')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
