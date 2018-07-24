<?php

namespace MC4WP\Sync;

use WP_User;
use WP_User_Query;
use Exception;

/**
 * Class UserRepository
 *
 * @package MC4WP\Sync
 * @property ListSynchronizer $synchronizer
 */
class Users {

	/**
	 * @var string
	 */
	private $meta_key = '';

	/**
	 * @var string
	 */
	private $role = '';

	/**
	 * @var array
	 */
	private $field_map = array();

	/**
	 * @var Tools
	 */
	private $tools;

	/**
	 * @param string $meta_key
	 * @param string $role
	 * @param array $field_map
	 */
	public function __construct( $meta_key, $role = '', $field_map = array() ) {
		$this->meta_key = $meta_key;
		$this->role = $role;
		$this->field_map = $field_map;

		$this->tools = new Tools();
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get( $args = array() ) {
		if( ! empty( $this->role ) ) {
			$args['role'] = $this->role;
		}

		$user_query = new WP_User_Query( $args );

		return $user_query->get_results();
	}

	/**
	 *
	 * @return int
	 */
	public function count() {
		$result = $this->get( array( 'fields' => 'id', 'count_total' => false ) );
		return count( $result );
	}

	/**
	 * @param string $mailchimp_id
	 *
	 * @return WP_User|null;
	 */
	public function get_user_by_mailchimp_id( $mailchimp_id ) {
		$args = array(
			'meta_key'     => $this->meta_key,
			'meta_value'   => $mailchimp_id,
		);

		return $this->get_first_user( $args );
	}

//	/**
//	 * @param string $email
//	 * @return WP_User|null
//	 */
//	public function get_user_by_email( $email ) {
//		$args = array(
//			'search' => $email,
//			'search_columns' => array( 'email' ),
//		);
//
//		return $this->get_first_user( $args );
//	}

	/**
	 * @return WP_User
	 */
	public function get_current_user() {
		return wp_get_current_user();
	}

	/**
	 * @param array $args
	 *
	 * @return null|WP_User
	 */
	public function get_first_user( array $args = array() ) {
		$args['number'] = 1;
		$users = $this->get( $args );

		if( empty( $users ) ) {
			return null;
		}

		return $users[0];
	}

	/**
	 * TODO: Run filter on result
	 *
	 * @return int
	 */
	public function count_subscribers() {
		global $wpdb;

		$sql = "SELECT COUNT(u.ID) FROM $wpdb->users u INNER JOIN $wpdb->usermeta um1 ON um1.user_id = u.ID";

		if( '' !== $this->role ) {
			$sql .= " AND um1.meta_key = %s";
			$sql .= " INNER JOIN $wpdb->usermeta um2 ON um2.user_id = um1.user_id WHERE um2.meta_key = %s AND um2.meta_value LIKE %s";

			$query = $wpdb->prepare( $sql, $this->meta_key, $wpdb->prefix . 'capabilities', '%%' . $this->role . '%%' );
		} else {
			$sql .= " WHERE um1.meta_key = %s";
			$query = $wpdb->prepare( $sql, $this->meta_key );
		}

		// now get number of users with meta key
		$subscriber_count = $wpdb->get_var( $query );
		return (int) $subscriber_count;
	}

    /**
     * @param $user_id
     * @return int
     */
	public function id( $user_id ) {
        if( $user_id instanceof WP_User ) {
            $user_id = $user_id->ID;
        }

        return $user_id;
    }

	/**
	 * @param $user
	 * @return WP_User
	 *
	 * @throws Exception
	 */
	public function user( $user ) {

		if( ! is_object( $user ) ) {
			$user = get_user_by( 'id', $user );
		}

		if( ! $user instanceof WP_User ) {
			throw new Exception( sprintf( 'Invalid user ID: %d', $user ) );
		}

		return $user;
	}

	/**
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public function should( WP_User $user ) {
		$sync = true;

		// if role is set, make sure user has that role
		if( ! empty( $this->role ) && ! in_array( $this->role, $user->roles ) ) {
			$sync = false;
		}

		/**
		 * Filters whether a user should be synchronized with MailChimp or not.
		 *
		 * @param boolean $sync
		 * @param WP_User $user
		 */
		return (bool) apply_filters( 'mailchimp_sync_should_sync_user', $sync, $user );
	}

