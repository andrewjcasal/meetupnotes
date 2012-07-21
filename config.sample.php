<?php
######################################################
##
##  Configuration file for Meetup Notes
##  Copy it to config.php and set values below
##
######################################################

/**
 * Source of randomness for security
 */
$randomness = '...some.random.characters.go.here...';

// used for un-authenticated requests
// Get yours here: http://www.meetup.com/meetup_api/key/
$meetupAPIKey = '...long.alpha.numeric.string...';

/**
 * MySQL configuration variables
 */
$mysql_db = 'meetnotes';
$mysql_user = 'meetnotes';
$mysql_password = '...password...';

/**
 * Meetup OAuth consumer key and secret
 * Register your app here: http://www.meetup.com/meetup_api/oauth_consumers/
 */
$meetup_OAuth_consumer_key = '...oauth.key.goes.here...';
$meetup_OAuth_consumer_secret = '...oauth.secret.goes.here...';
