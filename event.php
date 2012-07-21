<?php
require_once(dirname(__FILE__).'/users/users.php');

// get user if logged in or require user to login
#$user = User::get();
#$user = User::require_login();

$event_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/event/'.$event_id.'?key='.$meetupAPIKey); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$data = json_decode(curl_exec($ch), true);
curl_close($ch);

#var_export($data); exit;

if (array_key_exists('problem', $data)){
	if ($data['problem'] == 'Not Found') {
		header('HTTP/1.0 404 Not Found');
	} else {
		header('HTTP/1.0 500 Server Error');
	}

	echo $data['details'];

	exit;
}
?>
<html>
<head>
	<title>Meetup Notes for <?php echo UserTools::escape($data['name']) ?></title>
	<link rel="stylesheet" type="text/css" href="meetup.css"/>
</head>
<body>
<div style="float: right"><?php include(dirname(__FILE__).'/users/navbox.php'); ?></div>
<h1><?php echo UserTools::escape($data['name']) ?></h1>

<div class="description">
<?php echo $data['description'] ?>
</div>

<h2>Notes</h2>
<p><a href="add.php?event_id=<?php echo urlencode($event_id) ?>">Add your note</a></p>

TODO: add actual notes

</body>
</html>
