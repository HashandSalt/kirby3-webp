<?php

/**
 *
 * WebP Plugin for Kirby 3
 *
 * @version   0.0.1
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/webp
 * @license   MIT <http://opensource.org/licenses/MIT>
 */

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('hashandsalt/kirby-webp', [

    'snippets' => [
      'webp'     => __DIR__ . '/snippets/webp.php',
    ],

    // Options
    'options' => [
      'range' => [1920, 1140, 640, 320],
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
