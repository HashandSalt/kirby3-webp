<?php

/**
 *
 * WebP Plugin for Kirby 3
 *
 * @version   0.0.2
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/webp
 * @license   MIT <http://opensource.org/licenses/MIT>
 */

@include_once __DIR__ . '/vendor/autoload.php';
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
        'webp' => function () {
          // TODO: make file method!
        }
    ],

    'fieldMethods' => [
        'webp' => function () {
          // TODO: make field method!
        }
    ],

    // Hooks
    'hooks' => [
        'file.create:after' => function ($file) {
            (new Kirby\Plugins\WebP\Convert)->generateWebP($file);
        },

        'file.replace:after' => function ($newFile, $oldFile) {
            (new Kirby\Plugins\WebP\Convert)->generateWebP($newFile);
        },

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
