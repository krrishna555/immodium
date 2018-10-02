<?php

/**
 * Adding or modifying variables before page render
 */
function phptemplate_preprocess_page(&$vars) {
  // Page change based on node->type
  if(isset($vars['node'])) {
      $vars['template_files'][] = 'page-'.str_replace('_','-',$vars['node']->type);
  }
}

/**
 * Implementation of hook_preprocess_TEMPLATE().
 */
function imodium_preprocess_content_field(&$variables) {
  $node = $variables['node'];

  switch ($variables['field_name']) {

    // Those fields are textfield, but we would them to be slightly less
    // filtered than a check_plain().
    case 'field_title':
    case 'field_page_link':
      foreach ($variables['items'] as &$item) {
        if (!$item['empty']) {
          $item['view'] = filter_xss_admin($item['value']);
        }
      }
      break;

    // Field page generate a link, if this particular link has a title
    // attached (this title is the field_page_link as I speak), we have
    // to use it instead of the node title.
    case 'field_page':
      // Get first element (the only one) and attempt to load the node.
      $target_node = node_load($variables['items'][0]['nid']);
      if ($target_node) {
        $title = NULL;
        // Check we have an user set arbitrary title for link instead of
        // node title for the link.
        if (isset($node->field_page_link) && !empty($node->field_page_link[0]['value'])) {
          // No need here to take the 'safe' value, because our own theming
          // function will escape it.
          $title = filter_xss_admin($node->field_page_link[0]['value']);
        }
        else {
          // Same notice as upper.
          $title = $target_node->title;
        }
       // echo $title."<br />";
        //echo "<pre>"; print_r($variables); echo "</pre>"; 
        // Override the current item view using our own function.
        $variables['items'][0]['view'] = imodium_custom_button($title, 'node/' . $target_node->nid, FALSE);
      }
      break;
    case 'field_destination_url':
    		// Get first element (the only one) and attempt to load the node.
      		$target_node = node_load($variables['items'][0]['nid']);
      
          if (isset($node->field_page_link) && !empty($node->field_page_link[0]['value'])) {
	          // No need here to take the 'safe' value, because our own theming
	          // function will escape it.
	          $title = filter_xss_admin($node->field_page_link[0]['value']);
	        }
	        else {
	          // Same notice as upper.
	          $title = $target_node->title;
	        }
	        
	        $imodium_custom_button	=	imodium_custom_button($title, $node->field_destination_url[0]['value'], FALSE);
	        $variables['items'][0]['view'] = $imodium_custom_button;
    	break;
    // Field promo needs to arbitrary set different node templates depending
    // on the element count and each node position.
    case 'field_promo':
      // Get nodes.
      $first = TRUE;
      foreach ($variables['items'] as &$item) {
        if (!$item['empty']) {
          $node = node_load($item['nid']);
          // Alter the node object to add a template suggestion.
          // See imodium_preprocess_node()
          if ($first) {
            // First promo as a different template than all others. We need to
            // differenciate those.
            $node->imodium_template = 'node-promo-big';
            $first = FALSE;
          }
          // Small is the default node template, we do not need to set a
          // specific template here.
          $item['view'] = node_view($node);
        }
      }
      break;

    // Field promo needs to arbitrary set different node templates depending
    // on the element count and each node position.
    case 'field_homepage_promo':
      foreach ($variables['items'] as &$item) {
        if (!$item['empty']) {
          $node = node_load($item['nid']);
          $node->imodium_template = 'node-promo-homepage';
          $item['view'] = node_view($node);
        }
      }
      break;

    // Field promo needs to arbitrary set different node templates depending
    // on the element count and each node position.
    case 'field_fact':
      // Get nodes.
      $first = TRUE;
      foreach ($variables['items'] as &$item) {
        if (!$item['empty']) {
          $node = node_load($item['nid']);
          // Alter the node object to add a template suggestion.
          // See imodium_preprocess_node()
          if ($first) {
            // First promo as a different template than all others. We need to
            // differenciate those.
            $node->imodium_template = 'node-fact-big';
            $first = FALSE;
          }
          // Small is the default node template, we do not need to set a
          // specific template here.
          $item['view'] = node_view($node);
        }
      }
      break;

    // For promo (and only promo) content type that uses the field_image field,
    // we will link the image to the 'field_page_link' if not empty.
    case 'field_image':
      if ($node->type == 'promo' && isset($node->field_page[0]['nid']) && !empty($node->field_page[0]['nid'])) {
        foreach ($variables['items'] as &$item) {
          if (!$item['empty']) {
            $item['view'] = '<a href="' . url('node/' . $node->field_page[0]['nid']) . '">' . $item['view'] . '</a>';
          }
        }
      }
      break;
  }
}

