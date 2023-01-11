<?php

namespace Drupal\toolbar_link\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Example.
 */
class ToolbarLinkItemListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['text'] = $this->t('Link text');
    $header['url'] = $this->t('Link url');
    $header['id'] = $this->t('Machine name');
    $header['enabled'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['text'] = $entity->text;
    $row['url'] = $entity->url;
    $row['id'] = $entity->id();
    $row['enabled'] = $entity->enabled ? $this->t('Enabled') : $this->t('Disabled');

    // You probably want a few more properties here...

    return $row + parent::buildRow($entity);
  }

}
