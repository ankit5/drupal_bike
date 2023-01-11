<?php

namespace Drupal\user_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;
use Drupal\views\Views;

/**
 * Class OrderController for order detail page.
 */
class OrderController extends ControllerBase {

  /**
   * Returns a page title.
   */
  public function getTitle($order_id) {

    $data = $this->orderDetail($order_id, 1);
    return 'Order Detail (' . $data->order_id_show . ')';
  }

  /**
   * Render data order detail page.
   */
  public static function orderDetail($order_id, $object_only = 0) {

    // Connect to external database.
    Database::setActiveConnection('external');

    // Run query on external database.
    $results = Database::getConnection()->query("SELECT cust_orders.product_details AS cust_orders_product_details,cust_orders.road_tax AS road_tax, cust_orders.visual_order_id AS cust_orders_visual_order_id, cust_orders.total_price AS cust_orders_total_price, cust_orders.token_amount AS cust_orders_token_amount, cust_orders.payment_mode_type AS cust_orders_payment_mode_type, cust_orders.payment_id AS cust_orders_payment_id, cust_orders.created_at AS cust_orders_created_at, cust_orders.accessories_details AS cust_orders_accessories_details, cust_orders.shipping_address AS cust_orders_shipping_address, cust_orders.billing_address AS cust_orders_billing_address, cust_orders.customer_details AS cust_orders_customer_details, cust_orders.store_id AS cust_orders_store_id, cust_orders.store_name AS cust_orders_store_name, cust_orders.store_address AS cust_orders_store_address, cust_orders.shipping_status AS cust_orders_shipping_status, cust_orders.order_id AS cust_orders_order_id, cust_orders.id as id, cust_orders.customer_id AS customer_id, cust_orders.dealer_details AS dealer_details, cust_orders.offer_details AS offer_details, cust_orders.order_redeem_point as order_redeem_point, cust_orders.conversion_rate as conversion_rate, cust_orders.cancel_description as cancel_description,cancel_reason as cancel_reason
FROM
{cust_orders} cust_orders
LEFT JOIN {customers} customers_cust_orders ON cust_orders.customer_id = customers_cust_orders.id
WHERE cust_orders.id = '" . $order_id . "'
ORDER BY cust_orders_created_at DESC NULLS LAST
LIMIT 1 OFFSET 0")->fetchObject();
    $json = html_entity_decode(($results->cust_orders_accessories_details));
    $json = json_decode($json, TRUE);
    $results->cust_orders_accessories_details = $json;
    $ex_showroom = $results->cust_orders_product_details;
    $ex_showroom = html_entity_decode(($ex_showroom));
    $ex_showroom = json_decode($ex_showroom, TRUE);
 /*   if(isset($results->dealer_details)){
    $road_tax_rate = $results->dealer_details;
    $road_tax_rate = html_entity_decode(($road_tax_rate));
    $road_tax_rate = json_decode($road_tax_rate, TRUE);
    $road_tax_rate = $road_tax_rate['road_tax_rate'];
  }else{
$road_tax_rate = 0;
  }*/

   // $road_tax = ($road_tax_rate / 100) * $ex_showroom['showroom_price'];
    
     if(isset($results->offer_details)){
    $offer = $results->offer_details;

    $offer = html_entity_decode(($offer));

    $offer = json_decode($offer, TRUE);

   $offer_discount = self::calculateOfferDiscount(
        $ex_showroom['showroom_price'],
        $offer['flat_percentage'],
        $offer['discount_amount'],
        $offer['discount_percentage']
      );
 }else{
  $offer_discount = 0;
 }
 $or_status = [];

 if($results->cust_orders_payment_mode_type == 'Finance'){
$or_status['Loan Applied'] = 'Loan Applied';
$or_status['Loan Approved'] = 'Loan Approved';
$or_status['Gold Loan'] = 'Gold Loan';
$or_status['Loan Disbursed'] = 'Loan Disbursed';
 }
$or_status['Bike Delivered'] = 'Bike Delivered';
//$or_status = json_encode($or_status);
$results->or_status = $or_status;
$results->offer_discount = $offer_discount;

$results->conversion_rate =(isset($results->conversion_rate))?$results->conversion_rate:0;

$results->reward_redeem = $results->order_redeem_point * $results->conversion_rate;




    $results->cust_orders_total_price = $results->cust_orders_total_price - $results->offer_discount - $results->reward_redeem;

    $results->order_id_show = ($results->cust_orders_visual_order_id) ? $results->cust_orders_visual_order_id : $results->cust_orders_order_id;
   // $results->road_tax = $road_tax;
   // $results->road_tax_rate = $road_tax_rate;
    $results->pending_amount = $results->cust_orders_total_price - $results->cust_orders_token_amount;
    $results->cust_orders_payment_id = str_replace("pay_", "", $results->cust_orders_payment_id);
   
    // Connect back to default database.
    Database::setActiveConnection();

    if ($object_only) {
      return $results;
    }
    else {

      return [
        '#theme' => 'order_detail',
        '#order_id' => $order_id,
        '#data' => $results,
        '#attached' => [
          'library' => [
            'user_module/user_module_order_css',
          ],
        ],
      ];
    }

  }

  public static function calculateOfferDiscount (
  $vehiclePrice,
  $flatPercentage,
  $amount,
  $percentage
)  {
  if ($flatPercentage == "Percentage" && $percentage) {
    $discount = ($vehiclePrice * $percentage) / 100;
    return $discount;
  }
  return $amount ? $amount : 0;
}

public function cencel_order($order_id) {

  return array(
 '#markup' => Markup::create('<div class="">
   <div class="review_content ">
      
         <div class="field_wrapper">
         <label>Share your concern</label>
        <textarea rows="4" cols="50" id="cancel_message">

</textarea>
         </div>
         <div class="button_wrapper">
         <input type="hidden" value="'.$order_id.'" id="order_id" />
         <button class="blue" id="cancel_order">Submit </button>
         </div>
           </div>'),

);
    
  }

public function help_status($help_status_id) {
$args = [$help_status_id];
  $view = Views::getView('remarks');
  if (is_object($view)) {
    $view->setArguments($args);
    $view->setDisplay('block_1');
    $view->preExecute();
    $view->execute();
     @$result = \Drupal::service('renderer')->render($view->render());
  }
 
  return array(
 '#markup' => Markup::create('<div class="">
   <div class="review_content ">
         <div class="field_wrapper">
          <select id="help_status_value" class="form-select">
          <option value="Resolved">Resolved</option>
          <option value="Not resolved">Not resolved</option>
          </select>
         </div>
         <div class="field_wrapper">
         <label>Remark</label>
        <textarea rows="4" cols="50" id="remark" class="form-textarea"></textarea>
         </div>

         <div class="field_wrapper">
         <input type="hidden" value="'.$help_status_id.'" id="help_status_id" />
         <input type="hidden" value="'.time().'" id="resolved_date" />
         <button class="button button--primary button--small" id="help_status"> Save </button>
         </div>
          '.$result.' </div>'),

);
    
  }

}
