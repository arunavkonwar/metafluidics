<?php

namespace MC4WP\Sync;

use MC4WP_API_v3;
use MC4WP_MailChimp_Subscriber;
use WP_User;

class UserSubscriber {

    /**
     * @var Users
     */
    protected $users;

    /**
     * @var MC4WP_API_v3
     */
    protected $api;

    /**
     * @var string
     */
    protected $list_id;

    /**
     * @var string
     */
    public $error_message = '';

    /**
     * Subscriber2 constructor.
     *
     * @param Users $users
     * @param string $list_id
     */
    public function __construct( Users $users, $list_id ) {
        $this->users = $users;
        $this->api = mc4wp('api');
        $this->list_id = $list_id;
    }

    /**
     * @param WP_User $user
     * @param $double_optin
     * @param $email_type
     * @return MC4WP_MailChimp_Subscriber
     */
    private function transform( WP_User $user, $double_optin, $email_type ) {
        $subscriber = new MC4WP_MailChimp_Subscriber();
        $subscriber->email_address = $user->user_email;
        $subscriber->merge_fields = $this->users->get_user_merge_fields( $user );
        $subscriber->email_type = $email_type;
        $subscriber->status = $double_optin ? 'pending' : 'subscribed';

        /**
         * Filter data that is sent to MailChimp
         *
         * @param MC4WP_MailChimp_Subscriber $subscriber
         * @param WP_User $user
         */
        $subscriber = apply_filters( 'mailchimp_sync_subscriber_data', $subscriber, $user );

        return $subscriber;
    }

    /**
     * @param int $user_id
     * @param bool $double_optin
     * @param string $email_type
     * @param bool $replace_interests
     * @param bool $send_welcome (Unused)
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function subscribe( $user_id, $double_optin = false, $email_type = 'html', $replace_interests = false, $send_welcome = null ) {
        $user = $this->users->user( $user_id );
        $subscriber = $this->transform( $user, $double_optin, $email_type );
        $exists = false;
		$args = $subscriber->to_array();
        $args['status_if_new'] = $args['status'];
        unset( $args['status'] );

        // get old email
        $mailchimp_email_address = $this->users->get_mailchimp_email_address( $user_id );
        if( empty( $mailchimp_email_address ) ) {
            $mailchimp_email_address = $subscriber->email_address;
        }

        // perform the call
        try {
            $existing_member_data = $this->api->get_list_member( $this->list_id, $mailchimp_email_address );
            $exists = true;
            if( $existing_member_data->status === 'subscribed' ) {
                // this key only exists if list actually has interests
                if ( isset( $existing_member_data->interests ) ) {
                    $existing_interests = (array) $existing_member_data->interests;

                    // if replace, assume all existing interests disabled
                    if ($replace_interests) {
                        $existing_interests = array_fill_keys(array_keys($existing_interests), false);
                    }

					$args['interests'] = array_merge( $existing_interests, $args['interests'] );
                }
            }
        } catch( \MC4WP_API_Resource_Not_Found_Exception $e ) {
            // OK: subscriber does not exist yet, but we're adding it later.
            $exists = false;
        } catch( \MC4WP_API_Exception $e ) {
            $this->error_message = (string) $e;
            return false;
        }

        try {
            // add or update subscriber
            if( $exists ) {
                $member = $this->api->update_list_member( $this->list_id, $mailchimp_email_address, $args );
            } else {
                $member = $this->api->add_list_member( $this->list_id, $args );
            }
        } catch( \MC4WP_API_Exception $e ) {
            $this->error_message = (string) $e;
            return false;
        }

        // Store remote email address & last updated timestamp
        $this->users->set_subscriber_uid( $user_id, $member->unique_email_id );
        $this->users->set_mailchimp_email_address( $user_id, $member->email_address );
        $this->users->touch( $user_id );
        return true;
    }

    /**
     * @param $user_id
     * @param string $email_type
     * @param bool $replace_interests
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function update( $user_id, $email_type = 'html', $replace_interests = false ) {
        return $this->subscribe( $user_id, false, $email_type, $replace_interests );
    }

    /**
     * @param int $user_id
     * @param string $email_address
     * @param string $subscriber_uid        (optional)
     * @param null $send_goodbye            (unused)
     * @param null $send_notification       (unused)
     * @param null $delete_member           (unused)
     *
     * @return bool
     */
    public function unsubscribe( $user_id, $email_address, $subscriber_uid = null, $send_goodbye = null, $send_notification = null, $delete_member = null ) {

        // fetch subscriber_uid
        if( is_null( $subscriber_uid ) ) {
            $subscriber_uid = $this->users->get_subscriber_uid( $user_id );
        }

        // if user is not even subscribed, just bail.
        if( empty( $subscriber_uid ) ) {
            return true;
        }

        try {
            $this->api->delete_list_member( $this->list_id, $email_address );
        } catch( \MC4WP_API_Resource_Not_Found_Exception $e ) {
            // OK, not subscribed in first place.
        } catch( \MC4WP_API_Exception $e ) {
            // Not OK.
            $this->error_message = (string) $e;
            return false;
        }

        $this->users->delete_mailchimp_email_address( $user_id );
        $this->users->delete_subscriber_uid( $user_id );
        return true;
    }
}