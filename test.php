<?php
print "test 4354342";

?>
<?php
 $color_image = urlencode('https://motokart-uat.s3.ap-south-1.amazonaws.com/cms/s3fs-public/motokaart/Hero/105-XPULSE 200 BS6 - X PULSE 200 BS6 360 WHITE/Bike image/1440x920.png');
 $color_image = str_replace(['%2F', '%3A'], ['/', ':'], $color_image);
          $data22 = file_get_contents($color_image);
  
var_dump($data22);



?>asasda