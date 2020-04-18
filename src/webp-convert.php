<?php

namespace Kirby\Plugins\WebP;

use F;
use File;
use WebPConvert\WebPConvert;
use WebPConvert\Convert\Converters\Stack;

class Convert
{

  public function generateWebP($file)
    {
        try {
            // Checking file type since only images are processed
            if ($file->type() == 'image') {

                // WebP Convert
                $input    = $file->root();
                $original = $file->filename();
                $fname    = F::name($original) . '.webp';
                $output   = str_replace($original, $fname, $input);

                // WebP Convert options
                $options = [
                    'png' => [
                        'encoding' => option('hashandsalt.kirby-webp.png.encoding'),
                        'quality' => option('hashandsalt.kirby-webp.png.quality'),
                    ],
                    'jpeg' => [
                        'encoding' => option('hashandsalt.kirby-webp.jpeg.encoding'),
                        'quality' =>  option('hashandsalt.kirby-webp.jpeg.quality'),

                    ]
                ];

                // Generating WebP image & placing it alongside the original version
                Stack::convert($input, $output, $options);

                // Create Meta for the WebP File
                $webpfile = File::factory([
                  'source'     => $output,
                  'parent'     => $file->parent(),
                  'filename'   => $fname,
                  'template'   => option('hashandsalt.kirby-webp.template')
                ]);

                $webpfile->save();
            }
        }

        catch (Exception $e) {
            return Response($e->getMessage());
        }
    }
}
