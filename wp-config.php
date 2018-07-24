<?php

# Database Configuration
define( 'DB_NAME', 'wp_metafluidics' );
define('CONCATENATE_SCRIPTS', false);
define(‘SCRIPT_DEBUG’, true);
define( 'DB_USER', 'metafluidics' );
define( 'DB_PASSWORD', 'WOD7JyFQpb7BryTO5WM9' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY', 'so*gdPgc-`mCLa&*cYCB3}|B~7<W#:3|%y1`XTU8d {-eW5HDgG3V-&-c!>b6jMF');
define('SECURE_AUTH_KEY', 'd0e*;<E5BR@Ml%/>ngwlyt>hvxqk-L0-|>12A(t`Y807fEeQq?}imFM+6H(N!Uxd');
define('LOGGED_IN_KEY', '/EfZ8*_Xdn-0+<Lk|!{AfwBgSw5q~}|!U3vJTUI8TWPp(bp2tj4U-}33|VjL,k:1');
define('NONCE_KEY', '3-upMPfY$F3lB}^rJq%MfxJ9LjXwZgh_s|#4PuGCg|%uRKn]ma4zYV+fk|iNKa9h');
define('AUTH_SALT',        ':!t/q$b;K.,sZ,pC/em3G.BEnP~8V`FQA1RA^lQIf*!3m(H*i`}hwV"H4tAT2Ab8');
define('SECURE_AUTH_SALT', 'kQj {t49*+srZwhg",Vp:*p No3`^ZosRMqe:uO"%qT:D92l=]+zPH~FDaMaz%$f');
define('LOGGED_IN_SALT',   '`?H<iBJ`I0pr[,:i0EA>*@C>BC3U@U:gwmu,n}G)?dfz~|->6z!$duLu5. $NxKY');
define('NONCE_SALT',       ')K*r*yW%lRzvGjp>.E0a (?dq(hOBiJnrczuVk>l3A6?}AcRH46KnqH_PxKvD%Z*');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'metafluidics' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '85b6d0aa5133043c825c1bff99f157d803b5de7f' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '100654' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', true );

define( 'FORCE_SSL_LOGIN', true );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'metafluidics.org', 1 => 'metafluidics.wpengine.com', 2 => 'www.metafluidics.org', 3 => 'www.metafluidics.com', 4 => 'metafluidics.com', );

$wpe_varnish_servers=array ( 0 => 'pod-100654', );

$wpe_special_ips=array ( 0 => '104.196.192.156', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

//define( 'WP_SITEURL', 'http://metafluidics.org' );

//define( 'WP_HOME', 'http://metafluidics.org' );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
