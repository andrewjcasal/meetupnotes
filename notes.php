<?php

include('config.php');

function set_note($event_id, $url, $content = null, $title = null, $description = null, $user_id = null) {

	$query = "INSERT INTO notes SET
        event_id='".mysql_real_escape_string($event_id)."',
        url='".mysql_real_escape_string($url)."',
        content='".mysql_real_escape_string($content)."',
        title='".mysql_real_escape_string($title)."',
        description='".mysql_real_escape_string($description)."',
        user_id='".mysql_real_escape_string($user_id)."'";

	$result = mysql_query($query);
    
    if ($result) return true;
    else return false;
}

function get_note($id) {

}

function get_notes_by_meetup($event_id) {
    $query = "SELECT * FROM notes WHERE event_id=".mysql_real_escape_string($event_id);
    $result = mysql_query($query);
    for ($i=0;$row = mysql_fetch_assoc($result);$i++) {
        $return[$i]['id'] = $row['id'];
        $return[$i]['event_id'] = $row['event_id'];
        $return[$i]['user_id'] = $row['user_id'];
        $return[$i]['title'] = $row['title'];
        $return[$i]['description'] = $row['description'];
        $return[$i]['url'] = $row['url'];
        $return[$i]['content'] = $row['content'];
        $return[$i]['created'] = $row['created'];
    }
    if (isset($return) && !empty($return)) return $return;
    else return false;
}

if (isset($_GET['set'])) { //add note to db
    if (set_note($_GET['meetup'], $_GET['url'])) echo 1;
    else echo 0;
} elseif (isset($_GET['get'])) {
    if (isset($_GET['meetup'])) { //get all notes by meetup
        $results = get_notes_by_meetup($_GET['meetup']);
        if ($results) echo json_encode($results);
        else echo 0;
    } else { //get a single note by its id

    }
}

?>
