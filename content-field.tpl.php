<?php
/**
 * @file
 * Default theming for CCK, we get rid of all uneeded divs.
 */
if (!$field_empty) :
  foreach ($items as $delta => $item):
    if (!$item['empty']):
      print $item['view'] . "\n";
    endif;
  endforeach;
endif;
?>