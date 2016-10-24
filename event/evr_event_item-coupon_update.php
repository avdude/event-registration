<?php
//function to post changes to the coupon code
function evr_post_update_coupon(){
                    global $wpdb;
                    $id=$_REQUEST['id'];
                    $use_coupon=$_REQUEST['use_coupon'];
            		$coupon_code=$_REQUEST['coupon_code'];
            		$coupon_code_price=$_REQUEST['coupon_code_price'];
                    $end_date=$_REQUEST['end'];
            		if ($coupon_code_price == ''){$coupon_code_price = 0.00;}
        $sql=array('use_coupon'=>$use_coupon, 'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price); 
        $update_id = array('id'=> $id);               
		$sql_data = array('%s','%s','%s');
                if ($wpdb->update( get_option('evr_event'), $sql, $update_id, $sql_data, array( '%s' ) )){
                    ?>
                <div id="message" class="updated fade"><p><strong><?php _e('The coupon code information has been updated.  You will now be returned to the Event Pricing Page','evr_language');?></strong></p></div>
                <meta http-equiv="Refresh" content="3; url=admin.php?page=events&action=add_item&event_id=<?php echo $id;?>.&end=<?php echo $end_date;?>"/>
               <?php } else { ?> 
                <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The coupon code changes were not saved!','evr_language');
                print $wpdb->last_error; ?>
                </strong></p></div>
                <meta http-equiv="Refresh" content="3; url=admin.php?page=events&action=add_item&event_id=<?php echo $id;?>&end=<?php echo $end_date;?>"/> 
                <?php }
}
?>