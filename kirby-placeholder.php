<?php

/**
 * KirbyPlaceholder
 *
 * <img src="/kp?size=400x150" />
 *
 */

kirby()->routes([
  [
    'pattern' => c::get('kirbyplaceholder.route', 'kp'),
    'action'  => function () { 

      header ("Content-type: image/png");

      // Dimensions
      $getsize = isset($_GET['size']) ? $_GET['size'] : '1x1';
      $dimensions = explode('x', $getsize);

      // Create image
      $image = imagecreate($dimensions[0], $dimensions[1]);

      // make transparent
      $black = imagecolorallocate($image, 0, 0, 0);
      imagecolortransparent($image, $black);

      // Render image
      imagepng($image);
    }
  ]
]);