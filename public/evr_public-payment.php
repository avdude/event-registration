<?php
function evr_registration_payment($passed_event_id, $passed_attendee_id){
    global $wpdb, $company_options;
    //$company_options = get_option('evr_company_settings');
    if (is_numeric($passed_event_id)){$event_id = $passed_event_id;}
    else {
        $event_id = "0";
        _e('Failure - please retry!','evr_language');
        exit;}
    if (is_numeric($passed_attendee_id)){$attendee_id = $passed_attendee_id;}
    else {
        $attendee_id = "0";
        _e('Failure - please retry!','evr_language');
         exit;}
    //Get Event Info
    $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$event_id));
    $event_id       = $event->id;
    $event_name     = stripslashes($event->event_name);
    $event_location = $event->event_location;
    $event_address  = $event->event_address;
    $event_city     = $event->event_city;
    $event_postal   = $event->event_postal;
    $reg_limit      = $event->reg_limit;
    $start_time     = $event->start_time;
    $end_time       = $event->end_time;
    $start_date     = $event->start_date;
    $end_date       = $event->end_date;
    $use_coupon         = $event->use_coupon;
    $coupon_code        = $event->coupon_code;
    $coupon_code_price  = $event->coupon_code_price;
    $attendee = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_attendee') ." WHERE id = %d",$attendee_id));
    $attendee_id = $attendee->id;
    $lname = $attendee->lname;
    $fname = $attendee->fname;
    $address = $attendee->address;
    $city = $attendee->city;
    $state = $attendee->state;
    $zip = $attendee->zip;
    $email = $attendee->email;
    $phone = $attendee->phone;
    $quantity = $attendee->quantity;
    $date = $attendee->date;
    $reg_type = $attendee->reg_type;
    $ticket_order = unserialize($attendee->tickets);
    $tax = $attendee->tax;
    $payment= $attendee->payment;
    $event_id = $attendee->event_id;
    $coupon = $attendee->coupon;
    $attendee_name = $fname." ".$lname;  
    $item_custom_cur  = $ticket_order[0]['ItemCurrency'];
    if ($item_custom_cur == "GBP"){$item_display_cur = "&pound;";}
    elseif ($item_custom_cur == "USD"){$item_display_cur = "$";} 
    else {$item_display_cur = $item_custom_cur;} 
    //Get Payment Info
    if ($company_options['pay_now']!=""){$pay_now = $company_options['pay_now'];} else {$pay_now = "PAY NOW";}
    if ($company_options['payment_vendor']==""||$company_options['payment_vendor']=="NONE"){
// Print the Order Verification to the screen.
     echo stripslashes($company_options['pay_msg']); 
     echo '<br/>';
     echo "Reference ".$event_name." ID: ".$event_id."<br/>";
     echo '<br/>';
     echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$display_custom_cur.' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $item_display_cur . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$item_custom_cur." ".'<strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
}                   
//Paypal 
if ($company_options['payment_vendor']=="PAYPAL"){
    $p = new paypal_class;// initiate an instance of the class
    if ($company_options['use_sandbox'] == "Y") {
		$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
		echo '<h3 style="color:#ff0000;" title="'.__('Payments will not be processed','evr_language').'">'.__('Sandbox Mode Is Active','evr_language').'</h3>';
	}else {
		$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
	}
    	if ($payment != "0.00" || $payment != "0" || $payment != "" || $payment != " "){
            $p->add_field('business', $company_options['paypal_id']);
			 //$p->add_field('return', evr_permalink($company_options['return_url']));
			//$p->add_field('cancel_return', evr_permalink($company_options['cancel_return']));
                  $p->add_field('return', evr_permalink($company_options['return_url']).'&id='.$attendee_id.'&fname='.$fname);
				  //$p->add_field('cancel_return', evr_permalink($company_options['cancel_return']));
				  $p->add_field('cancel_return', evr_permalink($company_options['return_url']).'&id='.$attendee_id.'&fname='.$fname);
                  //$p->add_field('notify_url', evr_permalink($company_options['notify_url']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
				  $p->add_field('notify_url', evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
				  //$p->add_field('notify_url', evr_permalink($company_options['notify_url']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
				  //$p->add_field('notify_url', evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
                  $p->add_field('item_name', $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity);
				  $p->add_field('amount', $payment);
				  $p->add_field('currency_code', $company_options['default_currency']);
				  //Post variables
				  $p->add_field('first_name', $fname);
				  $p->add_field('last_name', $lname);
				  $p->add_field('email', $email);
				  $p->add_field('address1', $address);
				  $p->add_field('city', $city);
				  $p->add_field('state', $state);
				  $p->add_field('zip', $zip);				 
                echo stripslashes($company_options['pay_msg']); 
     echo '<br/>';
                echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$item_display_cur.' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $item_display_cur. " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].' <strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
                echo '<br/>';
                 $p->submit_paypal_post($pay_now); // submit the fields to paypal
                 ?>
                <br /><div style="display:block;"><img src="<?php echo EVR_PLUGINFULLURL?>/img/paypal_mc_visa_amex_disc_echeck_253x80.gif"  /></div>
                <?php
	            if ($company_options['use_sandbox'] == "Y") {
					  $p->dump_fields(); // for debugging, output a table of all the fields
	           }   
      }
}
 //End Paypal Section
//Authorize.Net Payment Section
if ($company_options['payment_vendor']=="AUTHORIZE"){
        //Authorize.Net Payment 
        // This sample code requires the mhash library for PHP versions older than
        // 5.1.2 - http://hmhash.sourceforge.net/
        // the parameters for the payment can be configured here
        // the API Login ID and Transaction Key must be replaced with valid values
        //$loginID		= $company_options['authorize_id'];
        //$transactionKey = $company_options['authorize_key'];
        $amount 		= $payment;
        $description 	= $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity;
        $label 			= $pay_now; // The is the label on the 'submit' button
        // By default, this sample code is designed to post to our test server for
        // developer accounts: https://test.authorize.net/gateway/transact.dll
        // for real accounts (even in test mode), please make sure that you are
        // posting to: https://secure.authorize.net/gateway/transact.dll
        if ($company_options['use_authorize_sandbox'] == "Y"){ 
            $loginID		= $company_options['authorize_sandbox_id'];
            $transactionKey = $company_options['authorize_sandbox_key'];
            $url            = "https://test.authorize.net/gateway/transact.dll";
            }
        else {
            $loginID		= $company_options['authorize_id'];
            $transactionKey = $company_options['authorize_key'];
            $url			= "https://secure.authorize.net/gateway/transact.dll";
            }
        if ($company_options['use_authorize_testmode']=="Y"){
            $testMode = "true";
        }
        else {
            $testMode = "false";
            }
        // If an amount or description were posted to this page, the defaults are overidden
        if ($_REQUEST["amount"])
        	{ $amount = $_REQUEST["amount"]; }
        if ($_REQUEST["description"])
        	{ $description = $_REQUEST["description"]; }
        // an invoice is generated using the date and time
        $invoice	= date(YmdHis);
        // a sequence number is randomly generated
        $sequence	= rand(1, 1000);
        // a timestamp is generated
        $timeStamp	= time ();
        // The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
        // newer have the necessary hmac function built in.  For older versions, it
        // will try to use the mhash library.
        if( phpversion() >= '5.1.2' )
        {	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
        else 
        { $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }
        if ($company_options['pay_msg'] !=""){ echo stripslashes($company_options['pay_msg']); }
     else { _e("To pay online, please select the Payment button to be taken to our payment vendor's site.",'evr_language'); }
     echo '<br/>';
        // Print the Order Verification to the screen.
        echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$item_display_cur.' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $item_display_cur . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>&nbsp;'.number_format($payment,2).'</strong></td></tr></table><br />';
                $ipn_url  = evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=authorize_txn';  
        // Create the HTML form containing necessary SIM post values
        echo "<FORM method='post' action='$url' >";
        // Additional fields can be added here as outlined in the SIM integration guide
        // at: http://developer.authorize.net
        echo "	<INPUT type='hidden' name='x_login' value='$loginID' />";
        if ($price == "0"){echo "Enter Amount $<INPUT type='text' name='x_amount' value='10.00' />";}
        else { echo "	<INPUT type='hidden' name='x_amount' value='$amount' />";}
        echo "	<INPUT type='hidden' name='x_description' value='$description' />";
        echo "	<INPUT type='hidden' name='x_cust_id' value='$attendee_id' />";
        echo "	<INPUT type='hidden' name='x_invoice_num' value='$invoice' />";
        echo "	<INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";
        echo "	<INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
        echo "	<INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";
        echo "	<INPUT type='hidden' name='x_test_request' value='$testMode' />";
        echo "	<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
        echo "  <INPUT type='hidden' name='x_receipt_link_method' value='POST' />";
        echo "	<INPUT type='hidden' name='x_receipt_link_url' value='$ipn_url' />";
        echo "	<input type='submit' value='$label' />";
        echo "</FORM>";
// This is the end of the code generating the "submit payment" button.    -->
?>
<br /><div style="display:block;"><img src="<?php echo EVR_PLUGINFULLURL?>/img/logo_authorize.gif"  /></div>
<?php
if ($company_options['use_authorize_sandbox'] == "Y"){
    ?>
    <h3>Authorize.Net Sandbox Field Output:</h3>
<table width="98%" border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td bgcolor="black"><b><font color="white">Field Name</font></b></td>
    <td bgcolor="black"><b><font color="white">Value</font></b></td>
  </tr>
  <tr>
    <td>ID Key</td>
    <td><?php echo $loginID;?></td>
  </tr>
  <tr>
    <td>Txn Key</td>
    <td><?php echo $transactionKey;?></td>
  </tr>
  <tr>
    <td>Submit URL</td>
    <td><?php echo $url;?></td>
  </tr>
  <tr>
    <td>Description</td>
    <td><?php echo $description;?></td>
  </tr>
  <tr>
    <td>Amount</td>
    <td><?php echo $amount;?></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><?php echo $email;?></td>
  </tr>
  <tr>
    <td>IPN Url</td>
    <td><?php echo evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=authorize_txn';?></td>
  </tr>
  <tr>
    <td>Invoice</td>
    <td><?php echo $invoice;?></td>
  </tr>
    <tr>
    <td>Timestamp</td>
    <td><?php echo $timeStamp;?></td>
  </tr>
    <tr>
    <td>Sequence</td>
    <td><?php echo $sequence;?></td>
  </tr>
    <tr>
    <td>Fingerprint</td>
    <td><?php echo $fingerprint;?></td>
  </tr>
</table>
    <?php
}
}
//End Authorize.Net Section 
//GooglePay Payment Section
    if ($company_options['payment_vendor']=="GOOGLE"){
    // Print the Order Verification to the screen.
     echo $company_options['pay_msg']; 
     echo '<br/>';
        // Print the Order Verification to the screen.
        echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$ticket_order[0]['ItemCurrency'].' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
        // Create the HTML Payment Button
    // is this a test transaction?
	if ( $company_options['google_sandbox'] == "Y" )
		$post_url = 'https://sandbox.google.com/checkout/api/checkout/v2/checkoutForm/Merchant/' . $company_options['google_id'];
	else
		$post_url = 'https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/' . $company_options['google_id'];
?>
<?php
    //Google Payment Button
    ?>
    	<form name="paymentform" method="post" action="<?php echo esc_url( $post_url ) ?>" accept-charset="utf-8">
		<input type="hidden" name="item_name_1" value="<?php echo $event_name."-".$attendee_name;?>" />
		<input type="hidden" name="item_description_1" value="<?php echo $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity;?>" />
		<input type="hidden" name="item_merchant_id_1" value="<?php echo esc_attr( $company_options['google_id'] ); ?>"/>
		<input type="hidden" name="item_price_1" value="<?php echo $payment;?>" />
		<input type="hidden" name="item_currency_1" value="<?php echo $company_options['default_currency'];?>" />
		<input type="hidden" name="item_quantity_1" value="1" />
		<input type="hidden" name="_charset_" value="utf-8" />
		<input type="hidden" name="continue_url" value="<?php echo esc_attr( $back_url ); ?>"/>
		<input type="hidden" name="shopping-cart.items.item-1.digital-content.url" value="<?php echo esc_attr( $back_url ); ?>" />
		<input type="image" src="<?php echo plugins_url( '/images/gcheckout-button.png', __FILE__ ); ?>" name="submit" />
	</form> 
    <?php
}
//End Google Pay Section
//Begin Monster Pay Section
if ($company_options['payment_vendor']=="MONSTER"){
    // Print the Order Verification to the screen.
    echo '<p>'.stripslashes($company_options['pay_msg']).'</p>'; 
    echo '<br/>';
       // Print the Order Verification to the screen.
        echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$ticket_order[0]['ItemCurrency'].' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>&nbsp;'.number_format($payment,2).'</strong></td></tr></table><br />';    
//End Verification
//Display Payment Button
?>    
<form action="https://www.monsterpay.com/secure/index.cfm" method="POST" enctype="APPLICATION/X-WWW-FORM-URLENCODED" target="_BLANK">
<input type="hidden" name="ButtonAction" value="buynow"/>
<input type="hidden" name="MerchantIdentifier" value="<?php echo $company_options['monster_id'];?>"/>
<input type="hidden" name="LIDDesc" value="<?php echo $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity;?>"/>
<input type="hidden" name="LIDSKU" value="<?php echo $event_name."-".$attendee_name;?>"/>
<input type="hidden" name="LIDPrice" value="<?php echo $payment;?>"/>
<input type="hidden" name="LIDQty" value="1"/>
<input type="hidden" name="CurrencyAlphaCode" value="<?php echo $company_options['default_currency'];?>"/>
<input type="hidden" name="ShippingRequired" value="0"/>
<input type="hidden" name="MerchRef" value=""/>
<input type="submit" value="<?php echo $pay_now;?>" />
</form> 
<br /><div style="display:block;"><img src="<?php echo EVR_PLUGINFULLURL?>/img/logo_monster.png"  /></div>
<?php   
}
//End Monster Pay Section
// Begin PayFast Payment
if ($company_options['payment_vendor']=="PAYFAST"){
                // Print the Order Verification to the screen.
                if ($company_options['pay_msg'] !=""){ echo $company_options['pay_msg']; }
                 else { _e("To pay online, please select the Payment button to be taken to our payment vendor's site.",'evr_language'); }
                 echo '<br/>';
                   // Print the Order Verification to the screen.
                    echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                            _e(' Event Name/Cost:','evr_language');
                            echo '</strong></td><td>'.$event_name.' - '.$ticket_order[0]['ItemCurrency'].' '.$payment.'</td></tr><tr><td><strong>';
                            _e('Attendee Name:','evr_language');
                            echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                            _e('Email Address:','evr_language');
                            echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                            _e('Number of Attendees:','evr_language');
                            echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                            _e('Order Details:','evr_language');
                            echo '</strong></td><td>';
                            $row_count = count($ticket_order);
                                for ($row = 0; $row < $row_count; $row++) {
                                    if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                                        echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                                        $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                                        }
                                    } 
                            echo '</td></tr>';
                            if ($company_options['use_sales_tax'] == "Y"){ 
                                echo '<tr><td></td><td>';
                                _e('Sales Tax  ','evr_language'); 
                                echo ':  '.$tax;
                                echo '</td></tr>';
                                } 
                            echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                            echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
            //End Verification
            if($company_options['payfast_sandbox'])
            {
                $merchantID = '10000100';
                $merchantKey = '46f0cd694581a';
                $url = 'sandbox.payfast.co.za';
            }
            else
            {
                $merchantID = $company_options['payfast_merchant_id'];
                $merchantKey = $company_options['payfast_merchant_key'];
                $url = 'www.payfast.co.za';
            }
            $html = "";
            $html .= '<form action="https://'.$url.'/eng/process" method="post">';
            $varArray = array(
                'merchant_id'=>$merchantID,
                'merchant_key'=>$merchantKey,
                'return_url'=> $company_options['payfast_return'],
                'cancel_url'=> $company_options['payfast_cancel'],
                'notify_url'=> evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=payfast_itn',
                'm_payment_id'=>$attendee_id,
                'amount'=>$payment,
                'item_name'=> $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity,
                'item_description'=>$event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$quantity
            );
            $secureString = '';
            foreach($varArray as $k=>$v)
            {
                $html.= '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
                $secureString .= $k.'='.urlencode(trim($v)).'&';
            }
            $secureString = substr( $secureString, 0, -1 );
            $secureSig = md5($secureString);
            $html .= '<input type="hidden" name="signature" value="'.$secureSig.'" />';
            $html .= '<div style="float:right;">Pay now with:&nbsp;<input title="Click Here to Pay" type="image" src="https://www.payfast.co.za/images/logo/PayFast_Logo_75.png" align="bottom" /></div>';
            $html .= '</form>';
            echo $html;
            ?>
            <br /><br />
            <?
            if($company_options['payfast_sandbox']){
                echo '<h3>PayFast Sandbox Field Output:</h3><table width="98%" border="1" cellpadding="2" cellspacing="0">
                        <tr><td bgcolor="black"><b><font color="white">Field Name</font></b></td>
                        <td bgcolor="black"><b><font color="white">Value</font></b></td>';
                foreach($varArray as $k=>$v)
                {
                echo '<tr><td>'.$k.'</td><td>'.$v.'</td></tr>';
                }
                echo '</table>';
              }
}
// End PayFast Payment
//Begin UPay Section
if ($company_options['payment_vendor']=="TOUCHNET"){
    // Print the Order Verification to the screen.
    echo $company_options['pay_msg']; 
    echo '<br/>';
       // Print the Order Verification to the screen.
        echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$ticket_order[0]['ItemCurrency'].' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
//End Verification
//Display Payment Button
?>
<form name="doit" action="<?php echo $company_options['upay_url'];?>" method="post">
<input type="hidden" name="UPAY_SITE_ID" value="<?php echo $company_options['upay_id'];?>">
    <input type="hidden" name="AMT" value="<?php echo $payment;?>">
    <input type="hidden" name="BILL_NAME" value="<?php echo $attendee_name;?>">
    <input type="hidden" name="BILL_EMAIL_ADDRESS" value="<?php echo $email;?>">
    <input type="hidden" name="SUCCESS_LINK" value="<?php echo $company_options['upay_success']; ?>" />
    <input type="hidden" name="CANCEL_LINK" value="http://ttapdev.eit.mtu.edu/nttc2013/nttc-2013-online-payment/?id=<?php echo $attendee_id;?>&fname=<?php echo $fname;?>" /> 
<input type="submit" value="Pay Now"/>
</form> 
<?php   
}
//End UPay Section
}
function evr_registration_donation($passed_event_id, $passed_attendee_id){
    global $wpdb, $company_options;
    //$company_options = get_option('evr_company_settings');
    if (is_numeric($passed_event_id)){$event_id = $passed_event_id;}
    else {
        $event_id = "0";
        echo "Failure - please retry!"; 
        exit;}
    if (is_numeric($passed_attendee_id)){$attendee_id = $passed_attendee_id;}
    else {
        $attendee_id = "0";
        echo "Failure - please retry!"; 
        exit;}
    //Get Event Info
    //Get Event Info
    $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$event_id));
    $event_id       = $event->id;
    $event_name     = stripslashes($event->event_name);
    $event_location = $event->event_location;
    $event_address  = $event->event_address;
    $event_city     = $event->event_city;
    $event_postal   = $event->event_postal;
    $reg_limit      = $event->reg_limit;
    $start_time     = $event->start_time;
    $end_time       = $event->end_time;
    $start_date     = $event->start_date;
    $end_date       = $event->end_date;
    $use_coupon         = $event->use_coupon;
    $coupon_code        = $event->coupon_code;
    $coupon_code_price  = $event->coupon_code_price;
