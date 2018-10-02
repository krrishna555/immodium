<?php if (!$field_empty) : ?>
<div class="relatedLinks">
  <div class="headingGray">Related links</div>
  <?php foreach ($items as $delta => $item): ?>
    <?php if (!$item['empty']): ?>
       <?php print $item['view']; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>