    /**
     * @param int|WP_User $user_id
     */
    public function touch( $user_id ) {
        $user_id = $this->id( $user_id );
        update_user_meta( $user_id, 'mc4wp_sync_last_updated', date( 'c' ) );
    }


    /**
     * @param int $user_id
     * @param string $email_address
     */
    public function set_mailchimp_email_address( $user_id, $email_address ) {
        $user_id = $this->id( $user_id );
        update_user_meta( $user_id, 'mc4wp_sync_remote_email_address', $email_address );
    }

    /**
     * @param int $user_id
     */
    public function delete_mailchimp_email_address( $user_id ) {
        $user_id = $this->id( $user_id );
        delete_user_meta( $user_id, 'mc4wp_sync_remote_email_address' );
    }

    /**
     * @param int $user_id
     * @return string
     */
    public function get_mailchimp_email_address( $user_id ) {
        $user_id = $this->id( $user_id );
        $email_address = get_user_meta( $user_id, 'mc4wp_sync_remote_email_address', true );
        return is_string( $email_address ) ? $email_address : '';
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function is_synced( $user_id ) {
        // check for new email meta first
        $email_address = $this->get_mailchimp_email_address( $user_id );
        if( ! empty( $email_address ) ) {
            return true;
        }

        // then check old subscriber uid
        $subscriber_uid = $this->get_subscriber_uid( $user_id );
        return ! empty( $subscriber_uid );
    }

    /**
     * @param int $user_id
     * @return string
     */
    public function get_subscriber_uid( $user_id ) {
        $user_id = $this->id( $user_id );
        $subscriber_uid = get_user_meta( $user_id, $this->meta_key, true );
        return is_string( $subscriber_uid ) ? $subscriber_uid : '';
    }

	/**
	 * @param int $user_id
	 */
	public function set_subscriber_uid( $user_id, $subscriber_uid ) {
        $user_id = $this->id( $user_id );
		update_user_meta( $user_id, $this->meta_key, $subscriber_uid );
	}

	/**
	 * @param int $user_id
	 */
	public function delete_subscriber_uid( $user_id ) {
        $user_id = $this->id( $user_id );
		delete_user_meta( $user_id, $this->meta_key );
	}

	/**
	 * @return string
	 */
	public function get_meta_key() {
		return $this->meta_key;
	}

	/**
	 * @param WP_User $user
	 *
	 * @return array
	 */
	public function get_user_merge_fields( WP_User $user ) {
		$merge_fields = array();

		if( ! empty( $user->first_name ) ) {
            $merge_fields['FNAME'] = $user->first_name;
		}

		if( ! empty( $user->last_name ) ) {
            $merge_fields['LNAME'] = $user->last_name;
		}

		if( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
            $merge_fields['NAME'] = sprintf( '%s %s', $user->first_name, $user->last_name );
		}

		// Do we have mapping rules for user fields to mailchimp fields?
		if( ! empty( $this->field_map ) ) {

			// loop through mapping rules
			foreach( $this->field_map as $rule ) {

				// get field value
				$value = $this->tools->get_user_field( $user, $rule['user_field'] );

				if( is_string( $value ) && ! empty( $value ) ) {
                    $merge_fields[ $rule['mailchimp_field'] ] = $value;
				}
			}
		}

        /** @ignore @deprecated */
        $merge_fields = (array) apply_filters( 'mailchimp_sync_user_data', $merge_fields, $user );

		return $merge_fields;
	}

}