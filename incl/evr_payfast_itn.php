<?php

function evr_payfast_itn()
{
    global $wpdb, $company_options;
    $today = date("m-d-Y");
    evr_createCompanyArray();
    //$company_options = get_option('evr_company_settings');
    $events_attendee_tbl = get_option('evr_attendee');
        
    $id="";
    $id=$_REQUEST['id'];//This is the id of the registrant
    include('payfast_common.inc');


            
            $events_attendee_tbl = get_option('evr_attendee');
            
            $today = date("m-d-Y"); 
            $payment_date = $today;


                // Variable Initialization
                $pfError = false;
                $pfErrMsg = '';
                $pfDone = false;
                $pfData = array();
                $pfHost = ( (  $company_options['payfast_sandbox'] ) ? '' : 'sandbox.' ) . 'payfast.co.za';
                $pfOrderId = '';
                $pfParamString = '';
                
                

                pflog( 'PayFast ITN call received' );
                
              

                //// Get data sent by PayFast
                if( !$pfError && !$pfDone )
                {
                    pflog( 'Get posted data' );
                
                    // Posted variables from ITN
                    $pfData = pfGetData();
                
                    pflog( 'PayFast Data: '. print_r( $pfData, true ) );
                
                    if( $pfData === false )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_BAD_ACCESS;
                    }
                }

                //// Verify security signature
                if( !$pfError && !$pfDone )
                {
                    pflog( 'Verify security signature' );
                
                    // If signature different, log for debugging
                    if( !pfValidSignature( $pfData, $pfParamString ) )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_INVALID_SIGNATURE;
                    }
                }

                //// Verify source IP (If not in debug mode)
                if( !$pfError && !$pfDone && !PF_DEBUG )
                {
                    pflog( 'Verify source IP' );
                
                    if( !pfValidIP( $_SERVER['REMOTE_ADDR'] ) )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_BAD_SOURCE_IP;
                    }
                }

              

                //// Verify data received
                if( !$pfError )
                {
                    pflog( 'Verify data received' );
                
                    $pfValid = pfValidData( $pfHost, $pfParamString );
                
                    if( !$pfValid )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_BAD_ACCESS;
                    }
                }
                    
                //// Check data against internal order
                /*
                if( !$pfError && !$pfDone )
                {
                   // pflog( 'Check data against internal order' );

                    // Check order amount
                    if( !pfAmountsEqual( $pfData['amount_gross'], $cart->getOrderTotal() ) )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_AMOUNT_MISMATCH;
                    }
                    // Check secure ID
                    elseif( strcasecmp( $pfData['custom_str1'], $cart->secure_key ) != 0 )
                    {
                        $pfError = true;
                        $pfErrMsg = PF_ERR_SESSIONID_MISMATCH;
                    }
                }
                */

                //// Check status and update order
                if( !$pfError && !$pfDone )
                {
                    pflog( 'Check status and update order' );

                    $sessionid = $pfData['custom_str1'];
                    $transaction_id = $pfData['pf_payment_id'];
                    
                  

                    switch( $pfData['payment_status'] )
                    {
                        case 'COMPLETE':
                            pflog( '- Complete' );
                            extract($pfData);
                            // Update the purchase status
                             $sql="UPDATE ". $events_attendee_tbl . " SET payment_status = 'Completed', amount_pd = '$amount_gross', payment_date ='$payment_date' WHERE id = '$id'";
                            pflog( 'Update SQL: '.$sql);
                            $wpdb->query($wpdb->prepare("UPDATE ". $events_attendee_tbl . " SET payment_status = 'Completed', amount_pd = '$amount_gross', payment_date ='$payment_date' WHERE id = %d",$id));
                                          
                                
                                
                                
                                (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
                                
                                
                                $row = $wpdb->get_row("SELECT * FROM $wpdb->links WHERE link_id = 10", ARRAY_A);
                                
                                
                                            $event_id       = $row['id'];
                                            $event_name     = stripslashes($row['event_name']);
                                            $event_location = stripslashes($row['event_location']);
                                            $event_address  = $row['event_address'];
                                            $event_city     = $row['event_city'];
                                            $event_postal   = $row['event_postal'];
                                            $reg_limit      = $row['reg_limit'];
                                            $start_time     = $row['start_time'];
                                            $end_time       = $row['end_time'];
                                            $start_date     = $row['start_date'];
                                            $end_date       = $row['end_date'];
                                            //added 6.00.13
                                            $send_coord = $row['send_coord'];
                                            $coord_email = $row['coord_email'];
                                            $coord_msg = stripcslashes($row['coord_msg']);
                                            $coord_pay_msg = stripslashes($row['coord_pay_msg']);
                                            pflog( 'Event Data: '. print_r($row,true));
                                   

                                $attendee = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_attendee') ." WHERE id = %d",$id));
                                
                                        $attendee_email = $attendee->email;
                                        $f_name = $attendee->fname;
                                        $l_name = $attendee->lname;
                                 
                                
                                $events_paypal_transactions_tbl = get_option('evr_payment');
                                //Store transaction details in the database
                                $sql=array('payer_id'=>$id, 'event_id'=>$event_id, 'payment_date'=>$payment_date, 'txn_id'=>$pf_payment_id, 'first_name'=>$f_name,
                 'last_name'=>$l_name, 'payer_email'=>$pfData['email_address'], 'payer_status'=>NULL, 'payment_type'=>'PayFast', 'memo'=>NULL, 
                 'item_name'=>$item_name, 'item_number'=>$m_payment_id, 'quantity'=>1,
                 'mc_gross'=>$amount_gross, 'mc_currency'=>'ZAR', 'address_name'=>NULL, 'address_street'=>NULL, 
                 'address_city'=>NULL, 'address_state'=>NULL, 
                 'address_zip'=>NULL, 'address_country'=>NULL, 'address_status'=>NULL, 
                 'payer_business_name'=>$f_name.' '.$l_name, 'payment_status'=>'Completed', 
                 'pending_reason'=>NULL, 'reason_code'=>NULL, 'txn_type'=>'PayFast Payment' );        
                                $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
     '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
                     
                     
                            
                        
                    //$wpdb->insert( get_option('evr_payment'), $sql, $sql_data );
                      if ($wpdb->insert( get_option('evr_payment'), $sql, $sql_data )){
                            $headers = "From: " . $company_options['company'] . " <". $company_options['company_email'] . ">\r\n";
                            $headers .= "Reply-To: " . $company_options['company'] . "  <" . $company_options['company_email'] . ">\r\n";
                          $details = "";
                          foreach ($pfData as $key => $value) { $details .= "\n$key: $value"; }
                          $paydate = date('m/d/Y');
                          $paytime = date('g:i A');
                          $SearchValues =  array( "[payer_email]","[fname]","[lname]","[attendee_email]","[event_name]", "[event_id]","[details]","[pay_date]", "[pay_time]");
                          $ReplaceValues = array($pfData['email_address'],$f_name, $l_name, $attendee_email, $event_name, $event_id, $details, $paydate, $paytime);
                         
                                if ($send_coord == "Y") {
                                 $subject = 'Instant Payment Notification - Success';
                                 $email_content = str_replace($SearchValues, $ReplaceValues, $coord_pay_msg ); 
                                 wp_mail($coord_email, html_entity_decode($subject), html_entity_decode($email_content),$headers);
                                }
                                else {       
                        
                                $subject = 'Instant Payment Notification - Success';
                                 $body =  "An instant payment notification was successfully posted\n";
                                  $body .= "from ".$pfData['email_address']." on behalf of ".$f_name." ".$l_name;
                                 $body .= " for event ".$event_name."(".$event_id.")"." on ".date('m/d/Y');
                                 $body .= " at ".date('g:i A')."\n\nDetails:\n";
                                 foreach ($pfData as $key => $value) { $body .= "\n$key: $value"; }
                                 wp_mail($contact, $subject, $body,$headers);} 
                                 }
                                 else {
                                    if ($send_coord == "Y") {
                                 $subject = 'Instant Payment Notification - Failure';
                                 $body =  "An instant payment notification was received but not posted!\n";
                                 $body .= "from ".$pfData['email_address']." on behalf of ".$f_name." ".$l_name;
                                 $body .= " for event ".$event_name."(".$event_id.")"." on ".date('m/d/Y');
                                 $body .= " at ".date('g:i A')."\n\nDetails:\n";
                                 foreach ($pfData as $key => $value) { $body .= "\n$key: $value"; }
                                 wp_mail($coord_email, $subject, $body,$headers);
                                }
                                else {       
                        
                                     $subject = 'Instant Payment Notification - Failure';
                                 $body =  "An instant payment notification was received but not posted!\n";
                                  $body .= "from ".$pfData['email_address']." on behalf of ".$f_name." ".$l_name;
                                 $body .= " for event ".$event_name."(".$event_id.")"." on ".date('m/d/Y');
                                 $body .= " at ".date('g:i A')."\n\nDetails:\n";
                                 foreach ($pfData as $key => $value) { $body .= "\n$key: $value"; }
                                 wp_mail($contact, $subject, $body,$headers);
                                 }
                                 }  
                                 
                         
                                         
                                       //$attendee_email   = "consultant@avdude.com";
                                        $email_subject    = $company_options['payment_subj'];
                                        $payment_msg      = stripslashes($company_options['payment_message']);
                                        $pay_confirm      = $company_options['pay_confirm'];
                                        $Organization     = $company_options['company'];
                                        $contact          = $company_options['company_email'];
                              
                            $headers .= "From: " . $Organization . " <". $contact . ">\r\n";
                            $headers .= "Reply-To: " . $Organization . "  <" . $contact . ">\r\n";
                            
                            
                            
                            if ($send_coord == "Y") {
                                $contact = $coord_email;
                            } 
                            else 
                            {
                                $contact = $company_options['company_email'];
                            }
                            $payment_link = evr_permalink($company_options['return_url']). "id=".$id."&fname=".$f_name;
                         
                            $SearchValues = array(  "[id]","[fname]", "[lname]", "[contact]", "[payer_email]", "[event_name]", 
                                          "[event_id]","[location]","[event_city]","[amnt_pd]", "[txn_id]",
                                          "[payment_url]","[start_date]", "[start_time]", "[end_date]","[end_time]",
                                          "[email]");

                            $ReplaceValues = array($id, $f_name, $l_name, $contact, $payer_email,$event_name,
                                          $event_id, $event_location, $event_city, $amount_pd,$txn_id,
                                          $payment_link, $start_date,$start_time, $end_date, $end_time,
                                          $attendee_email);
                                          
                                
                            //Replace the tags
                            $email_content = str_replace($SearchValues, $ReplaceValues, $payment_msg );            

                                       
                                                            
                                
                            
                                

                            if ($pay_confirm =='Y')
                            { 
                                wp_mail($attendee_email, html_entity_decode($email_subject),html_entity_decode($email_content),$headers);
                             }
                                             
                             
                                 
                          
                            
                            break;

                        case 'FAILED':
                            pflog( '- Failed' );

                          

                            break;

                        case 'PENDING':
                            pflog( '- Pending' );

                            // Need to wait for "Completed" before processing
                            break;

                        default:
                            // If unknown status, do nothing (safest course of action)
                        break;
                    }
                }

                // If an error occurred
                if( $pfError )
                {
                    pflog( 'Error occurred: '. $pfErrMsg );
                    exit();
                }

                // Close log
                pflog( '', true );
                
            










           
}