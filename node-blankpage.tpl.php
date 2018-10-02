<?php 
  	$descContainerExpand = "descContainerExpand";
  	$flasContainer = "flasContainer";
  	$heading_title = "";
?>
<div id="node-<?php print $node->nid; ?>" class="node" style="width:750px; margin: 0 auto;">
  <!-- Header. -->
  <?php if ($title_field_mode == 'flash'): ?>
    <div class="flasContainer">
      <?php print $title_field; ?>
    </div>
  <?php endif; ?>
  <div class="clearall"></div>
  <!-- End of Header. -->
  <!-- Content. -->                
  <div class="descContainer">
    <?php print $field_body_rendered; ?>
    <?php print $field_page_rendered; ?>
  </div>
  <div class="clearall"></div>
  <?php print $contextual; ?>
</div>
