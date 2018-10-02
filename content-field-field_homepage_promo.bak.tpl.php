<?php
/**
 * @file
 * Default theming for CCK, we get rid of all uneeded divs.
 */
if (!$field_empty) :
  $i = 0;
  foreach ($items as $delta => $item):
    if (!$item['empty']):?>
	  <div class="home-promo-item-<?php print ++$i ?> <?php $i==3? print "last" : print "" ?>">
<?php  
      print $item['view'] . "\n";?>
      </div>
<?php
    endif;
  endforeach;
endif;
?>