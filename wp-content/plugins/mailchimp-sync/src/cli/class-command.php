<?php

namespace MC4WP\Sync\CLI;

use MC4WP\Sync\Users;
use WP_CLI, WP_CLI_Command;
use MC4WP\Sync\ListSynchronizer;

class Command extends WP_CLI_Command {

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = $GLOBALS['mailchimp_sync']->options;

		$this->users = new Users( 'mailchimp_sync_' . $this->options['list'], $this->options['role'] );
		$this->synchronizer = new ListSynchronizer( $this->options['list'], $this->users, $this->options );

		parent::__construct();
	}

	/**
	 * Synchronize all users (with a given role)
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * ## OPTIONS
	 *
	 * <role>
	 * : User role to synchronize
	 *
	 * ## EXAMPLES
	 *
	 *     wp mailchimp-sync all --role=administrator
	 *
	 * @synopsis [--role=<role>]
	 *
	 * @subcommand all
	 */
	public function all( $args, $assoc_args ) {

		$user_query_args = array();

		// allow overriding user role with --role
		// by default, the stored setting will be used.
		$user_role = ( isset( $assoc_args['role'] ) ) ? $assoc_args['role'] : null;
		if( ! is_null( $user_role ) ) {
			$user_query_args['role'] = $user_role;
		}

		// start by counting all users
		$users = $this->users->get( $user_query_args );
		$count = count( $users );

		WP_CLI::line( "$count users found." );

		// show progress bar
		$notify = \WP_CLI\Utils\make_progress_bar( __( 'Working', 'mailchim-sync'), $count );
		$user_ids = wp_list_pluck( $users, 'ID' );

		foreach( $user_ids as $user_id ) {
			$this->synchronizer->subscribe_user( $user_id );
			$notify->tick();
		}

		$notify->finish();

		WP_CLI::success( "Done!" );
	}

	/**
	 * Synchronize a single user
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * ## OPTIONS
	 *
	 * <user_id>
	 * : ID of the user to synchronize
	 *
	 * ## EXAMPLES
	 *
	 *     wp mailchimp-sync user 5
	 *
	 * @synopsis <user_id>
	 *
	 * @subcommand user
	 */
	public function user( $args, $assoc_args ) {

		$user_id = absint( $args[0] );

		$result = $this->synchronizer->subscribe_user( $user_id );

		if( $result ) {
			WP_CLI::line( sprintf( "User %d successfully synced!", $user_id ) );
		} else {
			WP_CLI::error( $this->synchronizer->error );
		}

	}

	/**
	 * @deprecated 1.4
	 * @subcommand sync-user
	 */
	public function sync_user( $args, $assoc_args ) {
		$this->user( $args, $assoc_args );
	}

	/**
	 * @deprecated 1.4
	 * @subcommand sync-all
	 */
	public function sync_all( $args, $assoc_args ) {
		$this->all( $args, $assoc_args );
	}

	/**
	* @subcommand process-queue
	*/
	public function process_queue( $args, $assoc_args ) {
		do_action( 'mailchimp_user_sync_run' );	
	}	
}
