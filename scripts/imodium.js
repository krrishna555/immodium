/**
 * @file
 * Immodium theme JS specifics.
 */

;(function($){
  /**
   * Simple behavior that allows to close messages zone.
   */
  Drupal.behaviors.ImodiumMessages = function( context ) {
    jQuery("#messages-close:not(.imodium-processed)", context)
      .addClass('imodium-processed')
      .unbind('click')
      .click(function( event ) {
        jQuery("#messages", context).slideUp();
      });
  };

  /**
   * Nice menus override.
   */
  Drupal.behaviors.NiceMenuOverride = function( context ) {
    $('#region-top-menu ul.nice-menu:not(.imodium-processed)')
      .addClass('imodium-processed')
      .each(function() {
        var $this = $(this),
            $children = $('.dropdown-menu-child', $this);

        // Position children.
        $children.each(function() {
          var $child = $(this);

          $child.parent().css({
            'position': "relative",
            'z-index': "99999"
          });
          $child.css({
            'position': "absolute",
            'top': "40px",
            'left': "0px",
            'z-index': '100000'
          });
        });

        // Adjust last element to avoid it from going out of content.
        $children.eq($children.length - 1).css({
          'left': "auto",
          'right': "0px"
        });

        $this.find('li.dropdown-menu')
          .each(function() {
            // Mark the menu item as being active or not.
            $.data(this, 'active', $(this).find('a:first').hasClass('active'));
          })
          .hover(function( event ) {
            // Hover behavior.
            var $this = $(this);
            $this.find('.dropdown-menu-child').show();
            $this.find('a:first').addClass('active');
          }, function( event ) {
            // Mouse out behavior.
            var $this = $(this);
            $this.find('.dropdown-menu-child').hide();
            if (!$.data(this, 'active')) {
              $this.find('a:first').removeClass('active');
            }
          })
        });
  };

  /**
   * Contact page display privacy policy toggle link.
   */
  Drupal.behaviors.ImodiumContactPrivacy = function( context ) {
    // Prep. some variables.
    var content = jQuery("div.privacy-policy-inline", context),
        link = jQuery("a.privacy-show-link", context); 
    // Hide content on page load.
    content.hide();
    content._hidden = true;
    // Bind click event.
    link.click(function( event ) {
      event.preventDefault();
      event.stopPropagation();
      // Toggle.
      if (content._hidden) {
        content.show();
        content._hidden = false;
      }
      else {
        content.hide();
        content._hidden = true;
      }
      return false;
    });
    
    // Moves the "i agree" checkbox under the privacy policy statement
    var iAgreeBox = jQuery("#edit-motilium-validate-wrapper");
    if (iAgreeBox.length!=0) {
    	iAgreeBox.insertAfter(jQuery(".privacy-policy-inline"));
    }
  };
  
})(jQuery);

