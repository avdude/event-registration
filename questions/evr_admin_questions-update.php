<?php
function evr_post_update_question(){
    global $wpdb;
	(is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $event_name = $_REQUEST['event_name'];
    $question_text = $_REQUEST ['question'];
	$question_id = $_REQUEST ['question_id'];
	$question_type = $_REQUEST ['question_type'];
	$values = $_REQUEST ['values'];
	$required = $_REQUEST ['required'] ? 'Y' : 'N';
	$remark = $_REQUEST['remark'];	
	$wpdb->query ( "UPDATE ".get_option('evr_question')." set `question_type` = '$question_type', `question` = '$question_text', " . " `response` = '$values', `required` = '$required', `remark` = '$remark' where id = $question_id " );
?>
<META HTTP-EQUIV="refresh" content="0;URL=admin.php?page=questions&action=new&event_id=<?php echo $event_id . "&event_name=" . $event_name;?>">
<?php 
}
?>