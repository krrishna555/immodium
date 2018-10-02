<script language="JavaScript" type="text/javascript">
  if (DetectFlashVer(1,0,0)) {
    AC_FL_RunContent(
      'codebase',
      'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
      'width', '<?php print $width; ?>',
      'height', '<?php print $height; ?>',
      'src', '<?php print $player_id; ?>',
      'quality', 'high',
      'pluginspage', 'http://www.adobe.com/go/getflashplayer',
      'align', 'middle',
      'play', '<?php print $autoplay ? 'true' : 'false' ?>',
      'loop', 'false',
      'scale', '<?php print $scale; ?>',
      'wmode', '<?php print $wmode; ?>',
      'devicefont', 'false',
      'id', '<?php print $uniq_id; ?>',
      'bgcolor', '#ffffff',
      'name', '<?php print $uniq_id; ?>',
      'menu', 'false',
      'allowFullScreen', 'false',
      'allowScriptAccess','sameDomain',
      'movie', '<?php print $player_id; ?>',
      'salign', '',
      'swliveconnect', '<?php print $swliveconnect; ?>',
      'flashvars', '<?php print $flashvars_get; ?>'
    ); //end AC code
  }
</script><noscript>
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
          codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0"
          width="<?php print $width; ?>"
          height="<?php print $height; ?>"
          id="<?php print $uniq_id; ?>"
          align="middle">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="allowFullScreen" value="false" />
    <param name="movie" value="<?php print $player_id; ?>" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#ffffff" />
    <param name="flashvars" value="<?php print $flashvars_get; ?>" />
    <param name="wmode" value="<?php print $wmode; ?>" />
    <param name="scale" value="<?php print $scale; ?>" />
    <embed src="<?php print $player_path; ?>"
           quality="high"
           bgcolor="#cccccc"
           width="<?php print $width; ?>"
           height="<?php print $height; ?>"
           name="<?php print $player_id; ?>"
           align="middle"
           allowScriptAccess="sameDomain"
           scale="<?php print $scale; ?>"
           wmode="<?php print $wmode; ?>"
           allowFullScreen="false"
           type="application/x-shockwave-flash"
           pluginspage="http://www.adobe.com/go/getflashplayer"
           flashvars="<?php print $flashvars_get; ?>" />
  </object>
</noscript>
<?php if (!$no_behavior): ?>
<script language="JavaScript" type="text/javascript">
;(function($){$(document).ready(function() {
  var player = $('embed[name=<?php print $uniq_id; ?>]');
  if (!player.length) player = $('object[id=<?php print $uniq_id; ?>]');
  if (player.length) player.imodiumVideo({rotation: <?php print $rotation ? 'true' : 'false'; ?>});
});})(jQuery);
</script>
<?php endif; ?>