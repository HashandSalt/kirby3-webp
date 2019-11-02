# WebP Plugin for Kirby 3

A snippet and Kirby tag for using WebP images in templates and within textareas. Plugin will convert uploaded files to WebP on the server. This requires having the appropriate extensions installed for IM, GD or whatever you are using. Plugin was tested with GD & [cwebp](https://developers.google.com/speed/webp/docs/cwebp)

## Installation

### Manual

To use this plugin, place all the files in `site/plugins/kirby3-webp`.

### Composer

```
composer require hashandsalt/kirby3-webp
```
****

## Commerical Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://paypal.me/hashandsalt?locale.x=en_GB) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/36141?link=1170)

****

## Usage

### Using the Kirby Tag

On a basic level, this is enough:

```
(webp: yourimage.webp)
```

This will generate a picture tag with images for the default range of sizes: `[1920, 1140, 640, 320]`.

Will also accept `width`, `height`, `type`, `class`, `imgclass`, and `alt`.

The type allows you to set wether the fall back image is a jpg or a png.

Full example:

```
(webp: yourimage.webp width: 800 height: 600 type: png class: picturetagclass img: imgtagclass alt: my awesome alt text)
```

### Using the snippet

The tag uses a snippet, which you can use in your templates:

```
snippet('webp', ['sizes' => [1920, 1140, 640, 320], 'src' => 'yourimage.webp', 'type' => 'png', 'class' => 'picturetagclass', 'width' => 800, 'height' => 600])
```

If you want to modify the output of the snippet, you can copy it from the plugin into the usual snippet folder and it will get over ridden with your customised version. This is useful if you want to use `focusCrop` plugin rather then the built in `resize()`.

## Options

```
// Tag Options
'hashandsalt.kirby-webp.range' => [1920, 1140, 640, 320], // Default range of image sizes

// Convert Options
'hashandsalt.kirby-webp.template' => 'images', // file blueprint for converted files
'hashandsalt.kirby-webp.meta' => true,  // all|none|exif|icc|xmp

'hashandsalt.kirby-webp.png.encoding' => 'auto', // auto|lossy|lossless
'hashandsalt.kirby-webp.png.quality'  => 85, // 1 - 100

'hashandsalt.kirby-webp.jpeg.encoding' => 'auto', // auto|1 - 100
'hashandsalt.kirby-webp.jpeg.quality'  => 85, // 1 - 100
```

## Roadmap

The following features will be implemented over time:

* Field Method.
* File Method.
* ~~Convert source image to WebP on the server~~.
