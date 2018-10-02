<?php
/* $Id: forward.tpl.php,v 1.1.2.9 2010/08/30 19:59:34 seanr Exp $ */

/**
 * This template should only contain the contents of the body
 * of the email, what would be inside of the body tags, and not
 * the header.  You should use tables for layout since Microsoft
 * actually regressed Outlook 2007 to not supporting CSS layout.
 * All styles should be inline.
 *
 * For more information, consult this page:
 * http://www.anandgraves.com/html-email-guide#effective_layout
 *
 * If you are upgrading from an old version of Forward, be sure
 * to visit the Forward settings page to enable use of the new
 * template system.
 */
?>
<html>
  <body>
    <table width="<?php print $width; ?>" cellspacing="0" cellpadding="10" border="0">
      <thead>
        <tr>
          <td>
          <?php
          $logoimg = (variable_get('forward_header_image', '') == '') ? theme_get_setting('logo') : variable_get('forward_header_image', '');
          $logo = (!empty($logoimg)) ? '<img style="border: 0;width:189px height:59px;" "src="'. url($logoimg, array('absolute' => TRUE)). '" alt="" width="189" height="59"/>' : ''; ?>
            <h1 style="font-family:Arial,Helvetica,sans-serif; font-size:14px;"><a href="<?php print $site_url; ?>" title="<?php print $site_title; ?>"><?php print $logo; ?> <?php print $site_name; ?></a></h1>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-family:Arial,Helvetica,sans-serif; font-size:12px;">
            <p>Hi <?php print(check_plain($_POST['friend_name'])); ?>,</p>
            <?php print $forward_message; ?>
            <?php if ($title) { ?><h2 style="font-size: 14px;"><?php print $title; ?></h2><?php } ?>
            <p>Hope you find it helpful.</p>
			<p>Best wishes,</p>
			<p><?php print $name; ?>
			<hr style="width:100%; height:1px;"/>
			<p>Confidentiality Notice</p>
			<p>&copy; IMODIUM&reg;, 2009</p>
			<p>In sending you this email, McNeil Healthcare (UK) Ltd has not retained your personal data.</p>
			<p>This email is sent by IMODIUM&reg; which is solely responsible for it's contents. It is intended for residents of the United Kingdom.</p>
			<p>This email is sent in reference to www.imodium.co.uk and its <a href="http://www.imodium.co.uk/legal_notice">Legal Notice</a> and <a href="http://www.imodium.co.uk/privacy_policy">Privacy Policy</a></p>
			<p>Registered office: Saunderton High Wycombe, Buckinghamshire HP144HJ Registered in England No.2851962</p>
          </td>
        </tr>
        <?php if ($dynamic_content) { ?><tr>
          <td style="font-family:Arial,Helvetica,sans-serif; font-size:12px;">
            <?php print $dynamic_content; ?>
          </td>
        </tr><?php } ?>
        <?php if ($forward_ad_footer) { ?><tr>
          <td style="font-family:Arial,Helvetica,sans-serif; font-size:12px;">
            <?php print $forward_ad_footer; ?>
          </td>
        </tr><?php } ?>
        <?php if ($forward_footer) { ?><tr>
          <td style="font-family:Arial,Helvetica,sans-serif; font-size:12px;">
            <?php print $forward_footer; ?>
          </td>
        </tr><?php } ?>
      </tbody>
    </table>
  </body>
</html>
