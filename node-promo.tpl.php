<div id="promo-<?php print $node->nid; ?>">
	<div class="promoSmall">
	  <div class="promoSmallTop">
	  	<?php //<pre> print_r($field_background); </pre> ?>
	  	<?php if (isset($field_title_rendered) && !empty($field_title_rendered)): ?>
	    <h6><?php print $field_title_rendered; ?></h6>
	    <?php endif; ?>
	    <div class="promoSmallInner">
	      <?php print $field_image_rendered; ?>
	      <?php print $field_body_rendered; ?>
	      <div class="clearall"></div>
	    </div>
	    <?php print $field_page_rendered; ?>
	    <?php print $field_destination_url_rendered; ?>
	  </div>
	  <div class="promoSmallBottom"></div>
	  <?php print $contextual; ?>
	</div>
</div>
<?php
if (isset($field_background_rendered) and !empty($field_background_rendered))
{
?>
<style type="text/css">
#promo-<?php print $node->nid; ?> .promoSmallTop{
	background:none;
}
#promo-<?php print $node->nid; ?> .promoSmallBottom{
	background:none;
}
#promo-<?php print $node->nid; ?> .promoSmall{
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