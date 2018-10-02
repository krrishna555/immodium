<?php if (!$field_empty) : ?>
  <?php foreach ($items as $delta => $item): ?>
    <?php if (!$item['empty']): ?>
      <?php print $item['view']; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>