<?php

/**
 * KirbyPlaceholder
 *
 * <img src="/kp?size=400x150" />
 *
 */

namespace KirbyPlaceholder;
use c;

class KirbyPlaceholder {
  public static function register () {
    kirby()->routes([
      [
        'pattern' => self::route(),
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
  }

  public static function url ($width = 0, $height = 0) {
    return self::base() . self::route() . "?size={$width}x{$height}";
  }

  private static function base () {
    return str_replace('//', '/', url() . '/');
  }

  private static function route () {
    return c::get('kirbyplaceholder.route', 'kp');
  }
}

KirbyPlaceholder::register();