/**
 * Implementation of hook_preprocess_TEMPLATE().
 */
function imodium_preprocess_node(&$variables) {
  $node = $variables['node'];
  
  // allow template node-<node_id>.tpl.php
  $variables['template_files'][] = 'node-'. $node->nid;
  
  if (isset($node->content['body']['#value'])) {
    $variables['body'] = $node->content['body']['#value'];
  }

  // Add template suggestion based on other functions alteration.
  // This is a generic
  if ($node->imodium_template) {
    $variables['template_files'][] = $node->imodium_template;
  }

  // This will help us to set up some classes in template.
  if ($node->type == 'fact') {
    $variables['true'] = $node->field_validity[0]['value'] == 'true';
  }

  // Build some usefull control variables for blankpage node template.
  else if ($node->type == 'blankpage') {
    // Check if there is the flash movie field filled in.
    $flash_movie = $node->field_flash_movie[0]['value'];
    if ($flash_movie) {
      $variables['title_field'] = theme('imodium_flash_player', array('type' => $flash_movie));
      $variables['title_field_mode'] = 'flash';
    }
  }
  // Build some usefull control variables for page node template.
  else if ($node->type == 'page') {
    $variables['right_content'] = '';
    $show_right_panel = FALSE;

    // Check if there is the flash movie field filled in.
    $flash_movie = $node->field_flash_movie[0]['value'];
    // Compute a player identifier for the page.
    $player_id = uniqid('node-flash-player-');

    // Count videos and render them. The first one will be hidden by the
    // JavaScript and CSSS if needed, in order to do full video rotation.
    $videos = 0;
    if (isset($node->field_videos)) {
      foreach ($node->field_videos as &$item) {
        if (!empty($item['view'])) {
          if (!$item['empty']) {
            $smallMode= ('smallvideo'==$flash_movie);
            $video = node_load($item['nid']);
            if($smallMode) {
                $item['view']= theme_imagecache('video-thumbnail-small'
                  ,$video->field_image[0]['filepath']
                  , $video->field_image[0]['filename']
                  , $video->field_image[0]['filename']);
            }
            $variables['right_content'] .= theme('imodium_flash_player_next_link', array(
              'id' => $videos,
              'content' => $item['view'],
              'player_id' => $player_id,
              'format' => ($smallMode)? 'small' : 'classic',
              'attributes' => array(
                'class' => ($videos == 0 ? 'active' : ''),
                'title' => $video->title,
                'smalltitle' => ($smallMode)? $video->field_title[0]['value'] : $video->title,
              ),
            ));
            $videos++;
          }
        }
      }
    }

    // User may have specified a specific flash movie here. Let him display it
    // instead of the flash player.
    if ($flash_movie && 'smallvideo'!=$flash_movie) {
      $variables['title_field'] = theme('imodium_flash_player', array('type' => $flash_movie));
      $variables['title_field_mode'] = 'flash';
    }
    // Select video or image for title. If we have at least one video? the
    // first one (or only) one goes into the title.
    else if ($videos >= 1) {
      // Render the video field using our custom theme function.
      if ($flash_movie && 'smallvideo'==$flash_movie) {
        $variables['title_field'] = theme('imodium_flash_player', array(
          'type' => $flash_movie,
          'uniq_id' => $player_id,
          'flashvars' => array('xmlPath' => url('node/' . $node->nid . '/video.xml')),
        ));
      } else {
        $variables['title_field'] = theme('imodium_flash_player', array(
          'uniq_id' => $player_id,
          'flashvars' => array('xmlPath' => url('node/' . $node->nid . '/video.xml')),
        ));
      }
      $variables['title_field_mode'] = 'video';
    }
    else if (isset($variables['field_title_image_rendered']) && !empty($variables['field_title_image_rendered'])) {
      $variables['title_field'] = $variables['field_title_image_rendered'];
      $variables['title_field_mode'] = 'image';
    }
    else {
      $variables['title_field_mode'] = NULL;
    }

    // If we have less than 3 video, we need to put the fallback promo
    // field instead at this position.
    // The first one is the title video, the second one is displayed as
    // a thumbnail, so we need to put the fallback promo to avoid empty
    // space here.
    if ($videos < 3 && isset($variables['field_fallback_promo_rendered']) && !empty($variables['field_fallback_promo_rendered'])) {
      $variables['right_content'] .= $variables['field_fallback_promo_rendered'];
      $show_right_panel = TRUE;
    }

    if ($show_right_panel || $videos > 1 || !empty($variables['field_related_rendered'])) {
      $variables['show_right_panel'] = TRUE;
    }
    else {
      $variables['show_right_panel'] = FALSE;
    }
  }
}

