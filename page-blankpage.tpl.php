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
    <div class="blankcontainer">
      <div class="contentArea">
        <?php print $content; ?>  
        <div class="clearall"></div>     
      </div>
      <div class="footerArea">
        <?php print $sitemapLinks; ?>
        <div class="footer">
        <?php print $footer; ?>
        </div>
      </div>
    </div>
    <?php print $closure; ?>
  </body>
</html>
