<?php
// The node identifier in the id property will allow us to make the javascript
// work here. 
if ($teaser) :
?>
<div id="video-<?php print $node->nid; ?>" class="videoThumb"<?php if ($node->video_hidden): ?> style="display: none;"<?php endif; ?>>
  <?php print $field_image_rendered; ?>
  <?php print $contextual; ?>
</div>
<?php else: ?>
<div id="node-<?php print $node->nid; ?>" class="node">
  <!-- Header. -->
  <h1><?php print $title; ?></h1>
  <div class="clearall"></div>
  <!-- End of Header. -->
  <!-- Content. -->                
  <div class="descContainer descContainerAuto">
    <div class="text-center">
      <?php print $field_title_video_rendered; ?>
    </div>
  </div>
  <div class="clearall"></div>
  <?php print $contextual; ?>
</div>
<?php endif; ?>