/**
 * Override.
 *
 * Resets global variable that helps item count.
 */
function imodium_menu_tree($tree) {
  global $imodium_menu_count;
  $imodium_menu_count = 0;
  return '<ul class="menu">'. $tree .'</ul>';
}

/**
 * Implementation of hook_theme().
 */
function imodium_theme() {
  $items = array();
  $items['imodium_flash_player'] = array(
    'arguments' => array('options' => array()),
    'template' => 'flash-player',
  );
  $items['contact_mail_page'] = array(
    'arguments' => array('form' => $form),
  );
  $items['imodium_flash_player_next_link'] = array();
  return $items;
}

function imodium_contact_mail_page($form) {
    $output = drupal_render($form);
    $output = '<div class="footernode"><div class="clearall"></div><div class="descContainer descContainerExpandNoLeft">'.$output.'</div><div class="clearall"></div></div>';
    return $output;
}

/**
 * Implementation of hook_theme_registry_alter().
 *
 * We will reroute flash video here to display it with our own template file.
 */
function imodium_theme_registry_alter(&$theme_registry) {
  $theme_registry['video_flv'] = array(
    'arguments' => array('video' => NULL, 'node' => NULL),
    'template' => 'video-flv',
  );
}

/**
 * Implementation of template_preprocess_TEMPLATE().
 *
 * @see scripts/front-flash.js
 * @see imodium_imodium_flash_player_link()
 */