//get attendee info
    $attendee = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_attendee') ." WHERE id = %d",$attendee_id));
    $attendee_id = $attendee->id;
    $lname = $attendee->lname;
    $fname = $attendee->fname;
    $address = $attendee->address;
    $city = $attendee->city;
    $state = $attendee->state;
    $zip = $attendee->zip;
    $email = $attendee->email;
    $phone = $attendee->phone;
    $quantity = $attendee->quantity;
    $date = $attendee->date;
    $reg_type = $attendee->reg_type;
    $ticket_order = unserialize($attendee->tickets);
    $tax = $attendee->tax;
    $payment= $attendee->payment;
    $event_id = $attendee->event_id;
    $coupon = $attendee->coupon;
    $attendee_name = $fname." ".$lname; 
    //Get Donate Info
    if ($company_options['donations']=="Yes"){ $pay_now = "MAKE A DONATION";}
    elseif ($company_options['pay_now']!=""){$pay_now = $company_options['pay_now'];} 
    else {$pay_now = "PAY NOW";}
//Paypal 
    if ($company_options['payment_vendor']=="PAYPAL"){
    $p = new paypal_class;// initiate an instance of the class
    if ($company_options['use_sandbox'] == "Y") {
		$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
		echo "<h3 style=\"color:#ff0000;\" title=\"Payments will not be processed\">Sandbox Mode Is Active</h3>";
	}else {
		$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
	}
    	if (($payment == "0.00" || $payment == "0" || $payment == "" || $payment == " ")&&($company_options['donations']=="Yes")){
				  $p->add_field('business', $company_options['payment_vendor_id']);
				  $p->add_field('return', evr_permalink($company_options['return_url']).'&id='.$attendee_id.'&fname='.$fname);
				  //$p->add_field('cancel_return', evr_permalink($company_options['cancel_return']));
				  $p->add_field('cancel_return', evr_permalink($company_options['return_url']).'&id='.$attendee_id.'&fname='.$fname);
                  //$p->add_field('notify_url', evr_permalink($company_options['notify_url']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
				  $p->add_field('notify_url', evr_permalink($company_options['evr_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
                   $p->add_field('cmd', '_donations');
                  $p->add_field('item_name', 'Donation - '.$event_name );
				  $p->add_field('no_note', '0');
				  $p->add_field('currency_code', $company_options['default_currency']);
				  //Post variables
				  $p->add_field('first_name', $fname);
				  $p->add_field('last_name', $lname);
				  $p->add_field('email', $email);
				  $p->add_field('address1', $address);
				  $p->add_field('city', $city);
				  $p->add_field('state', $state);
				  $p->add_field('zip', $zip);				 
                // Print the Order Verification to the screen.
        echo '<p align="left"><strong>'.__('Order details:','evr_language').'</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e(' Event Name/Cost:','evr_language');
                echo '</strong></td><td>'.$event_name.' - '.$ticket_order[0]['ItemCurrency'].' '.$payment.'</td></tr><tr><td><strong>';
                _e('Attendee Name:','evr_language');
                echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>';
                _e('Email Address:','evr_language');
                echo '</strong></td><td>'.$email.'</td></tr><tr><td><strong>';
                _e('Number of Attendees:','evr_language');
                echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>';
                _e('Order Details:','evr_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                    for ($row = 0; $row < $row_count; $row++) {
                        if ($ticket_order[$row]['ItemQty'] >= "1"){ 
                            echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".
                            $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                            }
                        } 
                echo '</td></tr>';
                if ($company_options['use_sales_tax'] == "Y"){ 
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ','evr_language'); 
                    echo ':  '.$tax;
                    echo '</td></tr>';
                    } 
                echo '<tr><td><strong>'.__('Total Cost:','evr_language').'</strong></td>';
                echo '<td>'.$ticket_order[0]['ItemCurrency'].'<strong>'.number_format($payment,2).'</strong></td></tr></table><br />';    
                $p->submit_paypal_post($pay_now); // submit the fields to paypal
				  if ($company_options['use_sandbox'] == "Y") {
					  $p->dump_fields(); // for debugging, output a table of all the fields
				  }   
			}
 }
 //End Paypal Donation Section
}
?>