// Search page jnj_google_search module's functions callbacks
;jQuery(document).ready(function(){
    /* google Search results hooks to adapt the rendering of the search page */
    if (undefined != Drupal.settings.JnJGoogleSearch) {
	    if (undefined != Drupal.settings.JnJGoogleSearch) {
		    /* 1st hook, use at search page init */
		    if (Drupal.settings.JnJGoogleSearch.searchForm) {
			    Drupal.settings.JnJGoogleSearch.InitSearchPage = function(){
				    // hide the first branding as we have put it in another place
				    jQuery("table.gsc-branding",jQuery('form.gsc-search-box')).hide();
				    // hide original search button
				    jQuery("td.gsc-search-button",jQuery('form.gsc-search-box')).hide();
				    // hide clear results button
				    jQuery("td.gsc-clear-button",jQuery('form.gsc-search-box')).hide();
				    //replace submit button by our image one
				    var mysearchbtn = jQuery('<a />').attr('id',"jnj-google-search-btn").attr('href',"#")
				      .addClass("call2ActionBtn").attr('title',"Search");
				    mysearchbtn.append(jQuery('<span />').text('Search'));
				    mysearchbtn.insertAfter(jQuery("td.gsc-input"));
		        	
				    // forward click to search button
				    mysearchbtn.click( function(e) {
		        		jQuery("form.gsc-search-box").trigger('submit');
		        	} );
			    }
	        }
	    }
	    /* 2nd hook, provided at each ajax google results came back and the result div is drawn */
	    Drupal.settings.JnJGoogleSearch.OnSearchComplete = function(sc, searcher) {
	        // hook on google search results to show which search string was used
	        var infostr = 'You searched for ';
	        var infospan = jQuery('<span class="infoDescrStrong"></span>');
	        // searcher.Ve used to contain searched text (now Ue does)?
	        //seems it moves on google side, so instead get search from input
	        //infospan.text(searcher.Ue);
	        infospan.text(jQuery("input",jQuery(".gsc-input:first")).val())
	        var infodiv1 = jQuery('#searchInfoDiv1');
	        if(infodiv1.length==0) {
	            var infodiv1 = jQuery('<div id="searchInfoDiv1"></div>');
	            var ResultTitleTable=jQuery("table.gsc-search-box");
	            infodiv1.insertAfter(ResultTitleTable);
	        }
	        infodiv1.text(infostr);
	        infodiv1.append(infospan);
	        // Hook on google search result to build a short info text on the form:
	        // Results (1-8 of 15)
	        // on top of the results
	        if ( searcher.results && searcher.results.length > 0) {
	            var nbres = searcher.cursor.estimatedResultCount;
	            var nbpages = searcher.cursor.pages.length;
	            var curpage = searcher.cursor.currentPageIndex;
	            var pageinfo = searcher.cursor.pages[curpage];
	            var pagestart = parseInt(pageinfo.start,10);
	            var pageend = pagestart + searcher.results.length;
	            if (pageend > nbres) {
	              nbres = pageend;
	            }
	            var infotitle = jQuery('<span class="jnj-google-search-results-title">Results </span>');
	            var infostring = ' (' + pagestart + '-' + pageend + ' of ' + nbres + ')';
	            var infodiv = jQuery('#searchInfoDiv');
	            if(infodiv.length==0) {
	                var infodiv = jQuery('<div id="searchInfoDiv"></div>');
	                var ResultTitleTable=jQuery("table.gsc-resultsHeader:first");
	                infodiv.insertBefore(ResultTitleTable);
	            }
	            infodiv.text(infostring).prepend(infotitle);
	        }
	        // hide the resultsHeader from google
	        jQuery("table.gsc-resultsHeader").hide();
	        
	        // Add cursor arrow div at the end of the numbering pages
	        var cursorDiv = jQuery('div.gsc-cursor');
	        jQuery('<div><img src="'+Drupal.settings.basePath
	        		+'sites/all/themes/imodium/images/bulletBigblue.gif"/></div>')
	        		.addClass('jnj-google-search-cursor_arrow')
	        		.css('display','inline')
	        		.css('cursor','pointer')
	        		.appendTo(cursorDiv);
	        
	        // add click event on arrow cursor to go to the next result page
	        jQuery('div.jnj-google-search-cursor_arrow',cursorDiv).click( function(e) {
	        	var selectedPageCursor = jQuery('.gsc-cursor-current-page',cursorDiv);
	        	selectedPageCursor.next().trigger('click');
	        });
	        
	        // clone gsc-cursor-box (numbering pages) on top of results
	        var topcursor = jQuery("#jnj-google-search-top-cursor");
	        if(topcursor.length==0) {
	            jQuery('<div></div>').attr('id','jnj-google-search-top-cursor')
	            .css('display','block')
	            .addClass('gsc-results')
	            .prependTo('#jnj-google-search-results');
	        } else {
	           topcursor.html('');
	        }
	        jQuery("div.gsc-cursor-box").clone(true).appendTo('#jnj-google-search-top-cursor');
	        // problem: clone(true) would not clone the google events, it's not jquery... so we stay without the 'true' in clone
	        // and connect each top number click on his related bottom number link
	        var toplinks = jQuery('.gsc-cursor-page',jQuery("div.gsc-cursor-box",jQuery('#jnj-google-search-top-cursor')));
	        var bottomlinks = jQuery('.gsc-cursor-page',jQuery("div.gsc-cursor-box",jQuery('div.gsc-expansionArea')));
	        for(var i=0;i<bottomlinks.length;i++) {
	            var btmlnk = bottomlinks.get(i);
	            var toplnk = toplinks.get(i);
	            var $toplnk = jQuery(toplnk);
	            // trick, with jquery data, the DOM element btmlnk (one of the page link div) as now a key 'brother'
	            // and in this key there's the related bottom element
	            jQuery.data(toplnk,'brother',btmlnk);
	            $toplnk.click(function(e) {
	                // second part ofd the trick, inside to top element event we get the bottom related dom element and trigger his click
	                var brother = jQuery.data(this,'brother');
	                jQuery(brother).trigger('click');
	            });
	        }
	        // prevent any float right problem with gsc-cursor-box (numbering pages)
	        jQuery('<div class="clearall"></div>').insertAfter(jQuery("div.gsc-cursor-box"));
	    }    
    }
});

// replace any occurence of direct links to /life-balance-brought-you-imodium with the life popup
;jQuery(document).ready(function(){
   jQuery('a[href$="/life-balance-brought-you-imodium"]',jQuery('div.headerButton')).click(function(e) {
       e.preventDefault();
       window.open(jQuery(this).attr('href'),'popup','width=840,height=840,menubar=off,top=0,left=0');
       return false;
   });
});
