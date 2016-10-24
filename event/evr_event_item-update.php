<?php
//function to post update to price/sale item
function evr_update_item(){
           global $wpdb;
            //$event_id  = $_REQUEST['event_id'];
            (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
            $item_id = $_REQUEST['item_id'];
            $event_end = $_REQUEST['event_end'];
            
            //do not update sequence - leave as is
			
            $item_title       = $_REQUEST['item_name'];
            $item_description = $_REQUEST['item_desc']; 
            $item_cat         = $_REQUEST['item_cat'];
                 
            //$item_limit       = $_REQUEST['item_limit'];
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
            


            
            $sql=array('event_id'=>$event_id, 'item_title'=>$item_title, 'item_description'=>$item_description, 
                        'item_cat'=>$item_cat, 'item_limit'=>$item_limit, 'item_price'=>$item_price, 'free_item'=>$free_item,'item_available_start_date'=>$item_start_date,  
                        'item_available_end_date'=>$item_end_date, 'item_custom_cur'=>$item_custom_cur); 
		
			$sql_data = array('%s','%s','%s','%s','%s','%f','%s','%s','%s','%s');
            
                      
        
                $update_id = array('id'=>$item_id);
                
                if ($wpdb->update( get_option('evr_cost'), $sql, $update_id, $sql_data, array( '%d' ) )){
                ?>
                <div id="message" class="updated fade"><p><strong><?php _e('The event cost/ticket item has been updated.  You will now be taken back to the Event Pricing Page','evr_language');?></strong></p></div>
                <meta http-equiv="Refresh" content="3;  url=admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>&event_end=<?php echo $event_end;?>"/>
                <?php }
                else { ?>
                <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event cost/ticket item was not updated!','evr_language');                 
                print $wpdb->last_error;
                _e('Now taking you back . . .');?>
                </strong></p></div>
                <meta http-equiv="Refresh" content="3;  url=admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>&event_end=<?php echo $event_end;?>"/>
                <?php }
			       
}
?>