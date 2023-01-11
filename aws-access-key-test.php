<?php
print __DIR__;
use Aws\S3\S3Client;


define('AWS_KEY', 'AKIAW2ZBEV7BG73JRWJN');
define('AWS_SECRET_KEY', 'VKcJM+RDYY6ezE8i8MdAE3TUTf1iGbNSgj7vWF1Q');
//$ENDPOINT = 'http://objects.dreamhost.com';

// require the amazon sdk from your composer vendor dir
require __DIR__.'/vendor/autoload.php';

// Instantiate the S3 class and point it at the desired host
$client = new S3Client([
    'region' => 'ap-south-1',
    'version' => 'latest',
   // 'endpoint' => $ENDPOINT,
    'credentials' => [
        'key' => AWS_KEY,
        'secret' => AWS_SECRET_KEY
    ],
    // Set the S3 class to use objects.dreamhost.com/bucket
    // instead of bucket.objects.dreamhost.com
    'use_path_style_endpoint' => true
]);

//S3 Bucket list
print "<div><h3>S3 Bucket List</h3></div>";
$listResponse = $client->listBuckets();
$buckets = $listResponse['Buckets'];
foreach ($buckets as $bucket) {
    echo $bucket['Name'] . "\t" . $bucket['CreationDate'] . "<br>";
}
print "test 2";
?>
