<?php

    return [

      'class' => 'app\modules\imageapi\components\ImageApi',
      'allowedImageExtensions' => array("*.jpg", "*.jpeg", "*.gif", "*.png"),
      // 'thumbs' - контроллер
      // http://www.verot.net/php_class_upload_samples.htm
      'presets' => [

          /*'original' => [
              'cacheIn'=>'@webroot/i/cat/photo/original',
              'actions'=>[
              ],
          ],*/

          /*'small' => [
              'cacheIn'=>'@webroot/i/cat/photo/small',
              'actions'=>[
                'image_x' => 72,
                //'image_y' => 100,
                'image_ratio_crop' => true,
                'image_resize' => true,
              ],
          ],*/

          'standart' => [
            'cacheIn' => '@webroot/i/cat/photo/standart',
            'actions' => [
              'image_x' => 378,
              //'image_y' => 100,
              'image_ratio_crop' => true,
              'image_resize' => true,
            ],
          ],
          /*'100x100' => [
              'cacheIn'=>'@webroot/i/cat/photo/100x100',
              'actions'=>[
                'image_x' => 100,
                'image_y' => 100,
                'image_ratio_crop' => true,
                'image_resize' => true,
                //'image_watermark' => '',
                //'image_increase' => false,
              ],
          ],*/

          '200x150' => [
            'cacheIn' => '@webroot/i/cat/photo/200x150',
            'actions' => [
              'image_x' => 200,
              'image_y' => 150,
              'image_ratio_crop' => true, // L, R, true
              'image_resize' => true,
              //'image_watermark' => 'logo70x45.png',
              //'image_watermark_path' => 'webroot.commons',
              //'image_watermark_x' => -10,
              //'image_watermark_y' => -10,
              //'image_increase' => false,
            ],
          ],
          /*'200x200' => [
              'cacheIn'=>'@webroot/i/cat/photo/200x200',
              'actions'=>[
                'image_x' => 200,
                'image_y' => 200,
                'image_ratio_crop' => true, // L, R, true
                'image_resize' => true,
                //'image_watermark' => 'logo70x45.png',
                //'image_watermark_path' => 'webroot.commons',
                //'image_watermark_x' => -10,
                //'image_watermark_y' => -10,
                //'image_increase' => false,
              ],
          ],*/

          '600x480' => [
            'cacheIn' => '@webroot/i/cat/photo/600x480',
            'actions' => [
              'image_x' => 600,
              'image_y' => 480,
              'image_ratio_crop' => true,
              'image_resize' => true,
              //'image_watermark' => 'logo70x45.png',
              //'image_watermark_path' => 'webroot.commons',
              //'image_watermark_x' => -10,
              //'image_watermark_y' => -10,
              //'image_increase' => false,
            ],
          ],

      ],
    ];