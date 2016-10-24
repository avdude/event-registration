<?php
function evr_post_question(){
    global $wpdb;
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $event_name = $_REQUEST['event_name'];
    $question = $_REQUEST ['question'];
    $question_type = $_REQUEST ['question_type'];
    $values = $_REQUEST ['values'];
    $required = $_REQUEST ['required'] ? 'Y' : 'N';
    $remark = $_REQUEST['remark'];
    $sequence = $wpdb->get_var ( "SELECT max(sequence) FROM ".get_option('evr_question')." where event_id = '$event_id'" ) + 1;
	if ($wpdb->query ( "INSERT INTO ".get_option('evr_question')." (`event_id`, `sequence`, `question_type`, `question`, `response`, `required`,`remark`)" . " values('$event_id', '$sequence', '$question_type', '$question', '$values', '$required', '$remark')" )){ ?>
	<div id="message" class="updated fade"><p><strong><?php _e('The question has been added.','evr_language');?> </strong></p>
    <p><strong><?php _e(' . . .Now returning you to Question Managment . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=admin.php?page=questions&action=new&event_id=<?php echo $event_id;?>&event_name=<?php echo $event_name;?>"></strong></p></div>
    <?php }else { ?>
	<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The question was not saved!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p>
    <p><strong><?php _e(' . . .Now returning you to Question Management . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=admin.php?page=questions&action=new&event_id=<?php echo $event_id;?>&event_name=<?php echo $event_name;?>"></strong></p>
    </div>
<?php } 
}
?>