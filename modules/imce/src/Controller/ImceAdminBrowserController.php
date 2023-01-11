<?php

namespace Drupal\imce\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Class ImceAdminBrowserController.
 */
class ImceAdminBrowserController extends ControllerBase {

  /**
   * Browser Page.
   *
   * @return string
   *   Return Hello string.
   */
  public function page() {
    $base_path = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
    $render['iframe'] = [
      '#type' => 'inline_template',
      '#template' => '<iframe class="imce-browser" src="{{ url }}"></iframe>',
      '#context' => [
        'url' => $base_path.'imce',
      ],
    ];
    $render['#attached']['library'][] = 'imce/drupal.imce.admin';
    return $render;
  }

}
