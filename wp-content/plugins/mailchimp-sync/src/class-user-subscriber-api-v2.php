<?php

namespace MC4WP\Sync;

class UserSubscriberAPIv2 {

    /**
     * @var string
     */
    public $error_message = '';

    /**
     * @var Users
     */
    protected $users;

    /**
     * @var \MC4WP_API
     */
    protected $api;

    /**
     * @var string
     */
    protected $list_id;

    /**
     * Subscriber2 constructor.
     *
     * @param Users $users
     * @param string $list_id
     */
    public function __construct( Users $users, $list_id ) {
        $this->users = $users;
        $this->api = mc4wp_get_api();
        $this->list_id = $list_id;
    }

    /**
     * @param int $user_id
     * @param bool $double_optin
     * @param string $email_type
     * @param bool $replace_interests
     * @param bool $send_welcome
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function subscribe( $user_id, $double_optin = false, $email_type = 'html', $replace_interests = false, $send_welcome = false ) {

        $subscriber_uid = $this->users->get_subscriber_uid( $user_id );
        if( ! empty( $subscriber_uid ) ) {
            return $this->update( $user_id, $email_type, $replace_interests );
        }

        $user = $this->users->user( $user_id );
        $merge_vars = $this->users->get_user_merge_fields( $user );
        $update_existing = true;
        $success = $this->api->subscribe( $this->list_id, $user->user_email, $merge_vars, $email_type, $double_optin, $update_existing, $replace_interests, $send_welcome );

        if( ! $success ) {
            $this->error_message = $this->api->get_error_message();
            return false;
        }

        $last_api_response = $this->api->get_last_response();
        $subscriber_uid = $last_api_response->leid;
        $this->users->set_subscriber_uid( $user_id, $subscriber_uid );

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
        $user = $this->users->user( $user_id );
        $merge_vars = $this->users->get_user_merge_fields( $user );
        $merge_vars['new-email'] = $user->user_email;

        $subscriber_uid = $this->users->get_subscriber_uid( $user->ID );

        // update subscriber in mailchimp
        $success = $this->api->update_subscriber( $this->list_id, array( 'leid' => $subscriber_uid ), $merge_vars, $email_type, $replace_interests );

        // Error?
        if( ! $success ) {

            // subscriber leid did not match anything in the list
            if( in_array( $this->api->get_error_code(), array( 215, 232 ) ) ) {

                // delete subscriber leid as it's apparently wrong
                $this->users->delete_subscriber_uid( $user->ID );

                // re-subscribe user
                return $this->subscribe( $user->ID, false, $email_type, $replace_interests );
            }

            // other errors
            $this->error_message = $this->api->get_error_message();

            return false;
        }

        return true;
    }

    /**
     * @param int $user_id
     * @param string $email_address
     * @param string $subscriber_uid
     * @param boolean $send_goodbye
     * @param boolean $send_notification
     * @param boolean $delete_member
     *
     * @return bool
     */
    public function unsubscribe( $user_id, $email_address, $subscriber_uid = null, $send_goodbye = false, $send_notification = false, $delete_member = false ) {

        // fetch subscriber_uid
        if( is_null( $subscriber_uid ) ) {
            $subscriber_uid = $this->users->get_subscriber_uid( $user_id );
        }

        // if user is not even subscribed, just bail.
        if( empty( $subscriber_uid ) ) {
            return true;
        }

        $success = $this->api->unsubscribe( $this->list_id, $email_address, $send_goodbye, $send_notification, $delete_member );
        $this->error_message = $this->api->get_error_message();

        if( $success ) {
            $this->users->delete_subscriber_uid( $user_id );
        }

        return $success;
    }
}