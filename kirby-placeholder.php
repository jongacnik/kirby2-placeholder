<?php

/**
 * KirbyPlaceholder
 *
 * $file->placeholder()
 * -> data:image/png;base64, xxxxxxxxxxx
 *
 * $site->placeholder(100, 100)
 * -> data:image/png;base64, xxxxxxxxxxx
 *
 * also provides route for dynamic placeholders
 * <img src="/kp?size=400x150" />
 *
 */

namespace KirbyPlaceholder;
use c;
use site;
use file;

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

          self::makeImage($dimensions[0], $dimensions[1]);
        }
      ]
    ]);

    site::$methods['placeholder'] = function ($page, $width = 0, $height = 0) { 
      ob_start (); 
        self::makeImage($width, $height);
        $image_data = ob_get_contents (); 
      ob_end_clean (); 
      return 'data:image/png;base64, ' . base64_encode($image_data);
    };

    file::$methods['placeholder'] = function ($file, $reduce = 1) { 
      ob_start (); 
        self::makeImage($file->width()/$reduce, $file->height()/$reduce);
        $image_data = ob_get_contents (); 
      ob_end_clean (); 
      return 'data:image/png;base64, ' . base64_encode($image_data);
    };
  }

  private static function makeImage ($width, $height) {
    // Create image
    $image = imagecreate($width, $height);

    // make transparent
    $black = imagecolorallocate($image, 0, 0, 0);
    imagecolortransparent($image, $black);

    // Render image
    imagepng($image);
  }

  public static function url ($width = 0, $height = 0) {
    return self::base() . self::route() . "?size={$width}x{$height}";
  }

  private static function base () {
    return url() === '/' ? '/' : url() . '/';
  }

  private static function route () {
    return c::get('kirbyplaceholder.route', 'kp');
  }
}

KirbyPlaceholder::register();