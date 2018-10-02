<div id="promo-<?php print $node->nid; ?>">
<div class="homeFancyPromo">
	<div class="homeFancyPromoLeft">
	    <h3><?php print $field_title_rendered; ?></h3>
    	<?php print $field_body_rendered; ?>
    	 <div class="promoBoxBottom">
    	 	<?php print $field_page_rendered; ?>
    	 	<div class="clearall"></div>  
    	 </div>
    </div>
    <?php print $contextual; ?> 
</div>
</div>
<?php 
if (isset($field_background_rendered) and !empty($field_background_rendered))
{
	?>
	<style type="text/css">
	#promo-<?php print $node->nid; ?> .homeFancyPromo{
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