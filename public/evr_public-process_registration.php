<?php
//This is an outdated function
function evr_process_regform(){
     
    global $wpdb, $company_options;
    //$company_options = get_option('evr_company_settings');
    $num_people = 0;
    $item_order = array();
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    if ($event_id = "0"){echo "Failure, Please try again!"; exit;}
    
    //Begin gather registrtion data for database input
    $fname = $_REQUEST['fname'];
    $lname = $_REQUEST['lname'];
    $address = $_REQUEST['address'];
    $city = $_REQUEST['city'];
    $state = $_REQUEST['state'];
    $zip = $_REQUEST['zip'];
    $phone = $_REQUEST['phone'];
    $email = $_REQUEST['email'];
    $payment = $_REQUEST['total'];
    $coupon = $_REQEUST['coupon'];
    $reg_type = $_REQUEST['reg_type'];
    
    
   
          
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
            
            
            
                 
            $item_post = str_replace(".", "_", $item->item_price);
            $item_qty = $_REQUEST['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];
            
            if ($item_cat == "REG"){$num_people = $num_people + $item_qty;}
            
            $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat'=>$item_cat,
                'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
                $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
                'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
            array_push($item_order, $item_info);
            
            }
            }
    
    $ticket_data = serialize($item_order);

     $sql=array('lname'=>$lname, 'fname'=>$fname, 'address'=>$address, 'city'=>$city, 
                'state'=>$state, 'zip'=>$zip, 'reg_type'=>$reg_type, 'email'=>$email,
                'phone'=>$phone, 'email'=>$email, 'coupon'=>$coupon, 'event_id'=>$event_id,
                'quantity'=>$num_people, 'tickets'=>$ticket_data, 'payment'=>$payment);
					  
					  
            
                          
        		
     $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        	
        
    if ($wpdb->insert( get_option('evr_attendee'), $sql, $sql_data )){ 
                
                // Insert Extra From Post Here
            $reg_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
            $questions = $wpdb->get_results("SELECT * from ".get_option('evr_question')." where event_id = '$event_id'");
            if ($questions) {
                  foreach ($questions as $question) {
                    switch ($question->question_type) {
                        case "TEXT":
                        case "TEXTAREA":
                        case "DROPDOWN":
                            $post_val = $_POST[$question->question_type . '_' . $question->id];
                            $wpdb->query("INSERT into ".get_option('evr_answer')." (registration_id, question_id, answer)
        					values ('$reg_id', '$question->id', '$post_val')");
                            break;
                        case "SINGLE":
                            $post_val = $_POST[$question->question_type . '_' . $question->id];
                            $wpdb->query("INSERT into ".get_option('evr_answer')." (registration_id, question_id, answer)
        					values ('$reg_id', '$question->id', '$post_val')");
                            break;
                        case "MULTIPLE":
                            $value_string = '';
                            for ($i = 0; $i < count($_POST[$question->question_type . '_' . $question->id]);
                                $i++) {
                                $value_string .= $_POST[$question->question_type . '_' . $question->id][$i] .",";
                            }
                            
                            $wpdb->query("INSERT into ".get_option('evr_answer')." (registration_id, question_id, answer)
        					values ('$reg_id', '$question->id', '$value_string')");
                            break;
                        }
                    }
                }    
                
    }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again.','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p>
          
                </div>
                <?php break;
                } 
    
  
   _e("You're information has been received.",'evr_language');
   echo " ";
   
   
   
   $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$event_id));
 
        $event_id = $event->id;
        $reg_form_defaults = unserialize($event->reg_form_defaults);
        if ($reg_form_defaults !=""){
            if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
            if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
            if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
            if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
            if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
            }
        $use_coupon = $event->use_coupon;
        $reg_limit = $event->reg_limit;
        $event_name = stripslashes($event->event_name);
        $event_identifier = stripslashes($event->event_identifier);
        $display_desc = $event->display_desc;  // Y or N
        $event_desc = stripslashes($event->event_desc);
        $event_category = unserialize($_REQUEST['event_category']);
        $reg_limit = $event->reg_limit;
        $event_location = $event->event_location;
        $event_address = $event->event_address;
        $event_city = $event->event_city;
        $event_state =$event->event_state;
        $event_postal=$event->event_postcode;
        $google_map = $event->google_map;  // Y or N
        $start_month = $event->start_month;
        $start_day = $event->start_day;
        $start_year = $event->start_year;
        $end_month = $event->end_month;
        $end_day = $event->end_day;
        $end_year = $event->end_year;
        $start_time = $event->start_time;
        $end_time = $event->end_time;
        $allow_checks = $event->allow_checks;
        $outside_reg = $event->outside_reg;  // Yor N
        $external_site = $event->external_site;
        
        $more_info = $event->more_info;
        $image_link = $event->image_link;
        $header_image = $event->header_image;
        $event_cost = $event->event_cost;
        $allow_checks = $event->allow_checks;
        $is_active = $event->is_active;
        $send_mail = $event->send_mail;  // Y or N
        $conf_mail = stripslashes($event->conf_mail);
        $start_date = $event->start_date;
        $end_date = $event->end_date;
        $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id=%d",$event_id));
        if ($number_attendees == '' || $number_attendees == 0){
            $number_attendees = '0';
            }
        if ($reg_limit == "" || $reg_limit == " "){
            $reg_limit = "Unlimited";}
        $available_spaces = $reg_limit;

                               

