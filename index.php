<?php

/**
 *
 * WebP Plugin for Kirby 3
 *
 * @version   0.0.6
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/webp
 * @license   MIT <http://opensource.org/licenses/MIT>
 */


use WebPConvert\Convert\Converters\Stack;

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
        'generateWebp' => function () {
            try {
                // Checking file type since only images are processed
                if ($this->type() == 'image') {
                    // WebP Convert
                    $input = $this->root();
                    $original = $this->filename();
                    $fname = F::name($original) . '.webp';
                    $output = str_replace($original, $fname, $input);

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

                    // Generating WebP image & placing it alongside the original version
                    Stack::convert($input, $output, $options);

                    // Create Meta for the WebP File
                    $file = File::factory([
                        'source' => $output,
                        'parent' => $this->parent(),
                        'filename' => $fname,
                        'template' => option('hashandsalt.kirby-webp.template')
                    ]);

                    return $file->save();
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        },
        'toType' => function ($type = 'webp', $filename = false) {
            $pattern = '/\.[a-z]*$/';

            if ($filename) {
                return preg_replace($pattern, '.' . $type, $this->filename());
            }

            return preg_replace($pattern, '.' . $type, $this->id());
        },
        'toWebp' => function () {
            if (!$this) {
                return null;
            }

            if ($webp = $this->files()->findByKey($this->toType('webp', true))) {
                return $webp;
            } else {
                return $this->generateWebp();
            }
        },
        'toSource' => function () {
            if ($files = $this->toVariants()->filterBy('extension', '!=', 'webp')) {
                return $files->first();
            } else {
                return null;
            }
        },
        'toVariants' => function () {
            $pathinfo = pathinfo($this->root());
            $filename = $pathinfo['filename'];

            return $this->files()->filterBy('type', '==', 'image')->filter(function ($file) use ($filename) {
                $pathinfo = pathinfo($file->root());

                return $pathinfo['filename'] === $filename;
            });
        },
        'hasWebp' => function () {
            return $this->files()->find($this->toType('webp', true)) ? true : false;
        },
        'isWebp' => function () {
            $pathinfo = pathinfo($this->root());

            return $pathinfo['extension'] === 'webp';
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