function imodium_preprocess_imodium_flash_player(&$variables) {
  // Hard-coded default values.
  $variables['scale'] = 'showall'; // 'showall' or 'noscale'.
  $variables['wmode'] = 'transparent'; // 'window' or 'transparent'.
  $variables['swliveconnect'] = 'true'; // Usage of js for embed objects.

  // Merge the options.
  $variables += $variables['options'];

  // Default variables.
  $defaults = array(
    'width' => 670,
    'height' => 300,
    'autoplay' => 0,
    'rotation' => FALSE,
    // FIXME: jQuery behavior has been disabled. This was a good idea but
    // while applying it we loose the Flash exposed functions, and can't
    // find out why. If whenever we find out, this would be great to fix it.
    'no_behavior' => TRUE,
  );
  // Default flashvars.
  $defaults_flashvars = array(
    // These two should always be set by caller.
    'file' => NULL,
    'xmlPath' => NULL,
    'swliveconnect' => TRUE,
  );

  // Give a unique id to the object.
  if (!isset($variables['uniq_id'])) {
    $variables['uniq_id'] = uniqid('flash-');
  }

  // Allow the theme function to use a different swf file. Working remains the
  // same, all flashvars will be sent to the swf as well.
  switch ($variables['options']['type']) {
    case 'smallvideo':
      $variables['player_path'] = base_path() . path_to_theme() . '/data/mediaPlayer.swf';
      $variables['player_id'] = base_path() . path_to_theme() . '/data/mediaPlayer';
      $defaults['width'] = 480;
      $defaults['height'] = 214;
      // Also set Drupal.settings.
      $settings = array();
      drupal_add_js(array('mediaPlayer' => $settings), 'setting');
      break;
    case 'travelhostspots':
      $variables['no_behavior'] = TRUE;
      $variables['player_path'] = base_path() . path_to_theme() . '/data/IMM004_map_rollovers_07_NEW.swf';
      $variables['player_id'] = base_path() . path_to_theme() . '/data/IMM004_map_rollovers_07_NEW';
      $defaults = array(
        'width' => 670,
        'height' => 300,
        'autoplay' => 0,
      );
      $defaults_flashvars['territory'] = 'uk';
      $defaults_flashvars['xmlPath'] = base_path() . path_to_theme() . '/data/travel_hotspots_localisation.xml';
      break;
      
    case 'takethelifebalance':
      $variables['no_behavior'] = TRUE;
      $variables['player_path'] = base_path() . path_to_theme() . '/data/lifebalance.swf';
      $variables['player_id'] = base_path() . path_to_theme() . '/data/lifebalance';
      $defaults = array(
        'width' => 750,
        'height' => 650,
        'autoplay' => 1,
        'quality' => 'high',
        'align' => 'middle',
        'loop' => 1,
        'bgcolor' => '#e27177',
        'allowFullScreen' => 'false',
      );
      //$defaults_flashvars['territory'] = 'uk';
      break;
      
    case 'video':
    default:
      $variables['player_path'] = base_path() . path_to_theme() . '/data/mediaPlayer.swf';
      $variables['player_id'] = base_path() . path_to_theme() . '/data/mediaPlayer';
      // Also set Drupal.settings.
      $settings = array();
      drupal_add_js(array('mediaPlayer' => $settings), 'setting');
      break;
  }

  // Set defaults. Whenever the flash being used is different, adapt those
  // depending on the swf defaults.
  $variables += $defaults;

  // Flash parameters.
  // Build flashvars, we are going to build it in three different ways, this
  // could be useful later if we change the embed method.
  $variables['flashvars'] = (array)$variables['options']['flashvars'];
  // Set defaults in case they are not being overriden.
  $variables['flashvars'] += $defaults_flashvars;
  // Override the autoplay whatever happens.
  $variables['flashvars_json'] = drupal_to_js($variables['flashvars']);
  $variables['flashvars_get'] = drupal_query_string_encode($variables['flashvars']);
}

/**
 * Build a button for next content switch.
 *
 * @see scripts/front-flash.js
 * @see imodium_preprocess_imodium_flash_player()
 */
function imodium_imodium_flash_player_next_link($options = array()) {
  // We need a player identifier for the link to work.
  if (!isset($options['player_id'])) {
    watchdog('imodium', "Attempt to display a flash player rotation link without a player identifier.");
    return $options['content'];
  }
  if (!isset($options['id'])) {
    watchdog('imodium', "Attempt to display a flash player rotation link without a link identifier.");
    return $options['content'];
  }
  if (!isset($options['content'])) {
    watchdog('imodium', "Attempt to display a flash player rotation link without content.");
    return '';
  }

  // Override rel attribute with player unique identifier, it will allow the
  // JavaScript to find the associated player in the DOM.
  $options['attributes']['rel'] = $options['player_id'];
  // Set classes for the Javascript to find our links in the DOM.
  if (!isset($options['attributes']['class'])) {
    $options['attributes']['class'] = '';
  }
  $options['attributes']['class'] .= ' flash-rotation-link';

  // Set a 'div' here instead of a 'a' tag will allow us, in some cases, to put
  // complex html structure which will remain (x)html valid. It will avoid some
  // browser to attempt to correct the rendered html by themselves (such as
  // firefox) which can break the JavaScript and CSS theming.
  $tag = isset($options['attributes']['href']) ? 'a' : 'div';

  // We need at least an id, to fully support JS functions signature.
  $options['attributes']['id'] = 'flash-rotation-link-' . $options['id'];
  // Built the final result and go for it.
  $string = '<' . $tag . drupal_attributes($options['attributes']) . '>' . $options['content'];
  if (isset($options['attributes']['smalltitle']) && isset($options['format']) && 'small' == $options['format']) {
      $string .= '<span class="video-thumb-title">' . $options['attributes']['smalltitle'] . '</span>';
  }
  $string .= '</' . $tag . '>';
  return $string;
}

