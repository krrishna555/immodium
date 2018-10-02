<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir; ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php print $head_title; ?></title>
    <?php print $head; ?>
    <?php print $styles; ?>
    <?php print $scripts; ?>
    <!--[if IE 6]>
    <link href="<?php print base_path() . path_to_theme(); ?>/css/styleIE6.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 7]>
    <link href="<?php print base_path() . path_to_theme(); ?>/css/styleIE7.css" rel="stylesheet" type="text/css" />
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="header">
        <div class="logoArea">
          <a href="<?php print url("<front>"); ?>" title="Imodium">
            <img src="<?php print base_path() . path_to_theme(); ?>/images/imodiumLogo.jpg" alt="Imodium" title="Imodium" />
          </a>
        </div>
        <div class="searchBoxArea">
          <div class="buttons">
            <?php print $header_menu; ?>
            <div class="clearall"></div>
          </div>
          <?php print $header; ?>
        </div>
      </div>
      <?php if (!empty($messages)): ?>
      <div id="messages">
        <div id="messages-close"></div>
        <?php print $messages; ?>
      </div>
      <?php endif; ?>
      <div class="contentArea">
        <div id="region-top-menu">
          <?php print $top_menu; ?>
        </div>
        <?php print $bannerSection; ?>  
      </div>
      <div class="footerArea">  
        <?php print $content; ?>  
        <div class="clearall"></div>     
        <?php print $sitemapLinks; ?>
        <div class="footer">
          <?php print $footer; ?>
        </div>
      </div>
    </div>
    <?php print $closure; ?>
  </body>
</html>
