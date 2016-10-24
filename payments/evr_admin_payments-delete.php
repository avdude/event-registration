<?php
function evr_delete_payment(){
                global $wpdb;
                $payment_id = $_REQUEST['id'];
                //$event_id = $_REQUEST['event_id'];
				(is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
                $sql = " DELETE FROM " . get_option('evr_payment') . " WHERE id ='$payment_id'";
				$wpdb->query ( $sql );
                echo "<div id='message' class='updated fade'><p><strong>";
                _e('The payment has been successfully deleted from the attendee.','evr_language');
                echo "</strong></p></div>";
                echo "<META HTTP-EQUIV='refresh' content='2;URL=";
                echo "admin.php?page=payments&action=view_payments&event_id=".$event_id."'>";
}
?>