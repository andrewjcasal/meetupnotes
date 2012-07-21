<?php
require_once(dirname(__FILE__).'/users/users.php');

// get user if logged in or require user to login
$user = User::get();
#$user = User::require_login();

$event_id = $_REQUEST['event_id'];
if (!ctype_alnum($event_id)) {
	header('HTTP/1.0 400 Bad Request');
	echo 'Event ID must be alphanumeric';
	exit;
}

if (array_key_exists('add', $_POST)) {
	$user_id = null;
	if (!is_null($user)) {
		$user_id = $user->getID();
	}

	$url = $_POST['url'];
	$title = $_POST['title'];
	$description = $_POST['description'];

	// TODO Implement actual data insertion
}
?>
<html>
<head>
	<title>Adding a note?></title>
	<link rel="stylesheet" type="text/css" href="meetup.css"/>
</head>
<body>
<div style="float: right"><?php include(dirname(__FILE__).'/users/navbox.php'); ?></div>
<h1>Adding a note</h1>
<?php if (array_key_exists('add', $_POST)) { ?>
<p>Event id: <?php echo UserTools::escape($event_id) ?></p>
<p>URL: <?php echo UserTools::escape($url) ?></p>
<p>Title: <?php echo UserTools::escape($title) ?></p>
<p>Description (optional):<br/><?php echo UserTools::escape($description) ?></p>
<p>User id (optional):<?php echo UserTools::escape($user_id) ?></p>
<?php } ?>

<form method="POST">
<input type="hidden" name="event_id" value="<?php echo UserTools::escape($event_id) ?>"/>
<p>URL: <input type="text" name="url"/></p>
<p>Title: <input type="text" name="title"/></p>
<p>Description (optional)<br/><textarea name="description"></textarea></p>
<input type="submit" name="add" value="Save note"/>
</form>

</body>
</html>
