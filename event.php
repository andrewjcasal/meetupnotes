<?php
require_once(dirname(__FILE__).'/users/users.php');

// get user if logged in or require user to login
$user = User::get();
#$user = User::require_login();

$event_id = $_REQUEST['id'];
if (!ctype_alnum($event_id)) {
	header('HTTP/1.0 400 Bad Request');
	echo 'Event ID must be alphanumeric';
	exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/event/'.$event_id.'?fields=event_hosts&key='.$meetupAPIKey); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
$curl_info = curl_getinfo($ch);

// checking if HTTP call was successful and return non-empty response
if ($curl_info['http_code'] != 200 || !$output) {
	header('HTTP/1.0 500 Server Error');
	echo "API problems";
	exit;
}
curl_close($ch);

$data = json_decode($output, true); //, 512, JSON_BIGINT_AS_STRING);

// checking if we were able to parse JSON
if (is_null($data)) {
	header('HTTP/1.0 500 Server Error');
	echo "Can not decode JSON: <pre>".htmlentities($output)."</pre>";
	exit;
}

if (array_key_exists('problem', $data)){
	if ($data['problem'] == 'Not Found') {
		header('HTTP/1.0 404 Not Found');
	} else {
		header('HTTP/1.0 500 Server Error');
	}

	echo $data['details'];

	exit;
}

// checking if user is an organizer of this meetup
$is_organizer = false;
if (!is_null($user)) {
	$meetup_info = $user->getUserCredentials('meetup')->getUserInfo();
	$member_id= $meetup_info['id'];

	foreach ($data['event_hosts'] as $event_host) {
		if ($event_host['member_id'] == $member_id) {
			$is_organizer = true;
		}
	}
}

?>
<html>
<head>
	<title>Meetup Notes for <?php echo UserTools::escape($data['name']) ?></title>
	<link rel="stylesheet" type="text/css" href="meetup.css"/>
</head>
<body>
<div style="float: right"><?php include(dirname(__FILE__).'/users/navbox.php'); ?></div>
<h1>Notes for <?php echo UserTools::escape($data['name']) ?></h1>
<p>Group: <?php echo UserTools::escape($data['group']['name']) ?></p>

<div class="description">
<?php echo $data['description'] ?>
</div>

<h2>Notes</h2>
<p><a href="add.php?event_id=<?php echo urlencode($event_id) ?>">Add your note</a></p>

TODO: add actual notes

</body>
</html>
