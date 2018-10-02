<div id="promo-<?php print $node->nid; ?>">
	<div class="promoBox item-<?php print $id; ?>"> 
	  <div class="promoBoxTop"> 
	    <div class="promoLeft"> 
	      <h4 class=""><?php print $field_title_rendered; ?></h4>  
	      <?php print $field_body_rendered; ?> 
	    </div> 
	    <div class="promoRight"> 
	      <?php print $field_image_rendered; ?>
	    </div> 
	    <div class="clearall"></div> 
	  </div> 
	  <div class="promoBoxBottom"> 
	    <?php print $field_page_rendered; ?> 
	    <?php print $field_destination_url_rendered; ?>
	    <div class="clearall"></div> 
	  </div> 
	  <?php print $contextual; ?>
	</div>
</div>
<?php 
if (isset($field_background_rendered) and !empty($field_background_rendered))
{
	?>
	<style type="text/css">
	#promo-<?php print $node->nid; ?> .promoBoxTop{
		background:none;
	}
	#promo-<?php print $node->nid; ?> .promoBoxBottom{
		background:none;
	}
	#promo-<?php print $node->nid; ?> .promoBox{
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