<div class="factList">
  <div class="factListCallOut">
    <div class="factListCallOutHeading"><?php print $title; ?></div>
    <div class="factListCallOutBottom"></div>
  </div>
  <div class="factDesc">
    <div class="factHeading factHeading<?php print ($true ? 'True' : 'False'); ?>"><?php print $field_validity_rendered; ?></div>
    <?php print $field_body_rendered; ?>
  </div>
  <?php print $contextual; ?>
</div>
<div class="clearall"></div>