<?php
  // Specific classes for HCP page.
  // These changes are too small to justify a different template file.
  $descContainerExpand = "descContainerExpand";
  $flasContainer = "flasContainer";
  $heading_title = "";
  $videodisclaimer = $field_video_disclaimer_rendered;
  $show_video_disclaimer = (!empty($videodisclaimer));
?>
<div id="node-<?php print $node->nid; ?>" class="node">
  <h1><?php print $field_title_rendered; ?></h1>
  <?php //I know this is an ugly hack, but this whole page is an ugly hack because the layout is completly out of specifications...  ?>
    <div id="hack-centered-subtitle-text"><p>Ever wondered what causes diarrhoea, why we suffer and how to manage the condition?</p></div>
  <!-- Header. -->
  <?php if ($show_video_disclaimer): ?>
  <div class="video_disclaimer">
  <?php endif; ?>
    
  <div class="flasContainer">
  <?php //I know this is an ugly hack, but this whole page is an ugly hack because the layout is completly out of specifications...  ?>
    <div id="hack-content-on-top"><p>Watch informative advice videos with Dr Christian Jessen by clicking on any of the videos below.</p></div>
    <div id="current-video-title"></div>
    <div id="around-main-video">
    <?php print $title_field; ?>
    </div>
  </div>
  <div class="clearall"></div>
  <!-- End of Header. -->
  <!-- Content. -->
  <div class="descContainer<?php print ' ' . $descContainerExpand; ?>">
  
    <div class="videoThumbContainer videoThumbContainerHoriz">
      <?php print $right_content; ?>
    </div>
     <div class="clearall"></div>
    <?php print $field_body_rendered; ?>
      <?php if ($show_video_disclaimer): ?>
        <span class="videodisclaimer"><?php print $videodisclaimer; ?></span>
      <?php endif; ?>
  </div>
  
  <?php if ($show_video_disclaimer): ?>
  </div>
  <?php endif; ?>
    <?php print $field_page_rendered; ?>
    
  <div class="clearall"></div>
  <!-- Misc. fields non body related. -->
  <?php /* See upper hack print $field_fact_rendered;  */ ?>
  <?php print $field_faq_rendered; ?>
  <?php print $field_glossary_rendered; ?>
  <!-- End of Misc. fields non body related. -->
  <?php print $field_fact_promo_rendered;?>
  <div class="bottomPromoContainer">
    <?php /* FIXME: Missing field 'promo' in database. */ ?>
    <?php print $field_promo_rendered; ?>
  </div>
  <?php print $contextual; ?>
</div>
