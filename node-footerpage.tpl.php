<div id="node-<?php print $node->nid; ?>" class="node footernode">
  <!-- Header. -->
  <h1><?php print $field_title_rendered; ?></h1>
  <div class="clearall"></div>
  <!-- End of Header. -->
  <!-- Content. -->                
  <div class="descContainer<?php if (!$show_right_panel): print ' descContainerExpand'; endif; ?>">
    <?php print $field_body_rendered; ?>
    <?php print $field_page_rendered; ?>
  </div>
  <div class="clearall"></div>
  <?php print $contextual; ?>
</div>
