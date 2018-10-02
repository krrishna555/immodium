<div class="flasContainer"><?php print $field_banner_rendered; ?></div>
<div class="clearall"></div>
  <div class="descComContainer"> 
     <?php print $field_body_rendered; ?>
  </div>
	<div class="clearall"></div>
     <?php 
	      $form_id = $node->field_form_id[0]['value'];
		  print drupal_get_form($form_id); ?>
<?php print $contextual; ?>