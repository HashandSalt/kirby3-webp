<?php

/**
 *
 * WebP Plugin for Kirby 3
 *
 * @version   0.0.3
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/webp
 * @license   MIT <http://opensource.org/licenses/MIT>
 */


@include_once __DIR__ . '/vendor/autoload.php';


use WebPConvert\WebPConvert;
use WebPConvert\Convert\Converters\Stack;


@include_once __DIR__ . '/src/webp-convert.php';


Kirby::plugin('hashandsalt/kirby-webp', [

    'snippets' => [
      'webp'     => __DIR__ . '/snippets/webp.php',
    ],

    // Options
    'options' => [

      // Tag Options
      'range' => [1920, 1140, 640, 320], // Default range of image sizes

      // Convert Options
      'template' => 'images', // file blueprint for converted files
      'meta' => true,  // all|none|exif|icc|xmp

      'png.encoding' => 'auto', // auto|lossy|lossless
      'png.quality'  => 85, // 1 - 100

      'jpeg.encoding' => 'auto', // auto|1 - 100
      'jpeg.quality'  => 85, // 1 - 100

    ],

    // Methods
    'fileMethods' => [

        'generateWebp' => function () {
            try {
                // Checking file type since only images are processed
                if ($file->type() == 'image') {
                    // WebP Convert
                    Dir::make(dirname($file->mediaRoot()));

                    $input = $file->root();
                    $output = preg_replace('/\.[a-z]*$/', '.webp', $file->mediaRoot());

                    // WebP Convert options
                    $options = [
                        'png' => [
                            'encoding' => option('hashandsalt.kirby-webp.png.encoding'),
                            'quality' => option('hashandsalt.kirby-webp.png.quality')
                        ],
                        'jpeg' => [
                            'encoding' => option('hashandsalt.kirby-webp.jpeg.encoding'),
                            'quality' => option('hashandsalt.kirby-webp.jpeg.quality')

                        ]
                    ];

                    return Stack::convert($input, $output, $options);
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        },

        'webp' => function () {
          $webp = $this->generateWebp();
          return preg_replace('/\.[a-z]*$/', '.webp', $this->mediaUrl());

        }
    ],



    // Tags
    'tags' => [
        'webp' => [
            'attr' => [
                'fallback',
                'class',
                'imgclass',
                'alt',
                'width',
                'height'
            ],
            'html' => function($tag) {

              if ($tag->file = $tag->file($tag->value)) {
                  $tag->src       = $tag->file($tag->value);
                  $tag->alt       = $tag->alt ?? $tag->file->alt()->or(' ')->value();
                  $tag->fallback  = $tag->fallback ?? 'jpg';
                  $tag->width     = $tag->width ?? $tag->file($tag->value)->width();
                  $tag->height    = $tag->height ?? $tag->file($tag->value)->height();
                  $tag->sizes     = $tag->sizes ? $tag->sizes : option('hashandsalt.kirby-webp.range');

              } else {
                  $tag->src = Url::to($tag->value);
              }

              // Build up tag
              return snippet('webp', ['sizes' => $tag->sizes, 'src' => $tag->src, 'type' => $tag->fallback, 'class' => $tag->class, 'width' => $tag->width, 'height' => $tag->height], false);


            },
        ]
    ],

]);
