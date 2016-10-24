<?php
function evr_admin_payments_edit(){
    global $wpdb;
                        $payment_id = $_REQUEST['id'];
                        //$event_id = $_REQUEST['event_id'];
                        (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
                        $today = date("Y-m-d");
                        $payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_payment') ." WHERE id = %d",$payment_id));
                        $attendee = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_attendee') ." WHERE id = %d",$payment->payer_id));
                        $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$payment->event_id));
                        $total_paid = $wpdb->get_var($wpdb->prepare("SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE payer_id=%d",$payment->payer_id));
                        //TODO: Need to get $event_cost
                         if ($event->use_coupon =="Y" && $event_cost > "0" ) {
                                if ($attendee->coupon == $event->coupon_code) {$discount = $event->coupon_code_price;}
                                else {$discount = "0";}
                            }
                         if ($attendee->payment > "0" ){$balance = ($attendee->payment - $total_paid);
                         $balance = evr_moneyFormat($balance);}
                         else if ($event->event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}
?>    
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Payment Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:auto;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3 class='hndle'><span><p><?php _e('Add Payment:','evr_language');?><?php echo "  ".$attendee->fname." ".$attendee->lname." ".$attendee->email."    ";?></p><p><?php _e('Balance Owed:','evr_language'); echo "  ".$balance;?></p></span></h3>
                         <div class="inside">
                         <div class="padding">
                         <ul>
                         <form method="post" action="admin.php?page=payments">
                        				<li><label><?php _e('Payment Received Date','evr_language');?></label>: <input name="payment_date" size="15"	value="<?php echo $payment->payment_date;?>"/></li>
                                        <li><label><?php _e('Amount Paid','evr_language');?></label>: <input name="mc_gross" size="25" value="<?php echo $payment->mc_gross;?>"></li>
                                        <li><label><?php _e('Payment Type','evr_language');?></label>
                                         <select name="payment_type">
                                         <?php 
                                         if ($payment->payment_type =="full"){ ?><option value="full"><?php _e('Full Payment','evr_language');?></option><?php }
                                         if ($payment->payment_type =="partial") {?><option value="partial"><?php _e('Partial Payment','evr_language');?></option><?php }
                                         if ($payment->payment_type =="deposit"){?><option value="deposit"><?php _e('Deposit','evr_language');?></option><?php }
                                         if ($payment->payment_type =="donation"){?><option value="donation"><?php _e('Donation','evr_language');?></option><?php }
                                         if ($payment->payment_type =="modify"){?><option value="modify"><?php _e('Modification','evr_language');?></option><?php }
                                         if ($payment->payment_type =="cancel"){?><option value="cancel"><?php _e('Cancelation','evr_language');?></option><?php }
                                         if ($payment->payment_type =="refund"){?><option value="refund"><?php _e('Refund','evr_language');?></option><?php }
                                         ?>
                                                                <option value="full"><?php _e('Full Payment','evr_language');?></option>
                                                                  <option value="partial"><?php _e('Partial Payment','evr_language');?></option>
                                                                  <option value="deposit"><?php _e('Deposit','evr_language');?></option>
                                                                  <option value="donation"><?php _e('Donation','evr_language');?></option>
                                                                  <option value="modify"><?php _e('Modification','evr_language');?></option>
                                                                  <option value="cancel"><?php _e('Cancelation','evr_language');?></option>
                                                                  <option value="refund"><?php _e('Refund','evr_language');?></option>  
                                                                </select></li>
                                        <li><label><?php _e('Payment Method','evr_language');?></label>: <select name="txn_type">
                                        <?php
                                        if ($payment->txn_type =="online"){ ?> <option value="online"><?php _e('Online/Credit Card','evr_language');?></option><?php }
                                        if ($payment->txn_type =="check"){ ?> <option value="check"><?php _e('Check','evr_language');?></option><?php }
                                        if ($payment->txn_type =="cash"){ ?> <option value="cash"><?php _e('Cash','evr_language');?></option><?php }
                                        if ($payment->txn_type =="credit"){ ?> <option value="credit"><?php _e('Event Credit','evr_language');?></option><?php }
                                        ?>
                                                                  <option value="online"><?php _e('Online/Credit Card','evr_language');?></option>
                                                                  <option value="check"><?php _e('Check','evr_language');?></option>
                                                                  <option value="cash"><?php _e('Cash','evr_language');?></option>
                                                                  <option value="credit"><?php _e('Event Credit','evr_language');?></option>
                                                                </select> </li>
                                        <li><label><?php _e('Transaction ID','evr_language');?></label>
                                        : <input name="txn_id" size="45" value="<?php echo $payment->txn_id;?>"/> </li>
                                        <li><label><?php _e('Memo','evr_language');?></label>
                                        : <input name="memo" size="65" value="<?php echo $payment->memo;?>"/> </li><br />
                        <hr />
                        <br />
                        <li><label><?php _e('Do you want to send a revised payment received notice to the payer?','evr_language');?></label>
                        <INPUT TYPE='radio' NAME='send_payment_rec'  VALUE='Y'><?php _e('Yes','evr_language');?> 
                        <INPUT TYPE='radio' NAME='send_payment_rec' VALUE='N' CHECKED><?php _e('No','evr_language');?></li>
                        <br />
                        <hr />
                        <p align="center">
                        <?php
                        				echo "<input type='hidden' name='attendee_id' value='" . $attendee->id . "'>";
                        				echo "<input type='hidden' name='form_action' value='payment'>";
                                        echo "<input type='hidden' name='payment_id' value='".$payment->id."'>";
                                        echo "<input type='hidden' name='first_name' value='".$attendee->fname."'>";
                                        echo "<input type='hidden' name='last_name' value='".$attendee->lname."'>";
                                        echo "<input type='hidden' name='payer_email' value='".$attendee->email."'>";
                                        echo "<input type='hidden' name='address_name' value='".$attendee->fname." ".$attendee->lname."'>";
                                        echo "<input type='hidden' name='address_street' value='".$attendee->address."'>";
                                        echo "<input type='hidden' name='address_city' value='".$attendee->city."'>";
                                        echo "<input type='hidden' name='address_state' value='".$attendee->state."'>";
                                        echo "<input type='hidden' name='address_zip' value='".$attendee->zip."'>";
                                        echo "<input type='hidden' name='address_country' value='".$attendee->state."'>";
                                        echo "<input type='hidden' name='address_status' value='VALID'>";  
                                        echo "<input type='hidden' name='item_name' value='Event Payment: ".$event->event_name." (".$event_id.")'>";   
                                        echo "<input type='hidden' name='item_number' value='".$event_id."'>";  
                                        echo "<input type='hidden' name='quantity' value='1'>";        
                        				echo "<input type='hidden' name='attendee_pay' value='paynow'>";
                        				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
                        				echo "<input type='hidden' name='action' value='update_payment'>";
                        				?>
                        <hr/><p><input type="submit" name="Submit" value="<?php _e('UPDATE PAYMENT','evr_language');?>"/></p>
                        </form>
                        </p></ul>
                            </div>                           
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
<?php    
}
?>