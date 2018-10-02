<div id="promo-<?php print $node->nid; ?>">
<div class="promoBig">
  <div class="promoBigTop">
    <h6><?php print $field_title_rendered; ?></h6>
    <?php if (isset($field_image_rendered) && !empty($field_image_rendered)): ?>
    <div class="promoBigLeftPart">
      <?php print $field_image_rendered; ?>
    </div>
    <?php endif; ?>
    <div class="promoBigRightPart">
      <?php print $field_body_rendered; ?> 
      <?php print $field_page_rendered; ?>
    </div>
    <div class="clearall"></div>
  </div>  
  <div class="promoBigBottom"></div>
  <?php print $contextual; ?>
</div>
</div>
<?php
if (isset($field_background_rendered) and !empty($field_background_rendered))
{
?>
<style type="text/css">
#promo-<?php print $node->nid; ?> .promoBigTop{
	background:none;
}
#promo-<?php print $node->nid; ?> .promoBigBottom{
	background:none;
}
#promo-<?php print $node->nid; ?> .promoBig{
	background: url("<?php print $field_background[0]['filepath']; ?>") no-repeat scroll 0 0 transparent;
}
</style>
<?php
}
if (isset($field_inline_css_rendered) && !empty($field_inline_css_rendered)){
?>
<style type="text/css">
<?php print $field_inline_css_rendered ?>
</style>
<?php 
}
?>