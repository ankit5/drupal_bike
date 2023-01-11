<?php

namespace Drupal\user_module\Plugin\views\style;

use Drupal\rest\Plugin\views\style\Serializer;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "user_module_serializer",
 *   title = @Translation("Rest Serializer | MY Module"),
 *   help = @Translation("Extends existing serializer styles to provide additional view data."),
 *   display_types = {"data"}
 * )
 */
class BetterRestSerializer extends Serializer {

  /**
   * {@inheritdoc}
   */
  public function render() {
   /* $data['endpoint'] = [
      'path' => $this->view->getDisplay()->getPath(),
      'args' => $this->view->args,
      'requested' => $this->view->getUrl($this->view->args)->toString(),
    ];*/
    $data['pager'] = $this->getPagerDetails();
    //$data['exposed_filters'] = $this->getExposedHandlers('filter');
    //$data['exposed_sorts'] = $this->getExposedHandlers('sort');
    $data['rows'] = $this->getRows();

    // Get the content type configured in the display or fallback to the default.
    if ((empty($this->view->live_preview))) {
      $content_type = $this->displayHandler->getContentType();
    }
    else {
      $content_type = !empty($this->options['formats']) ? \reset($this->options['formats']) : 'json';
    }

    return $this->serializer->serialize($data, $content_type, ['views_style_plugin' => $this]);
  }

  /**
   * Get the search results and process facets.
   *
   * @see FacetsSerializer::render();
   *
   * @return array
   */
  protected function getRows() {
    $rows = [];

    // If the Data Entity row plugin is used, this will be an array of entities
    // which will pass through Serializer to one of the registered Normalizers,
    // which will transform it to arrays/scalars. If the Data field row plugin
    // is used, $rows will not contain objects and will pass directly to the
    // Encoder.
    foreach ($this->view->result as $row_index => $row) {
      // Keep track of the current rendered row, like every style plugin has to
      // do.
      // @see \Drupal\views\Plugin\views\style\StylePluginBase::renderFields
      $this->view->row_index = $row_index;
      $rows[] = $this->view->rowPlugin->render($row);
    }

    // Remove native row index which was throwing off something in the rendering
    // and avoid confusion. This is not relevant as the actually rows are being
    // stored and rendered elsewhere. Can ask Jonathan to help clarify if need
    // be.
    unset($this->view->row_index);

    return $rows;
  }

  /**
   * Get pager and page details.
   *
   * @link https://www.drupal.org/project/drupal/issues/2982729
   *
   * @return array
   */
  protected function getPagerDetails() {
    $details = ['active' => FALSE];

    $pager = $this->view->pager;

    if ($pager) {
      $class = get_class($pager);
      $total_pages = 0;

      if (!in_array($class, ['Drupal\views\Plugin\views\pager\None', 'Drupal\views\Plugin\views\pager\Some'])) {
        $total_pages = $pager->getPagerTotal();
      }

      $details = [
        'current_page' => $pager->getCurrentPage(),
        'total_items' => $pager->getTotalItems(),
        'items_per_page' => $pager->getItemsPerPage(),
        'total_pages' => $total_pages,
       // 'options' => $pager->usesOptions() ? $pager->options : FALSE,
      ];
    }

    return $details;
  }

  /**
   * Get exposed handler information and option values.
   *
   * @param string $type
   *   ID for a handler type.
   *
   * @return array
   */
  protected function getExposedHandlers($type) {
    $exposed = [];

    $handlers = $this->view->getHandlers($type);
    $exposed_input = $this->view->getExposedInput();

    foreach ($handlers as $id => $item) {
      if (!empty($item['exposed'])) {
        $info = [
          'id' => $id,
          'submitted_values' => !empty($exposed_input[$item['expose']['identifier']]) ? $exposed_input[$item['expose']['identifier']] : [],
        ];
        $info += $item['expose'];
        $exposed[] = $info;
      }
    }

    return $exposed;
  }

}