/**
 * Ugly, but since the swftools module does not want to work as we would expect
 * we will override this function and put our own code for video integration.
 *
 * At least, it will allow us to use the player we want to use.
 *
 * This function generates from URLs we know that exists. Those URLs are being
 * provided by the jnj_imodium module. See this module code to understand what
 * we really do with those.
 */
function imodium_preprocess_video_flv(&$variables) {
  $options = array();
  // Proceed to some URL replacements, so the flash won't yell at us.
  $options['video_path'] = base_path() . strtr($variables['video']->filepath, array(' ' => '%20'));
  $options['video_id'] = base_path() . substr($variables['video']->filepath, 0, strrchr($variable['video']->filepath, '.'));
  $options['autoplay'] = $variables['video']->autoplay;
  $options['width'] = $variables['video']->player_width;
  // 20 is the toolbar height.
  $options['height'] = $variables['video']->player_height; /* + 20;*/
  // Flash parameters.
  // Build flashvars, we are going to build it in three different ways, this
  // could be useful later if we change the embed method.
  $options['flashvars'] = array(
    'autostart' => $variables['autoplay'],
    'file' => $variables['video_path'],
    'xmlPath' => url('node/' . $variables['node']->nid . '/video.xml'),
  );

  $variables['content'] = theme('imodium_flash_player', $options);
}


/**
 * Override.
 *
 * Adds an item count in menu items.
 */
function imodium_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  global $imodium_menu_count;
  static $preg_forward;

  if (!empty($extra_class)) {
    $class .= ' '. $extra_class;
  }
  if ($in_active_trail) {
    $class .= ' active-trail';
  }

  // Add a class to the "forward" module form so we can display it in a custom
  // JavaScript overlay dialog instead of keeping it a real link.
  if (!isset($preg_forward)) {
    // Nice, isn't it? :D THIS IS MAGIC!
    $preg_forward = '/href="(|' . strtr(base_path(), array('/' => '\/')) . ')(\?q=|)forward/';
  }
  // See scripts/imodium.js for overlay handing.
  if (preg_match($preg_forward, $link)) {
    $class .= ' overlay-link';
  }

  return '<li class="'. ($class ? $class . ' ' : '') .'item-' . (++$imodium_menu_count) . '">'. $link . $menu ."</li>\n";
}

/**
 * Implementation of hook_preprocess_page().
 */
function imodium_preprocess_page(&$variables) {
  $header_menu = '<ul class="searchButtons">';
  $i = 1;
  foreach (menu_navigation_links('menu-search-box', 0) as $item) {
    $item['attributes']['class'] = 'item-' . $i;
    $item['attributes']['title'] = $item['attributes']['title'] ? $item['attributes']['title'] : $item['title'];
    $header_menu .= '<li ' . drupal_attributes($item['attributes']) . '>' . l($item['title'], $item['href']) . '</li>';
    $i++;
  }
  $header_menu .= '</ul>';
  $variables['header_menu'] = $header_menu;
}

/**
 * Override.
 */
function imodium_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\"><div class=\"messages-bottom\"><div class=\"messages-content\">\n";
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>'. $message ."</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div></div></div>\n";
  }
  return $output;
}

/**
 * Override.
 *
 * Disable HTML escaping while rendering links.
 */
function imodium_menu_item_link($link) {
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }

  // This is MAGIC!
  $link['localized_options']['html'] = TRUE;

  return l($link['title'], $link['href'], $link['localized_options']);
}

/**
 * Override.
 */
function imodium_jnj_overlay_button($text) {
  return imodium_custom_button($text);
}

/**
 * Render an Imodium button link.
 */
function imodium_custom_button($text, $path = '', $filter = TRUE) {
  if ($filter) {
    $text = filter_xss_admin($text);
  }
  $url = $path ? url($path) : '#';
  return '<div class="headerButton"><a href="' . $url . '" class="call2ActionBtn" title="' . str_replace(array('<sup>','</sup>'),array('',''),$text) . '"><span>' . $text . '</span></a></div>';
}

