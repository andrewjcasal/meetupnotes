<?php
/*
 * Copy this script to the folder above and populate $versions array with your migrations
 * For more info see: http://www.dbupgrade.org/Main_Page#Migrations_($versions_array)
 *
 * Note: this script should be versioned in your code repository so it always reflects current code's
 *       requirements for the database structure.
*/
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/dbupgrade/lib.php');

$versions = array();
// Add new migrations on top, right below this line.

/* -------------------------------------------------------------------------------------------------------
 * VERSION _
 * ... add version description here ...
*/
/*
$versions[_]['up'][] = "";
$versions[_]['down'][]	= "";
*/
$versions[1]['up'][] = "CREATE TABLE `notes` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` VARCHAR(255) NULL,
  `user_id` INTEGER UNSIGNED NULL,
  `title` TEXT NULL,
  `description` TEXT NULL,
  `url` TEXT NOT NULL,
  `content` TEXT NULL,
  `created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
$versions[1]['down'][] = "DROP TABLE notes";


// creating DBUpgrade object with your database credentials and $versions defined above
$dbupgrade = new DBUpgrade(new mysqli(null, $mysql_user, $mysql_password, $mysql_db, null), $versions);

require_once(dirname(__FILE__).'/dbupgrade/client.php');
