<?php
/*
  Plugin Name: Metafluidics Site MU-Plugins
  Description: Powers the customization of the Metafluidics Site
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

// post types
require_once('metafluidics/post-types/device.php');

// taxonomies
require_once('metafluidics/taxonomies/hardware.php');
require_once('metafluidics/taxonomies/keywords.php');
require_once('metafluidics/taxonomies/materials.php');
require_once('metafluidics/taxonomies/technology.php');
require_once('metafluidics/taxonomies/feature-status.php');

// functions
require_once('metafluidics/functions/users.php');
require_once('metafluidics/functions/media.php');
require_once('metafluidics/functions/likes.php');
require_once('metafluidics/functions/downloads.php');

// analytics
require_once('metafluidics/analytics/views.php');
