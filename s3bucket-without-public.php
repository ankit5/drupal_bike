<?php
define('AWS_KEY', 'AKIAW2ZBEV7BG73JRWJN');
define('AWS_SECRET_KEY', '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');

require __DIR__.'/vendor/autoload.php';
  
use Aws\S3\S3Client;

// Instantiate an Amazon S3 client.
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'ap-south-1'
    /*'credentials' => [
        'key' => AWS_KEY,
        'secret' => AWS_SECRET_KEY
    ]*/
]);
  
  
$bucket = 'motodesh-prod-s3';
$file_Path = __DIR__ . '/README.txt';
$key = basename($file_Path);
  
// Upload a publicly accessible file. The file size and type are determined by the SDK.
try {
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'Body'   => fopen($file_Path, 'r'),
        'ACL'    => 'public-read', // make file 'public'
    ]);
    echo "Image uploaded successfully. Image path is: ". $result->get('ObjectURL');
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e->getMessage();
}