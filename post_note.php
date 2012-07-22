<?php
require_once(dirname(__FILE__).'/users/users.php');
include('notes.php');

$user = User::get();

if(isset($_POST)) {
	if(
		isset($_POST["title"]) &&
		isset($_POST["description"]) &&
		isset($_POST["event_id"]) &&
		isset($_POST["title"]) &&
		isset($_POST["url"])) {

		$user_id = null;
		if (!is_null($user)) {
			$user_id = $user->getID();
		}

		if (set_note($_POST["event_id"], $_POST["url"], null, $_POST["title"], $_POST["description"], $user_id)) {
			if (!is_null($user)) {
				$user->recordActivity(MEETUPNOTES_ACTIVITY_ADD_NOTE);
			}
		?>
		<article>
			<div class="label-title"><?php echo $_POST["title"]; ?></div>
			<div class="label-description"><?php echo $_POST["description"]; ?></div>
			<a href="<?php echo $_POST["url"]; ?>">asdf</a>
		</article>
		<?php
		} else {
			?>Could not post note ;(<?php
		}
	}
}
?>
