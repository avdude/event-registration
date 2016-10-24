<?php
function evr_admin_payment_update(){
    global $wpdb, $company_options;
    $payment_id = $_REQUEST['payment_id'];
                $payer_id = $_REQUEST['attendee_id'];
                //$event_id = $_REQUEST['event_id'];
                (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $payer_email = $_REQUEST['payer_email'];
                $txn_id = $_REQUEST['txn_id'];
                $payment_type = $_REQUEST['payment_type'];
                $item_name = $_REQUEST['item_name'];
                $item_number = $_REQUEST['item_number'];
                $quantity = $_REQUEST['quantity'];

                
				
                
                if (isset($_REQUEST['payer_status'])){
				    $payer_status = $_REQUEST['payer_status'];
                    }else{
				    $payer_status = "manual";
   					}
                if (isset($_REQUEST['payment_status'])){
				    $payment_status = $_REQUEST['payment_status'];
                    }else{
				    $payment_status = "manually posted";
   					}
				
                
                $txn_type = $_REQUEST['txn_type'];
                            
                if (isset($_REQUEST['mc_currency'])){
				     $mc_currency = $_REQUEST['mc_currency'];
                     $currency_format =$_REQUEST['mc_currency'];
                    }else{
				    $mc_currency =$company_options['default_currency'];
                    $currency_format = $company_options['default_currency'];
   					}
                  
                if (isset($_REQUEST['memo'])){
				    $memo = $_REQUEST['memo'];
                    }else{
				    $memo = $_REQUEST['memo'];
   					}      
                $payment_date = $_REQUEST['payment_date'];
                if (isset($_REQUEST['mc_gross'])){
				    $amount_pd = $_REQUEST['mc_gross'];
                    }else{
				    $amount_pd = $_REQUEST['payment_gross'];
   					}
     			$mc_gross=$amount_pd;
     			$address_name = $_REQUEST['address_name'];
     			$address_street = $_REQUEST['address_street'];
     			$address_city = $_REQUEST['address_city'];
     			$address_state = $_REQUEST['address_state'];
     			$address_zip = $_REQUEST['address_zip'];
     			$address_country = $_REQUEST['address_country'];
     			$address_status = $_REQUEST['address_status'];
                if (isset($_REQUEST['payer_business_name'])){
				    $payer_business_name = $_REQUEST['payer_business_name'];
                    }else{
				    $payer_business_name = "None Specified";
   					}
     			//$payer_business_name = $_REQUEST['payer_business_name'];
     			if (isset($_REQUEST['pending_reason'])){
				    $pending_reason = $_REQUEST['pending_reason'];
                    }else{
				    $pending_reason = "manual posting";
   					}
                 if (isset($_REQUEST['reason_code'])){
				    $reason_code = $_REQUEST['reason_code'];
                    }else{
				    $reason_code = "not specified";
   					}
                
                $send_payment_rec = $_REQUEST['send_payment_rec'];   
                 
                
                
                
                               
                $sql=array('payer_id'=>$payer_id, 'event_id'=>$event_id, 'payment_date'=>$payment_date, 'txn_id'=>$txn_id, 
                            'first_name'=>$first_name, 'last_name'=>$last_name, 'payer_email'=>$payer_email, 'payer_status'=>$payer_status,
                            'payment_type'=>$payment_type, 'memo'=>$memo, 'item_name'=>$item_name, 'item_number'=>$item_number,
                            'quantity'=>$quantity, 'mc_gross'=>$mc_gross, 'mc_currency'=>$mc_currency, 'address_name'=>$address_name,
                            'address_street'=>$address_street, 'address_city'=>$address_city, 'address_state'=>$address_state, 'address_zip'=>$address_zip,
                            'address_country'=>$address_country, 'address_status'=>$address_status, 'payer_business_name'=>$payer_business_name, 'payment_status'=>$payment_status,
                            'pending_reason'=>$pending_reason, 'reason_code'=>$reason_code, 'txn_type'=>$txn_type);
                 $payment_dtl=array('payer_id'=>$payer_id, 'event_id'=>$event_id, 'payment_date'=>$payment_date, 'txn_id'=>$txn_id, 
                            'first_name'=>$first_name, 'last_name'=>$last_name, 'payer_email'=>$payer_email, 'payer_status'=>$payer_status,
                            'payment_type'=>$payment_type, 'memo'=>$memo, 'item_name'=>$item_name, 'item_number'=>$item_number,
                            'quantity'=>$quantity, 'mc_gross'=>$mc_gross, 'mc_currency'=>$mc_currency, 'address_name'=>$address_name,
                            'address_street'=>$address_street, 'address_city'=>$address_city, 'address_state'=>$address_state, 'address_zip'=>$address_zip,
                            'address_country'=>$address_country, 'address_status'=>$address_status, 'payer_business_name'=>$payer_business_name, 'payment_status'=>$payment_status,
                            'pending_reason'=>$pending_reason, 'reason_code'=>$reason_code, 'txn_type'=>$txn_type);              
     $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s',
                       '%s','%s','%s','%s','%s','%s','%s','%s','%s');
        $update_id = array('id'=> $payment_id);
            if ($wpdb->update( get_option('evr_payment'), $sql, $update_id, $sql_data, array( '%d' ) )){?>
        	         	<div id="message" class="updated fade"><p><strong><?php _e('The payment has been updated.','evr_language');?> </strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The payment was not updated!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p>
                <p><strong><?php _e(' . . .Now returning you to the payment section . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=admin.php?page=payments&action=view_payments&event_id=<?php echo $event_id;?>"></strong></p>
                </div>
                <?php } 
   	if ($send_payment_rec == "Y") {	
                    $attendee_dtl = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_attendee') ." WHERE id = %d",$payer_id)); 
                    $event_dtl = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$event_id)); 
					//get return URL
					$payment_link = evr_permalink($company_options['return_url']). "id=".$payment_dtl['payer_id']."&fname=".$payment_dtl['first_name'];
                    $payment_cue = __("To make payment or view your payment information go to",'evr_language');
                    $payment_text = $payment_cue.": " . $payment_link;
					$subject = stripslashes($company_options['payment_subj']);
					$distro = $payer_email;
                    $ticket_order = unserialize($attendee_dtl->tickets);
					$message = html_entity_decode(nl2br($company_options['payment_message']));
                        $SearchValues = array("[id]","[fname]","[lname]","[payer_email]","[event_name]", "[contact]",
                        "[payment_url]", "[amnt_pd]","[txn_id]","[txn_type]","[address_street]","[address_city]",
                        "[address_state]","[address_zip]","[address_country]","
                        [start_date]","[start_time]","[end_date]","[end_time]" );
                    $ReplaceValues = array($payment_dtl['payer_id'], $payment_dtl['first_name'], $payment_dtl['last_name'],$payment_dtl['payer_email'], 
                        stripslashes($event_dtl->event_name), $company_options['company_email'], $payment_link, $payment_dtl['mc_gross'], 
                        $payment_dtl['txn_id'], $payment_dtl['txn_type'], $payment_dtl['address_street'], $payment_dtl['address_city'], 
                        $payment_dtl['address_state'], $payment_dtl['address_zip'], $payment_dtl['address_country'], $event_dtl->start_date, 
                        $event_dtl->start_time, $event_dtl->end_date, $event_dtl->end_time);
                       
                          
                    $email_content = str_replace($SearchValues, $ReplaceValues, $message);
                    //$email_content .= "</br>".$payment_text;
                    $message_top = "<html><body>"; 
                    $message_bottom = "</html></body>";
                    $email_body = $message_top.$email_content.$message_bottom;
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= 'From: "' . stripslashes($company_options['company_name']) . '" <' . $company_options['company_email'] . ">\r\n";
                    wp_mail($attendee_dtl->email, stripslashes($subject), html_entity_decode($email_body), $headers);
                   ?>
				<div id="message" class="updated fade"><p><strong><?php _e('Payment Received Notification sent.','evr_language');?> </strong></p></div>
                <?php
				}
            ?>
            <META HTTP-EQUIV="refresh" content="3;URL=admin.php?page=payments&action=view_payments&event_id=<?php echo $event_id;?>">
            <?php
}
?>