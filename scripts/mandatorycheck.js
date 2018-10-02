;jQuery(document).ready(function() {
	var chkform = jQuery("form#contact-mail-page");
	jQuery("#edit-motilium-validate", chkform).each(function(){
		jQuery(this).click(function(event) {
			var mychk = jQuery(this);
			var thebutton = jQuery("input:submit",chkform);
			if (mychk.is(':checked')) {
				thebutton.attr('disabled', '');
			} else {
				thebutton.attr('disabled', 'disabled');
			}
		}).triggerHandler('click');
	}); 
});