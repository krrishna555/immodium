<?php if (!$field_empty) : ?>

        <?php
          foreach ($items as $delta => $item):
            if (!$item['empty']):
              print node_view(node_load($item['nid']), TRUE);
            endif;
          endforeach;
        ?>

<div class="clearall"></div>
<?php endif; ?>