<?php

namespace Drupal\user_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

/**
 * Below section is important.
 *
 * @Block(
 *  id = "dashboard_custom_block",
 *  admin_label = @Translation("Dashboard custom"),
 *  category = @Translation("Dashboard custom")
 * )
 */
class DashboardBlock extends BlockBase {

  /**
   * Build Block.
   */
  public function build() {
    $output = [];
    $output['#markup'] = $this->renderhtmldash();
    $output['#cache'] = [
      'max-age' => 0,
    ];
    $output['#attached']['library'][] = 'user_module/user_module_css';
    $output['#attached']['library'][] = 'user_module/user_module_external';

    return $output;
  }

  /**
   * Render block data.
   */
  private function renderhtmldash() {
    return '<div class="row">      
<div class="col-md-12 col-xl-4">
                                               
                                                <div class="card table-card">
                                                    <div class="">
                                                        <div class="row-table">
                                                            <div class="col-sm-6 card-block-big br">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <i class="fa fa-product-hunt" aria-hidden="true"></i>
                                                                    </div>
                                                                    <div class="col-sm-8 text-center">
                                                                        <h5>' . $this->getviewsCount('product_models_relations') . '</h5>
                                                                        <span>Products</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 card-block-big">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                       <i class="fa fa-motorcycle text-danger" aria-hidden="true"></i> 
                                                                    </div>
                                                                    <div class="col-sm-8 text-center">
                                                                        <h5>' . $this->getviewsCount('product_variant_list') . '</h5>
                                                                        <span>Product Variants</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="row-table">
                                                            <div class="col-sm-6 card-block-big br">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <i class="icofont icofont-files text-info"></i>
                                                                    </div>
                                                                    <div class="col-sm-8 text-center">
                                                                        <h5>' . $this->getviewsCount('accessories') . '</h5>
                                                                        <span>Accessories</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 card-block-big">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <i class="icofont icofont-envelope-open text-warning"></i>
                                                                    </div>
                                                                    <div class="col-sm-8 text-center">
                                                                        <h5>' . $this->getviewsCount('popular_compression') . '</h5>
                                                                        <span>Popular Compression</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                            </div>
</div>';
  }

  /**
   * Get count of views row.
   */
  public function getviewsCount($name) {

    $view = Views::getView($name);
    $view->build('page_1');
    $view->execute('page_1');

    return $view->total_rows;
  }

}
