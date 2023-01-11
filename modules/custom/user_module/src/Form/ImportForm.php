<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\File\FileSystemInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a form for deleting a batch_import_example entity.
 *
 * @package Drupal\user_module\Form
 */
class ImportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return 'batch_import_example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#prefix'] = '<p>This example form will import Variant Product Highlight</p>';

    $form['actions'] = array(
      '#type' => 'actions',
      'submit' => array(
        '#type' => 'submit',
        '#value' => 'Proceed',
      ),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

$batch = [
    'title' => t('Importing Highlight'),
    'operations' => [],
    'init_message' => t('Import process is starting.'),
    'progress_message' => t('Processed @current out of @total. Estimated time: @estimate.'),
    'error_message' => t('The process has encountered an error.'),
    ];

     $pp = NULL;
    // Count row number.
    $row = 0;
    // Add you row number for skip
    // hear we pass 1st row for skip in csv.
    $file_path = 'https://motokart-uat.s3.ap-south-1.amazonaws.com/cms/s3fs-public/motokaart/highlight_0.csv';
    $skip_row_number = ["1"];
    $file = fopen($file_path, "r");
    $customer = NULL;
    $specifications_name = '';
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
      $row++;
      $num = count($data);
      // Check row for skip row.
      if (in_array($row, $skip_row_number)) {
        continue;
        // Skip row of csv.
      }
      else {

// Color field data.
        if ($data[4] != '') {
         
          $color_image = $data[4];
         $http = \Drupal::httpClient();
$options = array();
$result = $http->request('get', $color_image, $options);
//$body_class = $result->getBody(); //The class
$body_data = $result->getBody()->getContents();
         

          $image_url = $data[4];
          $file3 = file_save_data($body_data, "s3://2021-11/".drupal_basename($image_url), FileSystemInterface::EXISTS_RENAME);

          $paragraph = Paragraph::create([
            'type' => 'highlight',
            'field_title' => [
              "value"  => trim($data[2]),
            ],
            'field_description' => [
              "value"  => trim($data[3]),
            ],
            'field_image' => [
              'target_id' => $file3->id(),
              'alt' => '',
              'title' => '',
            ],
          ]);
          $paragraph->save();

          $field_product_higlight[] = [
            'target_id' => $paragraph->id(),
            'target_revision_id' => $paragraph->getRevisionId(),
          ];

        }
        //

    
if($data[0]!=''){
    $batch['operations'][] = [['\Drupal\user_module\Form\ImportForm', 'importHighlight'], [$field_product_higlight,$data[0]]];
    unset($field_product_higlight);
  }
     
    

  }

}

    batch_set($batch);
    \Drupal::messenger()->addMessage('Imported '.$row.' Highlight!');

    $form_state->setRebuild(TRUE);
  }

  /**
   * @param $entity
   * Deletes an entity
   */
  public function importHighlight($field_product_higlight,$nid, &$context) {
             if (isset($field_product_higlight[0])) {
               $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $node->field_product_higlight = $field_product_higlight;
      $node->save();
    }
    $context['results'][] = $nid;
    $context['message'] = t('Created @title', array('@title' => $nid));
  }

}