<?php
//function to new price/sale items
function evr_post_item_to_db(){
            global $wpdb;
			$sequence         = $wpdb->get_var ( "SELECT max(sequence) FROM ".get_option('evr_cost')." where event_id = '$event_id'" ) + 1;
			(is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
            //$event_id         = $_REQUEST['event_id'];
            $item_title       = $_REQUEST['item_name'];
            $item_description = $_REQUEST['item_desc']; 
            $item_cat         = $_REQUEST['item_cat'];
            if ($_REQUEST['item_limit']==""){
            $item_limit       = "25";    
            }else {
            $item_limit       = $_REQUEST['item_limit'];}
            $item_price       = $_REQUEST['item_price'];
            $free_item        = $_REQUEST['item_free'];
            $item_start_month = $_REQUEST['item_start_month'];
            $item_start_day   = $_REQUEST['item_start_day'];
            $item_start_year  = $_REQUEST['item_start_year'];
            $item_end_month   = $_REQUEST['item_end_month'];
            $item_end_day     = $_REQUEST['item_end_day'];
            $item_end_year    = $_REQUEST['item_end_year'];
            $item_start_date  = $item_start_year."-".$item_start_month."-".$item_start_day;
            $item_end_date    = $item_end_year."-".$item_end_month."-".$item_end_day;
            $item_custom_cur  = $_REQUEST['custom_cur'];
            $event_end        = $_REQUEST['end'];
            $sql=array( 'sequence'=>$sequence,'event_id'=>$event_id, 'item_title'=>$item_title, 'item_description'=>$item_description, 
                        'item_cat'=>$item_cat, 'item_limit'=>$item_limit, 'item_price'=>$item_price, 'free_item'=>$free_item,'item_available_start_date'=>$item_start_date,  
                        'item_available_end_date'=>$item_end_date, 'item_custom_cur'=>$item_custom_cur); 
			$sql_data = array('%s','%s','%s','%s','%s','%s','%f','%s','%s','%s','%s');
                if ($wpdb->insert( get_option('evr_cost'), $sql, $sql_data )){ 
                 $lastID = $wpdb->insert_id;   
                 ?>
                <div id="message" class="updated fade"><p><strong><?php _e('The event cost/ticket item has been added.  You will now be taken back to the Event Pricing Page','evr_language');?></strong></p></div>
                <meta http-equiv="Refresh" content="3; url=admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>&event_end=<?php echo $event_end;?>"/>
                <?php }
                else { ?>
                <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event cost/ticket item was not saved!','evr_language');                 
                print $wpdb->last_error;
                _e('Now taking you back . . .','evr_language');?>
                </strong></p></div>
                <meta http-equiv="Refresh" content="3; admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>&event_end=<?php echo $event_end;?>"/>
                <?php }
}
?>