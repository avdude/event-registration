<?php
//function to delete an event from db
function evr_delete_event(){
        global $wpdb;
		if ( $_REQUEST['action'] == 'delete' ){
			$id=$_REQUEST['id'];
            //check attendee database for records for event
            $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id=%d",$id));
            if ($number_attendees > 0) { ?>
                <div id="message" class="error"><p><strong><?php _e('There are currently ','evr_language'); echo $number_attendees;
                _e(' attendes registered for this event.  The event cannot be deleted.','evr_language');?>.</strong></p>
                <p><strong><?php _e('. . .Now returning you to event list . . ','evr_language');?> <meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                </div>
                <?php 
                }
             else {
                $sql="DELETE FROM ".get_option('evr_event')." WHERE id=".$id;
                if ($wpdb->query($sql)){
		            $wpdb->query($wpdb->prepare(" DELETE FROM ".get_option('evr_question')." WHERE event_id = %d", $id));
                    $wpdb->query($wpdb->prepare(" DELETE FROM ".get_option('evr_cost')." WHERE event_id = %d", $id));
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php _e('The event has been deleted.','evr_language');?></strong></p>
                    <p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                    </div>
                <?php }
                else { ?>
                    <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event was not deleted!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p>
                    <p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                    </div>
                <?php }
                }   
          	}
		
		
          
          
}
?>