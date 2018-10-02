<div class="mythContainer" id="mythpromo-<?php print $node->nid; ?>">
<div class="myth">          
  <h3><?php print $title; ?></h3>
  <?php print $field_body_rendered; ?>   

    <?php print $field_page_rendered; ?>
     
</div>
<?php print $contextual; ?>
</div>

<?php
if (isset($field_inline_css_rendered) && !empty($field_inline_css_rendered)){
?>
<style type="text/css">
<?php print $field_inline_css_rendered ?>
</style>
<?php 
}
?>