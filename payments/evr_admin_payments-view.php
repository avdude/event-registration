<?php
function evr_admin_view_payments(){
    global $wpdb, $company_options;
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id = $event_id";
    $event = $wpdb->get_row($sql);
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
?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Payment Management','evr_language');?></h2>
<form name="form" method="post" action="admin.php?page=payments">
                                    <input class="button-primary" type="submit" name="Select Different" value="<?php _e('Select A Different Event','evr_language');?>" />
                                </form>  
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:auto;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3 class='hndle'><span><?php _e('View Payments:','evr_language');?><?php echo "  ".$event_name;?></span></h3>
                        <?php
                            if ($company_options['eventpaging'] >= "1"){
                               //define # of records to display per page
                                $record_limit = $company_options['attendeepaging'];  
                            } else
                            {
                                //define # of records to display per page
                                $record_limit = 25; 
                            }
                            //check database for number of records with date of today or in the future
                            
                            
                            $items = $wpdb->get_var( 'SELECT COUNT(*) FROM '.get_option('evr_attendee' ));
                            	if($items > 0) {
                            		$p = new evr_pagination;
                            		$p->items($items);
                            		$p->limit($record_limit); // Limit entries per page
                            		$p->target("admin.php?page=payments&action=view_payments&event_id=".$event_id);
                            		$p->currentPage((string)@$_GET[$p->paging]); // Gets and validates the current page
                            		$p->calculate(); // Calculates what to show
                            		$p->parameterName('paging');
                            		$p->adjacents(1); //No. of page away from the current page
                            		if(!isset($_GET['paging'])) {
                            			$p->page = 1;
                            		} else {
                            			$p->page = $_GET['paging'];
                            		}
                            		//Query for limit paging
                            		$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
                            } else {
                            	echo "No Record Found";
                            }//End pagination
                            ?>
                         <div class="inside">
                            <div class="padding"> 
                            <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php echo $p->show();  // Echo out the list of paging. ?>
                        </div>
                    </div>  
                         <form name="form" method="post" action="admin.php?page=payments">
                                    <input class="button-primary" type="submit" name="email_reminders" value="<?php _e('Email Payment Reminders','evr_language');?>" />
                                    <input type="hidden" name="action" value="email_reminders"/>
                                    <input type="hidden" name="event_id" value="<?php  echo $event_id;?>"/>
                                </form>  
                            <table class="widefat">
                                <thead>
                                <tr><th><?php _e('# People','evr_language');?></th><th><?php _e('Name','evr_language');?> </th><th><?php _e('Total','evr_language');?></th><th><?php _e('Order Detail','evr_language');?></th><th><?php _e('Payments','evr_language');?></th><th><?php _e('Action','evr_language');?></th></tr>
                                </thead>
                                <tfoot>
                                <tr><th><?php _e('# People','evr_language');?></th><th><?php _e('Name','evr_language');?> </th><th><?php _e('Total','evr_language');?></th><th><?php _e('Order Detail','evr_language');?></th><th><?php _e('Payments','evr_language');?></th><th><?php _e('Action','evr_language');?></th></tr>
                                </tfoot>
                                <tbody>
                                <?php
                            $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id' ORDER BY lname ASC ".$limit;
                            $rows = $wpdb->get_results( $sql );
                            if ($rows){
                            	foreach ($rows as $attendee){
                                    $attendee_id = $attendee->id;
                                    $lname = stripslashes($attendee->lname);
                        			$fname = stripslashes($attendee->fname);
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
                                    $payment= $attendee->payment;
                                    $event_id = $attendee->event_id;
                                    $coupon = $attendee->coupon;
                    			echo "<tr><td>".$quantity."</td><td align='left'>" . $lname . ", " . $fname . "</td><td>" . $payment . "</td><td>";
                                $row_count = count($ticket_order);
                                    for ($row = 0; $row < $row_count; $row++) {
                                       echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                                       } 
                                echo "</td>";
                                echo "<td>";
                                $sql = "SELECT * FROM " . get_option('evr_payment') . " WHERE payer_id='$attendee_id' ";
                                $rows = $wpdb->get_results( $sql );
                                if ($rows){
                                	foreach ($rows as $payment){
                                		echo  $payment->mc_currency." ".$payment->mc_gross." ".$payment->txn_type." ".$payment->txn_id." (".$payment->payment_date.")"."     ".
                                '<a href="admin.php?page=payments&action=delete_payment&event_id='.$event_id.'&id='.$payment->id.'">'.__('Delete','evr_language').'</a>   |  '.
                                '<a href="admin.php?page=payments&action=edit_payment&event_id='.$event_id.'&id='.$payment->id.'">'.__('Edit','evr_language').'</a><br />';
                                	}
                                }
                                else {
                                    echo '<font color="red">';
                                _e('No Payments Received!','evr_language');
                                echo '</font>';}
                                ?>
                                </td><td>
                                <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="add_payment"/>
                                <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                <input type="hidden" name="attendee_id" value="<?php echo $attendee_id?>">
                                <input class="button-primary" type="submit" name="Add Payment" value="<?php _e('Add Payment','evr_language');?>" />
                                </form>
                                </td> </tr>
                                <?php   } } 
                            echo "</table>";
                            ?>
                             <?php evr_payment_export($event_id);?>
                            <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php echo $p->show();  // Echo out the list of paging. ?>
                        </div>
                    </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful, please contribute to enable its continued development!<br />
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div> 
<?php
}
?>