<?php

namespace MC4WP\Sync;

use MC4WP_Queue as Queue;
use WP_CLI;

defined( 'ABSPATH' ) or exit;

// load autoloader (but only if not loaded already, for compat with sitewide autoloader)
if( ! function_exists( '_mailchimp_sync_update_groupings_to_interests' ) ) {
    require dirname( __FILE__ ) . '/vendor/autoload.php';
}

// load default filters
require_once __DIR__ . '/src/default-filters.php';

// instantiate plugin
$plugin = new Plugin();

// expose plugin in a global. YUCK!
$GLOBALS['mailchimp_sync'] = $plugin;

// default to null object
$list_synchronizer = null;
$users = null;
$queue = null;

// if a list was selected, initialise the ListSynchronizer class
if( ! empty( $plugin->options['list'] ) ) {

	// instantiate synchronizer
	$role =  $plugin->options['role'];
	$field_map = $plugin->options['field_mappers'];
	$users = new Users( 'mailchimp_sync_' . $plugin->options['list'], $role, $field_map );
	$list_synchronizer = new ListSynchronizer( $plugin->options['list'], $users, $plugin->options );
	$list_synchronizer->add_hooks();

	// if auto-syncing is enabled, setup queue and worker
	if( $plugin->options['enabled'] ) {

		// create a job queue
		$queue = new Queue( 'mc4wp_sync_queue' );

		$observer = new Observer( $queue, $users );
		$observer->add_hooks();

		// create a worker 
		$worker = new Worker( $queue, $list_synchronizer );
		$worker->add_hooks();
	}
}


// Webhook
if( ! is_admin() && $users instanceof Users ) {
	$webhook_listener = new Webhook\Listener( $users, $plugin->options['field_mappers'], $plugin->options['webhook']['secret_key'] );
	$webhook_listener->add_hooks();
}

// Ajax
if( defined( 'DOING_AJAX' ) && DOING_AJAX
	&& $list_synchronizer instanceof ListSynchronizer
	&& $users instanceof Users ) {
	$ajax = new AjaxListener( $list_synchronizer, $users  );
	$ajax->add_hooks();
}

// Admin
if( is_admin() ) {
	$admin = new Admin\Manager( $plugin->options, $list_synchronizer, $users, $queue );
	$admin->add_hooks();
}

// WP CLI Commands
if( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'mailchimp-sync', 'MC4WP\\Sync\\CLI\\Command' );
}
