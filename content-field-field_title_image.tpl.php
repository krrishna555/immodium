<?php
/**
 * @file
 * Specific way of handling title image: we are going put inline CSS code
 * for header theming instead of rendering the image using an <img/> tag. 
 */ 

if (!$field_empty) {
  foreach ($items as $delta => $item) {
    if (!$item['empty']) {
      if (preg_match('/height="(\d+)"/', $item['view'], $matches)) {
        $height = $matches[1];
      }
      if ($height) {
?>
<style type="text/css">
  .flasContainer {
    height: <?php print $height; ?>px;
    background: transparent;
    background-image: url(<?php print base_path() . $item['filepath']; ?>);
    background-position: top right;
    background-repeat: no-repeat;
    position: relative;
  }
  .flasContainer h1 {
    position: absolute;
    margin-top: 15px;
    width: 50%;
  }
</style>
<?php
        // Else this is an error, we need the height.
      }
    }
  } // End foreach.
}
?>