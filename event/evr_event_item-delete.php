<?php
//function to delete price/sale item
function evr_delete_item(){
            global $wpdb;
			$item_id = $_REQUEST['item_id'];
            $event_end=$_REQUEST['event_end'];
            (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
           // $event_id = $_REQUEST['event_id'];
            $sql = "DELETE FROM ".get_option('evr_cost')." WHERE id='$item_id'";
			$wpdb->query ( $sql );
            ?>
               
            <div id='message' class='updated fade'><p><strong><?php _e('The cost item/ has been deleted.','evr_language');?></strong></p></div>
            <meta http-equiv="Refresh" content="3; url=admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>&end=<?php echo $event_end;?>"/>
            <?php
}
?>