<?php
require_once(dirname(__FILE__).'/users/users.php');

// get user if logged in or require user to login
$user = User::get();
#$user = User::require_login();
$event_id = filter_var($_REQUEST['event_id'], FILTER_SANITIZE_NUMBER_INT);
$user_id = null;

if (!is_null($user))
	$user_id = $user->getID();
	
?>
<html>
<head>
  <title>MeetupNotes | Add Content</title>
	<link rel="stylesheet" type="text/css" href="meetup.css"/>
  <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
  <script src="http://scripts.embed.ly/jquery.embedly.min.js"></script>
  <script type="text/javascript">
    $('document').ready(function(){
		console.log('finished loading external scripts');
    });
    function attemptEmbed() {
		console.log('start embedly');
		$('div.response').embedly({key: "c83269d35e7b4b408906c67b1aeb9cea"});
		console.log('finish embedly');
    }
    var logged;
    function checkEmbed() {
		console.log('check url');
		var formUrl = $('input.field-url').attr('value');
		var formTitle = $('input.field-title').attr('value');
		var formUserId = $('input.field-user-id').attr('value');
		var formDescription = $('div.field-description').html();
		var formEventId = $('input.field-event-id').attr('value');
		logged = {
			url:formUrl,
			event_id:formEventId,
			user_id:formUserId,
			title:formTitle,
			description:formDescription
		};
		console.log('got url');
		$.post("post_note.php", logged ,function(response) {
			$('div.response').html(response);
			attemptEmbed();
		});
    }
  </script>
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
	<input type="hidden" class="field-event-id" value="<?php echo UserTools::escape($event_id) ?>"/>
	<input type="hidden" class="field-user-id" value="<?php echo UserTools::escape($user_id) ?>"/>
	<div class="field-row"><div class="field-label">Url</div><input type="textbox" placeholder="Title" class="field-title" /></div>
	<div class="field-row"><div class="field-label">Title</div><input type="textbox" placeholder="http://website.com/" class="field-url" /></div>
	<div class="field-row"><div class="field-label">Description (optional)</div><div class="field-description" onClick="this.contentEditable='true';" onBlur="this.contentEditable='true';"></div></div>
	<br /><br /><br /><br /><br /><br /><br /><br /><br />
	<div class="submit-button" onclick="checkEmbed();">submit</div>
</body>
</html>
