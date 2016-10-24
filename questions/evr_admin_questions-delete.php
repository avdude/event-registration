<?php
function evr_delete_question(){
			
			global $wpdb;
			//$event_id = $_REQUEST['event_id'];
			(is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
            $event_name = $_REQUEST['event_name'];
			$question_id = $_REQUEST['question_id'];
			
			$wpdb->query ( "DELETE from ".get_option('evr_question')." where id = '$question_id'" );
 ?>           
<div id="message" class="updated fade"><p><strong><?php _e('The Question has been deleted. Returning to Question Management.','evr_language');?></strong></p></div>
<meta HTTP-EQUIV='refresh' content='2;URL=admin.php?page=questions&action=new&event_id=<?php echo $event_id;?>'>
<?php
} 
?>