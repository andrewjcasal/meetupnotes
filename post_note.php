<?php
include('notes.php');

if(isset($_POST)) {
	if(
		isset($_POST["title"]) &&
		isset($_POST["description"]) &&
		isset($_POST["event_id"]) &&
		isset($_POST["title"]) &&
		isset($_POST["url"])) {
		 set_note($_POST["event_id"], $_POST["url"], null, $_POST["title"], $_POST["description"], $_POST["user_id"])
		
		?>
		<article>
			<div class="label-title"><?php echo $_POST["title"]; ?></div>
			<div class="label-description"><?php echo $_POST["description"]; ?></div>
			<a href="<?php echo $_POST["url"]; ?>">asdf</a>
		</article>
		<?php
	}
}
?>