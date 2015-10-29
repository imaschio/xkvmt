<?php 

global $esynConfig, $STW_Account;

/**
 * Prerequisites: PHP4 (tested 4.4.1+), PHP5
 * Maintainers: Andreas Pachler, Brandon Elliott
 *
 *	For the latest documentation and best practices: please visit http://www.shrinktheweb.com/content/shrinktheweb-pagepix-documentation.html
 */
define('ACCESS_KEY', $esynConfig->getConfig('stw_access_key'));
define('SECRET_KEY', $esynConfig->getConfig('stw_secret_key'));
define('THUMBNAIL_URI', IA_URL . 'uploads/stw_thumbs/');
define('THUMBNAIL_DIR', IA_UPLOADS . 'stw_thumbs/');
define('INSIDE_PAGES', false); // set to true if inside capturing should be allowed
define('CUSTOM_MSG_URL', ''); // i.e. 'http://yourdomain.com/path/to/your/custom/msgs'
define('CACHE_DAYS', $esynConfig->getConfig('stw_cache_days')); // how many days should the local copy be valid?
                         // Enter 0 (zero) to never update screenshots once cached
                         // Enter -1 to disable caching and always use embedded method instead
define('VER', '2.0.4'); // allows us to identify known bugs and version control; DONT touch!
define('QUOTA_IMAGE', 'quota.jpg');
define('BANDWIDTH_IMAGE', 'bandwidth.jpg');
define('NO_RESPONSE_IMAGE', 'no_response.jpg');
define('MAINTENANCE', 'ShrinkTheWeb is temporarily offline for maintenance');

// DB constants, must be setup when using debug
// For Advanced Users: create a database, add a user w/ permission to it, and then run the SQL in stw_debug_db.sql to setup it up
define('DEBUG', false); // MUST be "true" to log debug entries to database
define('DATABASE_HOST', IA_DBHOST); // localhost is common
define('DATABASE_PORT', IA_DBPORT); // 3306 is the default
define('DATABASE_SOCK', ''); // typically left blank
define('DATABASE_NAME', IA_DBNAME);
define('DATABASE_USER', IA_DBUSER);
define('DATABASE_PASS', IA_DBPASS);

include_once IA_PLUGINS . 'stw/includes/classes/stw_example_code.php';
include_once IA_PLUGINS . 'stw/includes/classes/stw_account_api.php';

$sAccount = getAccountInfo();

if ($sAccount)
{
	foreach ($sAccount as $option => $value)
	{
		$val = (array)$value;
		$STW_Account[$option] = $val[0];
	}
}