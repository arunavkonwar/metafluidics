<?php
/*
  Plugin Name: Metafluanalytics
  Description: Connects to the Google Analytics API to gets device view data
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

// create service object
function getService() {
  require_once 'google-api-client/src/Google/autoload.php';
  //$email = 'metafluidic-views@metafluidics-views.iam.gserviceaccount.com';
  //$key = file_get_contents( plugin_dir_path( __FILE__ ) . '/google-api-client/metafluidics-views-95843091aab1.p12' );
  
  $email = 'arunavkonwar-metafluidics@glassy-vial-208706.iam.gserviceaccount.com';
  $key = file_get_contents( plugin_dir_path( __FILE__ ) . '/google-api-client/my-project-9652ffc57e27.p12' );

  $client = new Google_Client();
  $client->setApplicationName( "Metafluanalytics" );
  $analytics = new Google_Service_Analytics( $client );
  $cred = new Google_Auth_AssertionCredentials(
      $email,
      array( Google_Service_Analytics::ANALYTICS_READONLY ),
      $key
  );

  $client->setAssertionCredentials( $cred );

  if( $client->getAuth()->isAccessTokenExpired() ) {
    $client->getAuth()->refreshTokenWithAssertion( $cred );
  }

  return $analytics;
}

// get view profile id
function getFirstprofileId( &$analytics) {
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if ( count( $accounts->getItems() ) > 0 ) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    $properties = $analytics->management_webproperties
        ->listManagementWebproperties( $firstAccountId );

    if ( count( $properties->getItems() ) > 0 ) {
      $items = $properties->getItems();
      $firstPropertyId = $items[0]->getId();

      $profiles = $analytics->management_profiles
          ->listManagementProfiles( $firstAccountId, $firstPropertyId );

      if ( count( $profiles->getItems() ) > 0 ) {
        $items = $profiles->getItems();
        return $items[0]->getId();
      }
      else {
        throw new Exception( 'No views (profiles) found for this user.' );
      }
    }
    else {
      throw new Exception( 'No properties found for this user.' );
    }
  }
  else {
    throw new Exception( 'No accounts found for this user.' );
  }
}

// get number of sessions for last seven days
function getResults( &$analytics, $profileId, $slug ) {

  $optParams = array(
    'dimensions' => 'ga:pagePath',
    'metrics' => 'ga:pageviews',
    'filters' => 'ga:pagePath==' . $slug,
    'max-results' => 1
  );

  $results = $analytics->data_ga->get(
      'ga:' . $profileId,
      '2015-12-31',
      'today',
      'ga:sessions',
      $optParams);

  return $results;
}

// get views of a specific page and return
function getPageViews( $slug ) {
  global $analytics, $profile;

  $results = getResults( $analytics, $profile, $slug );

  if ( count( $results->getRows() ) < 1 ) {
    return;
  }

  $profileName = $results->getProfileInfo()->getProfileName();
  $rows = $results->getRows();
  $views = $rows[0][1];

  return $views;
}

// globals
$analytics = getService();
$profile = getFirstProfileId( $analytics );

?>
