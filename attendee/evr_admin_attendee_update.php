<?php
function evr_update_attendee(){
    global $wpdb;
    $attendee_id = (string)@$_REQUEST['attendee_id'];
    $num_people = 0;
    $item_order = array();
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    //Begin gather registrtion data for database input
    $fname = (string)@$_REQUEST['fname'];
    $lname = (string)@$_REQUEST['lname'];
    $address = (string)@$_REQUEST['address'];
    $city = (string)@$_REQUEST['city'];
    $state = (string)@$_REQUEST['state'];
    $zip = (string)@$_REQUEST['zip'];
    $phone = (string)@$_REQUEST['phone'];
    $email = (string)@$_REQUEST['email'];
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $payment = (string)@$_REQUEST['total'];
    $coupon = (string)@$_REQEUST['coupon'];
    $reg_type = (string)@$_REQUEST['reg_type'];
    $attendee_array = (array)@$_REQUEST['attendee'];
    $attendee_list = serialize($attendee_array);
    $items = $wpdb->get_results( "SELECT * FROM ". get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC" );
      if ($items){
      foreach ($items as $item){
            $item_id          = $item->id;
            $item_sequence    = $item->sequence;
   			$event_id         = $item->event_id;
            $item_title       = $item->item_title;
            $item_description = $item->item_description; 
            $item_cat         = $item->item_cat;
            $item_limit       = $item->item_limit;
            $item_price       = $item->item_price;
            $free_item        = $item->free_item;
            $item_start_date  = $item->item_available_start_date;
            $item_end_date    = $item->item_available_end_date;
            $item_custom_cur  = $item->item_custom_cur;
            if ($item_custom_cur == "GBP"){$item_custom_cur = "&pound;";}
            if ($item_custom_cur == "USD"){$item_custom_cur = "$";} 
            $item_post = str_replace(".", "_", $item_price);
            $item_qty = $_REQUEST['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];
            if ($item_cat == "REG"){$num_people = $num_people + $item_qty;}
            $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat'=>$item_cat,
                'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
                $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
                'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
            array_push($item_order, $item_info);
            }}
    $ticket_data = serialize($item_order);
     $sql=array('lname'=>$lname, 'fname'=>$fname, 'address'=>$address, 'city'=>$city, 
                'state'=>$state, 'zip'=>$zip, 'reg_type'=>$reg_type, 'email'=>$email,
                'phone'=>$phone, 'email'=>$email, 'coupon'=>$coupon, 'event_id'=>$event_id,
                'quantity'=>$num_people, 'tickets'=>$ticket_data, 'payment'=>$payment,'attendees'=>$attendee_list);
     $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s');
        	 $update_id = array('id'=> $attendee_id);
            if ($wpdb->update( get_option('evr_attendee'), $sql, $update_id, $sql_data, array( '%d' ) )){?>
            	<div id="message" class="updated fade"><p><strong><?php _e('The attendee has been added.','evr_language');?> </strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The attendee was not saved!','evr_language');?><?php $wpdb->last_error; ?>.</strong></p>
                <p><strong><?php _e(' . . .Now returning you to attendee list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                </div>
                <?php } 
    // Insert Extra From Post Here
    $reg_id = $attendee_id;
    $questions = $wpdb->get_results("SELECT * from ".get_option('evr_question')." where event_id = '$event_id'");
    if ($questions) {
        ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Now adding extra question responses.','evr_language');?> </strong></p></div>
        <?php
        foreach ($questions as $question) {
            ?>
            <div id="message" class="updated fade"><p><strong><?php _e('Now adding a question responses.','evr_language');?> </strong></p></div>
            <?php
            switch ($question->question_type) {
            				case "TEXT" :
            				case "TEXTAREA" :
            				case "DROPDOWN" :
            					$post_val = $_REQUEST [$question->question_type . '_' . $question->id];
            				
                                $sql = "INSERT INTO " . get_option('evr_answer') . " (answer,registration_id,question_id) VALUES ('$post_val', '$reg_id', '$question->id') ON DUPLICATE KEY UPDATE answer='$post_val'"; 
                                
            					$wpdb->query ($sql);
            					break;
            				case "SINGLE" :
            					$post_val = $_REQUEST [$question->question_type . '_' . $question->id];
            					$sql = "INSERT INTO " . get_option('evr_answer') . " (answer,registration_id,question_id) VALUES ('$post_val', '$reg_id', '$question->id') ON DUPLICATE KEY UPDATE answer='$post_val'"; 
                                $wpdb->query ($sql);
            					break;
            				case "MULTIPLE" :
            					$value_string = '';
            					for ($i=0; $i<count($_REQUEST[$question->question_type.'_'.$question->id]); $i++){ 
            					$value_string .= $_REQUEST[$question->question_type.'_'.$question->id][$i].","; 
            					}
            					$sql = "INSERT INTO " . get_option('evr_answer') . " (answer,registration_id,question_id) VALUES ('$value_string', '$reg_id', '$question->id') ON DUPLICATE KEY UPDATE answer='$value_string'"; 
                                $wpdb->query ($sql);
            					break;
            			}
        }
        ?>
    <div id="message" class="updated fade"><p><strong><?php _e('The attendee extra questions responses have been added.','evr_language');?> </strong></p></div>
<?php 
}
?>
   <div id="message" class="updated fade"><p><strong><?php _e(' . . .Now returning you to attendee list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
<?php                
}
?>