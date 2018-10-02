<?php if (!$field_empty) : ?>
<div class="faqBlock">
  <div class="faq-block-left"></div>
  <div class="faq-block-right"></div>
  <div class="faq-block-inner">
    <div class="title">Frequently Asked Questions</div>
    <?php
      foreach ($items as $delta => $item) {
        if (!$item['empty']) {
          print node_view(node_load($item['nid']), TRUE);
        }
      }
    ?>
    <?php print imodium_custom_button("See all FAQs",'faqs'); ?>
  </div>
</div>
<div class="clearall"></div>
<?php endif; ?>