/**
 * Render a nice menu submenu for dropdown.
 */
function imodium_nice_menus_submenu($children) {
  global $last_menu_split;
  return '<div class="dropdown-menu-child dropdown-menu-inner columns-' . $last_menu_split . '" style="display: none;"><div class="dropdown-menu-left"></div><div class="dropdown-menu-right"></div><ul>' . $children . '</ul></div><div class="clearall"></div>';
}

/**
 * Override of nice menu helper in order to build more complex drop down menu
 * allowing us a cleaner theming.
 */
function imodium_nice_menus_build($menu, $depth = -1, $trail = NULL, $child = FALSE) {
  $output = '';

  // Last menu split will tell us the number of columns the previous execution
  // generated while rendering children.
  global $last_menu_split;

  // Prepare to count the links so we can mark first, last, odd and even.
  $index = 0;
  $count = 0;
  $split = -1;
  $splited = 1;
  foreach ($menu as $menu_count) {
    if (!$menu_count['link']['hidden']) {
      $count++;
    }
  }

  // Compute menu split.
  // Change this value to alter the split behavior.
  if ($child && $count > 4) {
    $split = floor($count / 2);
  }

  // Split counter.
  $i = 0;

  // Get to building the menu.
  foreach ($menu as $menu_item) {
 
	 //Specifically hide Love Summer page from top menu - Client's requirement. 	
  	if ($menu_item['link']['link_title']=='Home'){
  		if (isset($menu_item['below']['49950 Love Summer 1835']))
  		{
  			unset($menu_item['below']['49950 Love Summer 1835']);
  		}
  	}
  	
    $mlid = $menu_item['link']['mlid'];

    // Check to see if it is a visible menu item.
    if (!isset($menu_item['link']['hidden']) || $menu_item['link']['hidden'] == 0) {
      // Check our count and build first, last, odd/even classes.
      $index++;
      $children = '';

      // Add usefull classes.
      $classes = array();
      $classes[] = $child ? 'dropdown-menu-child-li' : 'dropdown-menu';
      if ($index == 1 || $index == $split + 1) {
        $classes[] = 'first';
      }
      $classes[] = $index % 2 == 0 ? 'even' : 'odd';
      if ($index == $count || $index == $split) {
        $classes[] = 'last';
      }

      if ($trail && in_array($mlid, $trail)) {
        $classes[] = 'active-trail';
        // Set the active class to active trail menu items.
        // Ugly but will avoid PHP notices.
        if (isset($menu_item['link']['localized_options']['attributes']['class'])) {
          $menu_item['link']['localized_options']['attributes']['class'] .= ' active';
        }
        else {
          $menu_item['link']['localized_options']['attributes']['class'] = 'active';
        }
      }

      // If it has children build a nice little tree under it.
      if ((!empty($menu_item['link']['has_children'])) && (!empty($menu_item['below'])) && $depth != 0) {
        // Keep passing children into the function 'til we get them all.
        $children = theme('nice_menus_build', $menu_item['below'], $depth, $trail, TRUE);

        // Set the class to parent only of children are displayed.
        $classes[] = ($children && ($menu_item['link']['depth'] <= $depth || $depth == -1)) ? 'menuparent ' : '';

        // Check our depth parameters.
        if ($menu_item['link']['depth'] <= $depth || $depth == -1) {
          // Build the child UL only if children are displayed for the user.
          if ($children) {
            $children = imodium_nice_menus_submenu($children);
          }
          else {
            // Foo container for JS to work flawlessly.
            $children = '<span class="dropdown-menu-child dropdown-menu-inner"></span>';
          }
        }
        else {
          // Foo container for JS to work flawlessly.
          $children = '<span class="dropdown-menu-child dropdown-menu-inner hide"></span>';
        }
      }

      $output .= '<li class="' . implode(' ', $classes) .'">' . theme('menu_item_link', $menu_item['link']) . $children . '</li>';

      if ($split > 0 && ($i == $split - 1) && $count > $split) {
        $splited++;
        // See imodium_nice_menus_submenu()
        $output .= '</ul><ul>';
      }

      $i++;
    }
  }

  // For parent.
  $last_menu_split = $splited;

  return $output;
}
/***
 * 
 * Removing Colon from Form Element Label
 * 
 */
