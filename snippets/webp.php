<?php
  $fb = $type;
  $fbtype = 'type="image/'.$type.'"';
  $img = $src;
  $fallbackimg = $img->parent()->file(str_replace($img->extension(), $fb, $img->filename()));
  $range = $sizes;
?>

<picture class="<?= $class ?>">

  <?php foreach($range as $max): ?>
    <source media="(min-width: <?= $max ?>px)" type="image/webp" srcset="<?= $img->resize($max)->url() ?>" alt="<?= $img->alt() ?>"  />
  <?php endforeach ?>

  <?php foreach($range as $max): ?>
    <source media="(min-width: <?= $max ?>px)" <?= $fbtype ?> srcset="<?= $fallbackimg->resize($max)->url() ?>" alt="<?= $fallbackimg->alt() ?>"  />
  <?php endforeach ?>

  <img
  src="<?= $fallbackimg->resize($width, $height)->url() ?>"
  alt="<?= $fallbackimg->alt() ?>"
  >

</picture>
