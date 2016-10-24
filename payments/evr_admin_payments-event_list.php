<?php
function evr_payment_event_listing(){
global $wpdb, $company_options;
//define # of records to display per page
if ($company_options['eventpaging'] >= "1"){
                   //define # of records to display per page
                    $record_limit = $company_options['eventpaging'];  
                } else
                {
                    //define # of records to display per page
                    $record_limit = 10; 
                }
//get today's date to sort records between current & expired'
$curdate = date("Y-m-d");
//initiate connection to wordpress database.


?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Payment Management','evr_language');?></h2>
<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div class='postbox-container' style='width:auto;'>
        <div id='normal-sortables' class='meta-box-sortables'>
            <div id="dashboard_right_now" class="postbox " >
                 
                <h3 class='hndle'><span><?php _e('Events','evr_language');?></span></h3>
                <?php
                //check database for number of records with date of today or in the future

                
                $items = $wpdb->get_var( 'SELECT COUNT(*) FROM '.get_option('evr_event' ));
                
                	if($items > 0) {
                		$p = new evr_pagination;
                		$p->items($items);
                		$p->limit($record_limit); // Limit entries per page
                		$p->target("admin.php?page=payments");
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
                            <?php if($items > 0) {echo $p->show();}  // Echo out the list of paging. ?>
                        </div>
                    </div>
                         <table class="widefat">
                         <thead>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Event</th>
                            <th>Location / City</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Sales</th>
                            <th>Payments</th>
                            <th>Outstanding</th>
                            <th>Manage</th>
                            </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Event</th>
                            <th>Location / City</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Sales</th>
                            <th>Payments</th>
                             <th>Outstanding</th>
                            <th>Manage</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        	//$sql = "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit;
                    		$rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                            if ($rows){
                            	foreach ($rows as $event){
                            		$event_id       = $event->id;
                                    $event_id       = $event->id;
                    				$event_name     = stripslashes($event->event_name);
                    				$event_location = stripslashes($event->event_location);
                                    $event_address  = $event->event_address;
                                    $event_city     = $event->event_city;
                                    $event_postal   = $event->event_postal;
                                    $reg_limit      = $event->reg_limit;
                            		$start_time     = $event->start_time;
                            		$end_time       = $event->end_time;
                            		$conf_mail      = $event->conf_mail;
                                    //$custom_mail    = $event->custom_mail;
                            		$start_date     = $event->start_date;
                            		$end_date       = $event->end_date;
                          
                            $balance_sql    = "SELECT SUM(payment) FROM " . get_option('evr_attendee') . " WHERE event_id=%d";
                            $attendee_sql   = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id=%d";
                            $payment_sql    = "SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE event_id=%d";
                            $payment_due        = $wpdb->get_var( $wpdb->prepare( $balance_sql,$event_id ));
                            $number_attendees   = $wpdb->get_var( $wpdb->prepare( $attendee_sql,$event_id ));
            				$payments_recieved  = $wpdb->get_var( $wpdb->prepare( $payment_sql,$event_id ));
                            
                            $outstanding = $payment_due-$payments_recieved; 
                            
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
            				
            			 $exp_date = $end_date;
                               $todays_date = date("Y-m-d");
                               $today = strtotime($todays_date);
                               $expiration_date = strtotime($exp_date);
                               
                             if ($expiration_date <= $today){
            					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED EVENT','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE EVENT','evr_language').'</span>';
            				}   
                        	?>
                            <tr></tr>
                          <tr>
                            <td style="white-space: nowrap;"><?php echo $start_date; ?></td>
                            <td><?php echo $event_id; ?></td>
                            <td><?php echo $event_name; ?></td>
                            <td><?php echo $event_location; ?><br /><?php echo $event_city; ?></td>
                            <td><?php echo $active_event ; ?></td>
                            <td><?php echo $number_attendees;?> / <?php echo $reg_limit?></td>
                            <td><?php echo evr_moneyFormat($payment_due);?></td>
                            <td><?php echo evr_moneyFormat($payments_recieved);?></td>
                            <td><?php echo evr_moneyFormat($outstanding);?></td>
                            <td>
                            <div style="float:left">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="view_payments"/>
                                <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                <input class="button-primary" type="submit" name="Payments" value="<?php _e('Payments','evr_language');?>" />
                              </form>
                            </div>
                            </td>
                            </tr>
                            <tr></tr>
                          <?php
                        	}
                         } else { ?>
                          <tr>
                            <td>No Record Found!</td>
                          <tr>
                            <?php	}?>
                          </tbody>
                        </table>
                        <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) {echo $p->show();}  // Echo out the list of paging. ?>
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