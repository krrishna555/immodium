<?php if (!$field_empty) : ?>
<div class="glossaryBlock">
  <div class="glossary-block-left"></div>
  <div class="glossary-block-right"></div>
  <div class="glossary-block-inner">
    <div class="title">Glossary</div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
        <?php
          foreach ($items as $delta => $item):
            if (!$item['empty']):
              print node_view(node_load($item['nid']), TRUE);
            endif;
          endforeach;
        ?>
      </tbody>
    </table>
    <?php print imodium_custom_button("See the full glossary","glossary"); ?>
  </div>
</div>
<div class="clearall"></div>
<?php endif; ?>