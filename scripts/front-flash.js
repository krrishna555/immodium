/**
 * @file
 * Front page flash player handling.
 */

/**
 * Hold some usefull variables and functions.
 */
;var homeFlashPlayer = {
  /**
   * Tells if the global object has been inited or not.
   */
  _inited: false,

  /**
   * Current content being displayed.
   */
  _currentItem: 0,

  /**
   * Rotation delai, in seconds.
   */
  _rotationDelay: 5,

  /**
   * Rotation queue.
   */
  _enableRotation: false,

  /**
   * Is queue started?
   */
  _started: false,

  /**
   * jQuery queue being used.
   */
  _queue: null,

  /**
   * Keep a reference to control links for faster access.
   */
  _links: [],

  /**
   * Function that really trigger the rotation.
   */
  _queueCallback: function() {
    //console.log('queue callback');
    if (this._enableRotation && this._started) {
      //console.log('queue callback  [OK]');
      // This algorithm is able to do the rotation among a fixed list of links
      // that was found at page init. We keep a current link pointer and each
      // time we need to rotate we re-compute the next link to click. This
      // allows us to be able to change this internal pointer at the real user
      // physical click event so the order will always remains consistent.
      var i = this._currentItem;
      i = ++i % this._links.length;
      // Click link.
      this._links[ i ].trigger('click-callback');
      // Reset global object after trigger and run the new iteration.
      this._currentItem = i;
    }
  },

  /**
   * Queue runner.
   */
  _startQueue: function() {
    // Ensure the setInterval() is called only once at init time.
    if (this._enableRotation && !this._inited) {
      setInterval(function() {
        // In this callback, check if the rotation queue should actually be
        // running or not. In paused state, the setInterval() will continue
        // to run, but the _queueCallback will execute his content only if the rotation is running.
        if (homeFlashPlayer._enableRotation) {
          homeFlashPlayer._queueCallback();
        }
      }, this._rotationDelay * 1000);
    }
  },

  /**
   * Start rotation timer.
   */
  jstartTimer: function() {
    //console.log('internal jstartTimer');
    if (this._enableRotation) {
      this._started = true;
    }
  },

  /**
   * Stop rotation timer.
   */
  jpauseTimer: function() {
    //console.log('internal jpauseTimer');
    this._started = false;
  },

  /**
   * Get the flash player.
   */
  _getFlashMediaPlayer: function( playerId, context ) {
    context = context || null;
    var ret = null;
    // Attempt to find the right embed tag. We identify id using the given
    // player id which was set on the link themselves.
    var mymov = jQuery("embed[name=" + playerId + "]:first", context);
    if (mymov.length > 0) {
      ret = mymov.get(0);
    }
    else {
      // In case the embed tag wasn't found, attempt with the object tage. Some
      // browsers don't support the embed tag but will have an object tag
      // instead.
      var mymov = jQuery("object[id=" + playerId + "]:first", context);
      if (mymov.length > 0) {
        ret = mymov.get(0);
      }
    }
    // In case we have a ret, we need to check if the current embed or object
    // has exposed the function we need.
    // Browsers that don't support JS will still have the object tag, so this
    // test is necessary.
    // In some weird cases, when doing a hard reload (emptying the cache before
    // loading the page), the flash player won't fully initialize, there is no
    // way to find out why, which makes this test even more necessary.
    if (!ret || typeof ret.nextContent == 'undefined') {
      return false;
    }
    else {
      return ret;
    }
  },

  /**
   * Content rotation function.
   */
  jnextContent: function( videoId, playerId ) {
    //console.log('internal jnextContent');
    // Fetch the node in the DOM.
    var node = jQuery('#flash_' + videoId);
    if (node.length) {
      // Hide all others.
      jQuery('.banner_flash').hide();
      node.show();
    }
    // Whatever happens, mess with the flash player.
    var flashmediaplayer = this._getFlashMediaPlayer( playerId );
    if (false != flashmediaplayer) {
      flashmediaplayer.nextContent( videoId );
    }
  }
};

/**
 * Add a behavior for the media player links handling.
 * 
 * We use the window load event instead of the classic Drupal behavior to ensure
 * the flash player has been fully init before starting to rotate. We will init
 * it only once anyway and no new DOM elements will ever need this behavior.
 */
jQuery(window).load(function() {
  // Global init.
  if (!homeFlashPlayer._inited) {
    if (Drupal.settings.mediaPlayer) {
      // Enable rotation if set.
      if (Drupal.settings.mediaPlayer.enableRotation) {
        if (Drupal.settings.mediaPlayer.rotationDelay) {
          homeFlashPlayer._rotationDelay = parseInt( Drupal.settings.mediaPlayer.rotationDelay );
        }
        homeFlashPlayer._enableRotation = true;
        homeFlashPlayer._started = true;
        homeFlashPlayer._startQueue();
      }
    }
    homeFlashPlayer._inited = true;
  }

  // Add our custom behavior to non already processed links.
  // here we find for example video thumbnails which get 'push on' to the player
  var i = 0, links = jQuery('.flash-rotation-link:not(flash-rotation-processed)');
  var $mainVideoTitle  = jQuery('#current-video-title');
  links.addClass('flash-rotation-processed')
    .unbind('click')
    .each(function() {
      // Prepare some variables.
      var $this = $(this),
          items = $this.attr('id').split('-'),
          id = parseInt( items[items.length - 1] ),
          playerId = $this.attr('rel'),
          // Keep the current i in scope. If we try to use the previous
          // variable, it will always remain the maximum value.
          current = i;
      // This is a custom event, that the real click callback will trigger. By
      // proceeding this way, we can add a slightly different behavior on real
      // user physical click that will stop the timer. Click events send by the
      // rotation queue callback won't stop the rotation.
      $this.bind('click-callback', function( event ) {
        // Mess up with classes.
        // will show all thumbs
        links.removeClass('active');
        // will hide th active played one
        $this.addClass('active');
        // handle video subtitles if any
        var $subtitle = $this.children("span.video-thumb-title");
        if ($subtitle.length > 0 && $mainVideoTitle.length > 0) {
            $mainVideoTitle.text($subtitle.text());
        }
        // Update the current item.
        homeFlashPlayer._currentItem = current;
        // Run the next content function.
        homeFlashPlayer.jnextContent( id, playerId );
      });
      if ( 0 == i ) {
          $this.trigger('click-callback');
      }

      // See comment on custom trigger above.
      $this.click(function( event ) {
        // Prevent defaults.
        event.preventDefault();
        event.stopPropagation();
        // Remove the rotation stuff.
        $this.trigger('click-callback');
        //console.log("xxxx Forcing a pauseTimer!!!");
        homeFlashPlayer.jpauseTimer();
        return false;
      });

      // Reference this links into the global object for faster access.
      homeFlashPlayer._links[i++] = $this;
    });
});

/**
 * Proxy function for Flash player.
 */
function startTimer() {
   //if (console) console.log("flash: startTimer() called");
  return homeFlashPlayer.jstartTimer();
}

/**
 * Proxy function for Flash player.
 */
function pauseTimer() {
   //if (console) console.log("flash: pauseTimer() called");
  return homeFlashPlayer.jpauseTimer();
}
/**
 * Proxy function for Flash player.
 */
function stopTimer() {
   //if (console) console.log("flash: stopTimer() called");
  return homeFlashPlayer.jstopTimer();
}
