<?php
  // Specific classes for HCP page.
  // These changes are too small to justify a different template file.
  if ($_GET['q'] == 'node/46') {
  	$descContainerExpand = "hcpdescContainer";
  	$flasContainer = "hcpPage";
  	$heading_title = "class='hcpHeading'";
  }
  else {
  	$descContainerExpand = "descContainerExpand";
  	$flasContainer = "flasContainer";
  	$heading_title = "";
  }
  $videodisclaimer = $field_video_disclaimer_rendered;
  $show_video_disclaimer = (!empty($videodisclaimer));
?>
<div id="node-<?php print $node->nid; ?>" class="node">
  <!-- Header. -->
  <?php if ($title_field_mode == 'flash'): ?>
    <div class="flasContainer">
      <?php print $title_field; ?>
    </div>
  <?php elseif ($title_field_mode == 'video'): ?>
    <div class="flasContainer">
      <?php print $title_field; ?>
    </div>
    <h1><?php print $field_title_rendered; ?></h1>
  <?php elseif ($title_field_mode == 'image'): ?>
    <?php print $title_field; ?>
    <div class="<?php print $flasContainer; ?>">
      <h1 <?php print $heading_title; ?>><?php print $field_title_rendered; ?></h1>
    </div>
  <?php else: ?>
    <h1><?php print $field_title_rendered; ?></h1>
  <?php endif; ?>
  <div class="clearall"></div>
  <!-- End of Header. -->
  <!-- Content. -->
  <div class="descContainer<?php if (!$show_right_panel): print ' ' . $descContainerExpand; endif; ?>">
    <?php print $field_body_rendered; ?>
    <?php /* Hack: This should be under. */ print $field_fact_rendered; ?>
    <?php print $field_page_rendered; ?>
  </div>
  <!-- FIXME: Replace this with the videos or promo fallback field. -->
  <?php if ($show_right_panel): ?>
    <?php if ($show_video_disclaimer): ?>
    <div class="video_disclaimer">
    <?php endif; ?>
  <div class="videoThumbContainer">
    <?php print $right_content; ?>
    <?php print $field_related_rendered; ?>
  </div>
    <?php if ($show_video_disclaimer): ?>
    <span class="videodisclaimer"><?php print $videodisclaimer; ?></span>
    </div>
    <?php endif; ?>
  <?php endif; ?>
  <div class="clearall"></div>
  <!-- Misc. fields non body related. -->
  <?php /* See upper hack print $field_fact_rendered;  */ ?>
  <?php print $field_faq_rendered; ?>
  <?php print $field_myth_rendered; ?>
  <?php print $field_glossary_rendered; ?>
  <!-- End of Misc. fields non body related. -->
  <?php print $field_fact_promo_rendered;?>
  <div class="bottomPromoContainer">
    <?php /* FIXME: Missing field 'promo' in database. */ ?>
    <?php print $field_promo_rendered; ?>
  </div>
  <?php print $contextual; ?>
</div>
