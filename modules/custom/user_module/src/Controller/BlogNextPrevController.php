<?php

namespace Drupal\user_module\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\views\Views;

class BlogNextPrevController extends ControllerBase {


   public function review_average($uuid,$node_type) {

        //return the response
    if($uuid && $node_type=='node--product_main'){
$view = Views::getView('product_rate_api'); 
$view->setDisplay('rest_export_3');
$view->setExposedInput(['uuid' => $uuid]);
$view->execute();
@$rendered = \Drupal::service('renderer')->render($view->render());
$json = $rendered->jsonSerialize();
$json_data = json_decode($json);

//  $json_data = json_decode($json);
     // $arr = explode(' ', trim($json_data[0]->field_product_rate));

      $average_rating = round($json_data[0]->field_product_rate, 1);

      // Set value for field.
      $node = Node::load($json_data[0]->nid);
      $node->field_rating_average->value = $average_rating;
      $node->save();
      $res['avg']=$node->field_rating_average->value;
        return new JsonResponse($res);
      }
if($uuid && $node_type=='node--store'){
$view = Views::getView('stores_api'); 
$view->setDisplay('rest_export_3');
$view->setExposedInput(['uuid' => $uuid]);
$view->execute();
@$rendered = \Drupal::service('renderer')->render($view->render());
$json = $rendered->jsonSerialize();
$json_data = json_decode($json);

//  $json_data = json_decode($json);
     // $arr = explode(' ', trim($json_data[0]->field_product_rate));

      $average_rating = round($json_data[0]->field_store_rate, 1);

      // Set value for field.
      $node = Node::load($json_data[0]->nid);
      $node->field_rating_average->value = $average_rating;
      $node->save();
      $res['avg']=$node->field_rating_average->value;
        return new JsonResponse($res);
      }


    }

    public function getData($blog_uuid) {

        //return the response
        $res['data']['prev'] = $this->generateNextPrevious($blog_uuid, 'prev');
        $res['data']['next'] = $this->generateNextPrevious($blog_uuid, 'next');
        return new JsonResponse($res);
    }

private function generateNextPrevious($uuid, $direction) {
    $node = \Drupal::service('entity.repository')->loadEntityByUuid('node', $uuid);
     if(empty($node)){
     return false;
 }

    $comparison_opperator = '>';
    $sort = 'ASC';
    $current_nid = $node->id();
    
    $current_langcode = $node->get('langcode')->value;

    if ($direction === 'prev') {
      $comparison_opperator = '<';
      $sort = 'DESC';
    }

    // Lookup 1 node younger (or older) than the current node.
    $database = \Drupal::database();
    $results = $database->query("SELECT n.nid as nid, n.uuid as uuid, nfd.title as title 
FROM
node n
LEFT JOIN node_field_data nfd ON n.nid = nfd.nid
where nfd.type = 'blog' and nfd.status = 1 and nfd.nid $comparison_opperator $current_nid 
ORDER BY nid $sort
LIMIT 1 OFFSET 0")->fetchObject();

    return $results;
  }


  public function get_city($state) {
    
       
  $database = \Drupal::database();
    $query = $database->query("SELECT node__field_message.field_message_value AS node__field_message_field_message_value, node_field_data.nid AS nid, node_field_data.title AS title
FROM
{node_field_data} node_field_data
LEFT JOIN {node__field_message_type} node__field_message_type_value_0 ON node_field_data.nid = node__field_message_type_value_0.entity_id AND node__field_message_type_value_0.field_message_type_value = 'city'
INNER JOIN {node__field_state} node__field_state ON node_field_data.nid = node__field_state.entity_id AND node__field_state.deleted = '0' AND (node__field_state.langcode = node_field_data.langcode OR node__field_state.bundle = 'store')
LEFT JOIN {node__field_message} node__field_message ON node_field_data.nid = node__field_message.entity_id AND node__field_message.deleted = '0'
WHERE ((node__field_state.field_state_value = '$state')) AND ((node_field_data.type IN ('messages')) AND (node__field_message_type_value_0.field_message_type_value = 'city') AND (node_field_data.status = '1'))
ORDER BY node__field_message_field_message_value DESC");

    $result = $query;
  $options_r = [];
  //$options_r['All'] = '-Any-2';
if($result){
  while ($row = $result->fetchObject()) {
    $options_r[$row->title] = $row->node__field_message_field_message_value;
  }
}

 return new JsonResponse($options_r);
 // return ;
}


}