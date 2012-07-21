<?php
require_once(dirname(__FILE__).'/users/users.php');

// get user if logged in or require user to login
$user = User::get();
#$user = User::require_login();

$recent_events = array();
$upcoming_events = array();

if (!is_null($user)) {
	// You can work with users, but it's recommended to tie your data to accounts, not users
	#$current_account = Account::getCurrentAccount($user);

	$creds = $user->getUserCredentials('meetup');
	$meetup_info = $creds->getUserInfo();
	$meetup_id = $meetup_info['id'];

	$page = 0; // requesting first page
	$keep_going = true;

	while($keep_going) {
		$result = $creds->makeOAuthRequest(
			'http://api.meetup.com/2/events?rsvp=yes&status=upcoming,past&order=time&desc=true',
			'GET'
		);

		if ($result['code'] == 200) {
			$api_data = json_decode($result['body'], true);

			foreach ($api_data['results'] as $event) {
				$event_info = array(
					'id' => $event['id'],
					'name' => $event['name'],
					'event_url' => $event['event_url'],
					'status' => $event['status'],
					// Meetup's timestamps are in milliseconds
					'time' => $event['time'] / 1000,
				);

				if ($event['status'] == 'past') {
					$recent_events[] = $event_info;
				}

				if ($event['status'] == 'upcoming') {
					$upcoming_events[] = $event_info;
				}
			}

			// keep going while next meta parameter is set
			$keep_going = $api_data['meta']['next'] !== '';

			if ($keep_going) {	
				$page++;
			}
		} else {
			$keep_going = false;
		}
	}
}
?>
<html>
<head>
	<title>Meetup Notes</title>
	<link rel="stylesheet" type="text/css" href="meetup.css"/>
</head>
<body>
<div style="float: right"><?php include(dirname(__FILE__).'/users/navbox.php'); ?></div>
<?php

if (!is_null($user)) {
?>
<h1>Welcome, <?php echo $user->getName() ?>!</h1>

<div style="width: 400px; max-width: 100%">
<a href="https://github.com/jasondpearson/meetupnotes" target="_blank" style="float: left"><img alt="Octocat.png" src="http://startupapi.org/w/images/thumb/6/61/Octocat.png/50px-Octocat.png" width="50" height="50" border="0" align="top" style="margin-right: 1em"></a>
This is Meetup Notes application powered by <a href="http://www.startupapi.com">Startup API</a>, you can see the <a href="https://github.com/jasondpearson/meetupnotes" target="_blank">code on Github</a>.
</div>
<div style="clear: both"></div>

<?php
	$event_categories = array(
		array('name' => 'Recent events', 'events' => $recent_events),
		array('name' => 'Upcoming events', 'events' => $upcoming_events)
	);

	$total_events = 0;
	foreach ($event_categories as $event_category) {
		if (count($event_category['events'])) {
		?>
		<h3><?php echo $event_category['name']?></h3>
		<ul class="events">
		<?php
			foreach ($event_category['events'] as $event) {
				?><li>
					<a href="event.php?id=<?php echo $event['id'] ?>"><?php echo $event['name'] ?></a> (<?php echo date('F j', $event['time'])?><sup><?php echo date('S', $event['time'])?></sup>)
				</li><?php
			}
		?>
		</ul>
		<?
			$total_events++;
		}
	}

	if (!$total_events) { ?>
		<p>You still didn't attend any events?!</p>
		<p><a href="http://www.meetup.com/find/">Find a group and join immediately!</a></p>
	<?
	}
}
else
{
?>
<h1>Meetup Notes</h1>

<?php
	$meetup_module = AuthenticationModule::get('meetup');
	?><p><?php $meetup_module->renderRegistrationForm(); ?></p><?php
}
?> 

</body>
</html>
