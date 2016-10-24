<?php
function evr_delete_attendee(){
    global $wpdb;
    $attendee_id = $_REQUEST['attendee_id'];
    $event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['attendee_id'])) ? $attendee_id = $_REQUEST['attendee_id'] : $attendee_id = "0";
    $sql = " DELETE FROM " . get_option('evr_attendee') . " WHERE id ='$attendee_id'";
    $wpdb->query ( $sql );
    echo "<div id='message' class='updated fade'><p><strong>";
    _e('The attendee information has been successfully deleted from the event.','evr_language');
    echo "</strong></p></div>";
    echo "<META HTTP-EQUIV='refresh' content='2;URL=";
    echo "admin.php?page=attendee&action=view_attendee&event_id=".$event_id."'>";
}
function evr_delete_all_attendee(){
    global $wpdb;
    $e_id = $_REQUEST['e_id'];
    (is_numeric($_REQUEST['e_id'])) ? $e_id = $_REQUEST['e_id'] : $e_id = "0";
    $sql = " DELETE FROM " . get_option('evr_attendee') . " WHERE event_id ='$e_id'";
    $wpdb->query ( $sql );
    echo "<div id='message' class='updated fade'><p><strong>";
    _e('All the attendee information has been successfully deleted from the event.','evr_language');
    echo "</strong></p></div>";
    echo "<META HTTP-EQUIV='refresh' content='2;URL=";
    echo "admin.php?page=attendee'>";
}
?>