function imodium_form_element($element, $value) {
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  $output = '<div class="form-item"';
  if (!empty($element['#id'])) {
    $output .= ' id="'. $element['#id'] .'-wrapper"';
  }
  $output .= ">\n";
  $required = !empty($element['#required']) ? '<span class="form-required" title="'. $t('This field is required.') .'">*</span>' : '';

  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $output .= ' <label for="'. $element['#id'] .'">'. $t('!title !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</label>\n";
    }
    else {
      $output .= ' <label>'. $t('!title !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</label>\n";
    }
  }

  $output .= " $value\n";

  if (!empty($element['#description'])) {
    $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}
/**
 * Customizing the Pager to add HTML attribue
 * Format a link to a specific query result page.
 *
 * @param $page_new
 *   The first result to display on the linked page.
 * @param $element
 *   An optional integer to distinguish between multiple pagers on one page.
 * @param $parameters
 *   An associative array of query string parameters to append to the pager link.
 * @param $attributes
 *   An associative array of HTML attributes to apply to a pager anchor tag.
 * @return
 *   An HTML string that generates the link.
 *
 * @ingroup themeable
 */
function imodium_pager_link($text, $page_new, $element, $parameters = array(), $attributes = array()) { 
  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query[] = drupal_query_string_encode($parameters, array());
  }
  $querystring = pager_get_querystring();
  if ($querystring != '') {
    $query[] = $querystring;
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next3232zsdsad ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    else if (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  return l($text, $_GET['q'], array('attributes' => $attributes, 'html' => TRUE, 'query' => count($query) ? implode('&', $query) : NULL));
}

/**
 * Format a query pager.
 *
 * Menu callbacks that display paged query results should call theme('pager') to
 * retrieve a pager control so that users can view other results.
 * Format a list of nearby pages with additional query results.
 *
 * @param $tags
 *   An array of labels for the controls in the pager.
 * @param $limit
 *   The number of query results to display per page.
 * @param $element
 *   An optional integer to distinguish between multiple pagers on one page.
 * @param $parameters
 *   An associative array of query string parameters to append to the pager links.
 * @param $quantity
 *   The number of pages in the list.
 * @return
 *   An HTML string that generates the query pager.
 *
 * @ingroup themeable
 */
function imodium_pager($tags = array(), $limit = 10, $element = 0, $parameters = array(), $quantity = 9) {  
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.
  
/*
 * 
 * Modified By Faruq Shaik on June 10th 2011
 * Customizing Pager for only Search Results
 */  
  
if(arg(0) == 'search') {
	$src = base_path() . path_to_theme() . '/images/bulletBigblue.gif';
	$img = "<img src=$src alt='next'>";
  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('')), $limit, $element, $parameters);
  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('')), $limit, $element, 1, $parameters);
  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : $img), $limit, $element, 1, $parameters);
  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('')), $limit, $element, $parameters);
}else{
  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('« first')), $limit, $element, $parameters);
  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('‹ previous')), $limit, $element, 1, $parameters);
  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : t('next ��')), $limit, $element, 1, $parameters);
  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('last »')), $limit, $element, $parameters);	
}
  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => 'pager-first',
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => 'pager-previous',
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => 'pager-ellipsis',
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_previous', $i, $limit, $element, ($pager_current - $i), $parameters),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => 'pager-current',
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_next', $i, $limit, $element, ($i - $pager_current), $parameters),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => 'pager-ellipsis',
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => 'pager-next',
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => 'pager-last',
        'data' => $li_last,
      );
    }
    return theme('item_list', $items, NULL, 'ul', array('class' => 'pager'));
  }
}

function imodium_box($title, $content, $region = 'main') {
  if ($title == 'Your search yielded no results')
  {
    //$title = 'Sorry, we couldn\'t find what you were looking for';
    $content = '<div class="no-results-message">Sorry, no results.</div>';
     }
  $output = '<div class="no-results-result"><div class="search-snippet">'. $content .'</div></div>';
  return $output;
}