//End EVent Details
   
//Send Confirmation Email   
   //Select the default message
   if ($company_options['send_confirm']=="Y"){
      if ($send_mail == "Y"){
            $confirmation_email_body = $conf_mail;
           }
        else{ $confirmation_email_body = $company_options['message'];}
        
            
    $payment_link = evr_permalink($company_options['return_url']). "id=".$reg_id."&fname=".$fname;
    //search and replace tags
    $SearchValues = array("[id]","[fname]", "[lname]", "[phone]", "[event]",
        "[description]", "[cost]", "[currency]",
        "[contact]", "[company]", "[co_add1]", "[co_add2]", "[co_city]", "[co_state]",
        "[co_zip]", "[payment_url]", "[start_date]", "[start_time]", "[end_date]",
        "[end_time]", "[snum]", "[num_people]");

    $ReplaceValues = array($reg_id, $fname, $lname, $phone, $event_name, $event_desc, $payment,
        $custom_cur, $company_options['company_email'], $company_options['company'], $company_options['company_street1'], $company_options['company_street2'],
        $company_options['city'], $company_options['state'], $company_options['postal'],$payment_link , $start_date,
        $start_time, $end_date, $end_time, $attnum, $quantity);

    $email_content = str_replace($SearchValues, $ReplaceValues, $confirmation_email_body);
    $message_top = "<html><body>"; 
    $message_bottom = "</html></body>";
    $wait_message =  '<font color="red"><p>'.__("Thank you for registering for",'evr_language')." ".$event_name.". ".__("At this time, all seats for the event have been taken.  
    Your information has been placed on our waiting list.  
    The waiting list is on a first come, first serve basis.  
    You will be notified by email should a seat become available.",'evr_language').'</p><p>'.__("Thank You",'evr_language').'</p></font>';
    
    if ($reg_type=="WAIT"){$email_content = $wait_message;}
    $email_body = $message_top.$email_content.$message_bottom;
            
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= 'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n";
    
    wp_mail($email, $event_name, html_entity_decode($email_body), $headers);
    
}
//End Send Confirmation Email    
   
   
//Provide screen feedback on registration process   
   if($reg_type=="WAIT"){
    echo "<p>";
    _e("At this time, all seats for the event have been taken.  Your information has been placed on our waiting list.  The waiting list is on a first come, first serve basis.  You will be notified by email should a seat become available.",'evr_language');
    echo "</p>";
   }
   
   if ($payment > "0"){
             _e("Registration, however, is not complete until we have received your payment.",'evr_language'); 
           echo " ";
           if ($company_options['checks']=="Y"){
                _e("You may pay online or by check.  If you are paying by check, please mail your check today to:",'evr_language');
                echo "<p>".
                $company_options['company']."<br />".
                $company_options['company_street1']."<br />".
                $company_options['company_street2']."<br />".
                $company_options['company_city']." ".$company_options['company_state']." ".$company_options['company_postal']."</p>";
            }     
           _e("Please select the Pay Now button to be taken to our payment vendor's site for online-payment.",'evr_language'); $company_options['company_email']     = $_POST['email'];
           evr_registration_payment($event_id, $reg_id);
           }
}
?>