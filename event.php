<?php
require_once(dirname(__FILE__).'/users/users.php');
include('notes.php');

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

// Meetup responses come in ISO-8859-1 - converting to UTF8
$output = utf8_encode($output);

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

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.meetup.com/2/groups?key='.$meetupAPIKey.'&sign=true&group_id='.$data['group']['id']); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
$curl_info = curl_getinfo($ch);

// Meetup responses come in ISO-8859-1 - converting to UTF8
$output = utf8_encode($output);

// checking if HTTP call was successful and return non-empty response
if ($curl_info['http_code'] != 200 || !$output) {
	header('HTTP/1.0 500 Server Error');
	echo "API problems";
	exit;
}
curl_close($ch);

$data_group = json_decode($output, true); //, 512, JSON_BIGINT_AS_STRING);

// checking if we were able to parse JSON
if (is_null($data_group)) {
	header('HTTP/1.0 500 Server Error');
	echo "Can not decode JSON: <pre>".htmlentities($output)."</pre>";
	exit;
}

if (array_key_exists('problem', $data_group)){
	if ($data_group['problem'] == 'Not Found') {
		header('HTTP/1.0 404 Not Found');
	} else {
		header('HTTP/1.0 500 Server Error');
	}

	echo $data_group['details'];

	exit;
}

#var_export($data_group); exit;

$data_group = $data_group['results'][0];

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
    <link href="meetup.css" rel="Stylesheet" type="text/css" />
  <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
  <script src="http://scripts.embed.ly/jquery.embedly.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $(".little-content").hover( function() {
                $(this).find("div").fadeIn();
            }, function() {
                $(this).find("div").fadeOut();
            });
		console.log('start embedly');
		$('.little-content').embedly({key: "c83269d35e7b4b408906c67b1aeb9cea"});
		console.log('finish embedly');
        });
    </script>
</head>
<body>
<? $notes = get_notes_by_meetup($event_id); ?>
<div class="area-content">
    <div class="top">
        <div class="nav">
            <?php include(dirname(__FILE__).'/users/navbox.php'); ?>
        </div>
    </div>
    <div class="scrollable-content">
        <div class="container clearfix">
            <? foreach($notes as $note): ?>
            <div id="note<?=$note['id']?>" class="little-content">
                <a href="<?=$note['url']?>"><?=$note['title']?></a>
                <div class="fade">
                    <?=$note['description']?>
                </div>
            </div>
            <? endforeach; ?>
            <div class="header clearfix">
                <div class="header-left">
                    <div class="title"><?php echo UserTools::escape($data['name']) ?></div>
			<div class="subtitle"><?php echo $data_group['name']?> on <?php
			echo date('F j', $data['time'] / 1000)?><sup><?php echo date('S', $data['time'] / 1000)?></sup>, <?php echo date('Y', $data['time'] / 1000)?></div>
                </div>
                <div class="header-right">
                </div>
            </div>
            <!--div>Group: <?php //echo UserTools::escape($data['group']['name']) ?></div-->
            <!--div><?php //echo $data['description'] ?></div-->
            <div class="sidebar">
                <a href="add.php?event_id=<?php echo urlencode($event_id) ?>" class="add">add</a>
                <!--div class="event-url">
                </div>
                <div class="sms">
                </div-->
            </div>
            <div class="area-social">
		<!--
                <a href="#" class="linkedin">sign in with linkedin</a>
                <a href="#" class="twitter">sign in with twitter</a>
                <a href="#" class="facebook">sign in with facebook</a>
		-->
		<?php
		if (is_null($user)) {
			$meetup_module = AuthenticationModule::get('meetup');
			$meetup_module->renderRegistrationForm();
		}
		?>
            </div>
        </div>
    </div><!-- end container -->
</div><!-- end scrollable-content -->

</div><!-- end area-content -->
</body>
</html>
