<?php
function evr_admin_payments(){
    $action = (string)@$_REQUEST['action'];
    switch ($action) {
    case "view_payments":
    evr_admin_view_payments();
    //evr_check_form_submission();
    break;
    case "add_payment":
    evr_admin_payments_add();
    break;
    case "post_payment":
    //evr_check_form_submission();
    evr_admin_payment_post();
    break;
    case "edit_payment":
    evr_admin_payments_edit();
    break;
    case "update_payment":
    //evr_check_form_submission();
    evr_admin_payment_update();
    break;
    case "delete_payment":
    //evr_check_form_submission();
    evr_delete_payment();
    break;
    case "email_reminders":
    //evr_check_form_submission();
    evr_send_payment_reminders ();
    break;
    default:
    evr_payment_event_listing();
    }
}
function evr_send_payment_reminders ($event_id = ''){
    if (isset($_REQUEST['event_id'])){
    $curdate = date("Y-m-d");
    global $wpdb, $company_options;
    $event_id = $_REQUEST['event_id'];
    $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
    $attendees = $wpdb->get_results( $sql );
    foreach ( $attendees as $attendee ){
    	if ($attendee->payment >= '.01'){
    	   //$payment_sql     = "SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE payer_id=".$attendee->id;
           $payment_recieved    = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE payer_id= %d",$attendee->id ));
           $balance_due     = $attendee->payment - $payment_recieved;
           if ($balance_due >= '.01'){
            //echo $attendee->fname." ".$attendee->lname." owes ".$balance_due."<br/>";
   				 //$company_options = get_option('evr_company_settings');
                 $payment_link = evr_permalink($company_options['return_url']). "id=".$attendee->id."&fname=".$attendee->fname;
                    $payment_cue = __("A balance is outstanding on your event registration fees.  Please pay to complete your registration process.",'evr_language');
                    $payment_text = $payment_cue.": " . $payment_link;
					$subject = __('Payment Reminder','evr_language');
					$distro = $attendee->email;
                        $SearchValues = array("[id]","[fname]","[lname]","[payer_email]","[event_name]", "[contact]",
                        "[payment_url]", "[amnt_pd]","[txn_id]","[txn_type]","[address_street]","[address_city]",
                        "[address_state]","[address_zip]","[address_country]","
                        [start_date]","[start_time]","[end_date]","[end_time]" );
                       $ReplaceValues = array($payment_dtl['payer_id'], $payment_dtl['first_name'],$payment_dtl['last_name'],$payment_dtl['payer_email'],stripslashes($event_dtl['event_name']), $company_options['company_email'], 
                       $payment_link, $payment_dtl['mc_gross'], $payment_dtl['txn_id'], $payment_dtl['txn_type'],$payment_dtl['address_street'], $payment_dtl['address_city'], 
                       $payment_dtl['address_state'],$payment_dtl['address_zip'],$payment_dtl['address_country'], 
                       $event_dtl['start_date'], $event_dtl['start_time'],$event_dtl['end_date'],$event_dtl['end_time'], );     
                    //$email_content = str_replace($SearchValues, $ReplaceValues, $message);
                    $email_content = $payment_text;
                    $message_top = "<html><body>"; 
                    $message_bottom = "</html></body>";
                    $email_body = $message_top.$email_content.$message_bottom;
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= 'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n";
                    wp_mail($distro, $subject, html_entity_decode($email_body), $headers);
                   ?>
				<div id="message" class="updated fade"><p><strong>
                <?php _e('Payment Reminder Notification sent to','evr_language'); 
                echo " ".$attendee->fname." ".$attendee->lname." | ".$attendee->email." | ";
                echo " Amount due: ".$balance_due;?> 
                </strong></p></div>
                <?php
           }
           else { 
            //echo $attendee->fname." ".$attendee->lname." paid ".$payment_recieved."<br/>"; 
           }
    	}
    }
   } ?>
   <form name="form" method="post" action="admin.php?page=payments">
   <input class="button-primary" type="submit" name="Select Different" value="<?php _e('Select Another Event','evr_language');?>" />
   </form> 
   <?php
}
?>