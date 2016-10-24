<?php
function evr_new_event_button(){
     ?>
     <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="new">
                                <input class="evr_button evr_add" type="submit" name="new" value="<?php  _e('ADD EVENT','evr_language');?>" />
     </form>
     <?php
}
//function that displays events with option buttons
function evr_event_listing(){
//initiate connection to wordpress database.
global $wpdb, $evr_date_format,$company_options;
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
?>
<div class="wrap">
<h2 style="font-family: segoe;"><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL; ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
<?php evr_new_event_button();?>
 <?php
                //check database for number of records with date of today or in the future
              
               	
                $items = $wpdb->get_var( 'SELECT COUNT(*) FROM '.get_option('evr_event' ));
                //$items = $wpdb->num_rows;
                               
                	if($items > 0) {
                		$p = new evr_pagination;
                		$p->items($items);
                		$p->limit($record_limit); // Limit entries per page
                		$p->target("admin.php?page=events");
                		if(!isset($_GET['paging'])) {
                			$p->page = 1;
                		} else {
                			$p->page = $_GET['paging'];
                		}
                        $p->currentPage($p->page); // Gets and validates the current page
                		$p->calculate(); // Calculates what to show
                		$p->parameterName('paging');
                		$p->adjacents(1); //No. of page away from the current page
                		//Query for limit paging
                		$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
                } else {
                	echo "No Record Found";
                }//End pagination
                ?>
                    <div class="padding">
                    <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) { echo $p->show(); } // Echo out the list of paging. ?>
                        </div>
                    </div>
                         <table class="widefat">
                         <thead>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Name</th>
                            <th>ShortCode</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Manage</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Name</th>
                            <th>ShortCode</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Manage</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        <?php
                       	$rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                          if ($rows){
                            foreach ($rows as $event){
                                        $event_id       = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                                        if((get_option('evr_location_active')=="Y") && ( $event->location_list >= '1')){
                                            $sql = "SELECT * FROM " . get_option('evr_location')." WHERE id =".$event->location_list;
                                            $location = $wpdb->get_row( $sql, OBJECT );//default object
                                            //$object->field;
                                            if( !empty( $location ) ) {
                                            $event_location = stripslashes($location->location_name);
                                            $event_address  = $location->street;
                                            $event_city     = $location->city;
                                            $event_state    = $location->state;
                                            $event_postal   = $location->postal;
                                            $event_phone    = $location->phone;
                                            }
                                        } else {
                                        $event_location = stripslashes($event->event_location);
                                        $event_address  = $event->event_address;
                                        $event_city     = $event->event_city;
                                        $event_postal   = $event->event_postal;
                                        }
                                        $reg_limit      = $event->reg_limit;
                                		$start_time     = $event->start_time;
                                		$end_time       = $event->end_time;
                                		$conf_mail      = $event->conf_mail;
                                        //$custom_mail    = $event->custom_mail;
                                		$start_date     = $event->start_date;
                                		$end_date       = $event->end_date;
                                        $event_close    = $event->close;
                            $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id= %d",$event_id));
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
                               //$current_dt= date('Y-m-d H:i a',current_time('timestamp',0));
$current_dt= date('Y-m-d H:i',current_time('timestamp',0));
if ($event_close == "start"){$close_dt = $start_date." ".$start_time;}
else if ($event_close == "end"){$close_dt = $end_date." ".$end_time;}
else if ($event_close == ""){$close_dt = $start_date." ".$start_time;}
$stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
$expiration_date = strtotime($stp);
$today = strtotime($current_dt);
if ($expiration_date <= $today){
           					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE','evr_language').'</span>';
            				}   
                        	?>
                            <tr></tr>
                          <tr>
                            <td style="white-space: nowrap;"><?php echo $start_date; ?></td>
                            <td><?php echo $event_id; ?></td>
                            <td>
                            <a href="#TB_inline?height=600&width=400&inlineId=popup<?php echo $event_id;?>" class="thickbox"><?php echo evr_truncateWords($event_name, 8, "..."); ?></a>
                            <br />
                            <?php echo $event_location; ?><?php echo ", ".$event_city; ?>
                            </td>
                            <td style="white-space: nowrap;">[EVR_SINGLE event_id="<?php echo $event_id;?>"] </td>
                            <td><?php echo $active_event ; ?></td>
                            <td><?php echo $number_attendees;?> / <?php echo $reg_limit; ?></td>
                            <td>
                            <div style="float:left; margin-right:10px;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="add_item"/>
                                <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                <span title="FEES"><button type="submit" name="items" value="<?php _e('Fees/Items','evr_language');?>" /><i class="fa fa-bank"></i>
                                </button></span>
                              </form>
                            </div>
                            <div style="float:left; margin-right:10px;">
                                        <form name="form" method="post" action="admin.php?page=questions">
                                          <input type="hidden" name="action" value="new"/>
                                          <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
                                          <input type="hidden" name="event_name" value="<?php echo $event_name;?>">
                                          <span title="CUSTOM QUESTIONS"> <button  type="submit" name="questions" value="<?php _e('Questions','evr_language');?>" ><i class="fa fa-question"></i><i class="fa fa-question"></i> </button></span>
                                        </form>
                                    </div>
                                    <div style="float:left;">
                                        <form name="form" method="post" action="admin.php?page=attendee">
                                          <input type="hidden" name="action" value="view_attendee">
                                          <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                          <span title="ATTENDEES"><button type="submit" name="Attendees" value="<?php _e('Attendees','evr_language');?>" ><i class="fa fa-male"></i><i class="fa fa-female"></i></button></span>
                                        </form>
                                    </div>
                            </td><td>
                            <div style="float:left; margin-right:10px;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?php echo $event_id?>">
                                <!--<i class="fa fa-pencil"></i><input class="edit_btn" type="submit" name="edit" value="<?php _e('Edit','evr_language');?>" id="edit_event_setting-<?php echo $event_id?>" />-->
                                <span title="EDIT"><button type="submit" value="<?php _e('Edit','evr_language');?>" id="edit_event_setting-<?php echo $event_id?>"><i class="fa fa-pencil"></i></button></span>
                              </form>
                            </div>
                            <div style="float:left; margin-right:10px;">
                                        <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                          <input type="hidden" name="action" value="copy_event">
                                          <input type="hidden" name="id" value="<?php echo $event_id?>">
                                          <!-- <input class="copy_btn" type="submit" name="copy" value="<?php _e('Copy','evr_language');?>" id="copy_event_setting-<?php echo $event_id?>"  onclick="return confirm('<?php _e('Are you sure you want to copy','evr_language');?> <?php echo $event_name?>?')"/>
                                        --> <span title="COPY"><button type="submit" name="copy" value="<?php _e('Copy','evr_language');?>" id="copy_event_setting-<?php echo $event_id?>"  onclick="return confirm('<?php _e('Are you sure you want to copy','evr_language');?> <?php echo $event_name?>?')"><i class="fa fa-files-o"></i></button></span>
                                        </form>
                                    </div>
                            <div style="float:left;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $event_id?>">
                                 <span title="DELETE"><button type="submit" name="delete" value="<?php _e('Delete','evr_language');?>" id="delete_event-<?php echo $event_id?>" onclick="return confirm('<?php _e('Are you sure you want to delete','evr_language');?> <?php echo $event_name?>?')"/><i class="fa fa-trash-o"></i></button>
                              </span>
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

$rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
if ($rows){
                            foreach ($rows as $event){
                                        $event_id       = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                                        if((get_option('evr_location_active')=="Y") && ( $event->location_list >= '1')){
                                            $sql = "SELECT * FROM " . get_option('evr_location')." WHERE id =".$event->location_list;
                                            $location = $wpdb->get_row( $sql, OBJECT );//default object
                                            //$object->field;
                                            if( !empty( $location ) ) {
                                            $event_location = stripslashes($location->location_name);
                                            $event_address  = $location->street;
                                            $event_city     = $location->city;
                                            $event_state    = $location->state;
                                            $event_postal   = $location->postal;
                                            $event_phone    = $location->phone;
                                            }
                                        } else {
                                        $event_location = stripslashes($event->event_location);
                                        $event_address  = $event->event_address;
                                        $event_city     = $event->event_city;
                                        $event_postal   = $event->event_postal;
                                        }
                                        $reg_limit      = $event->reg_limit;
                                		$start_time     = $event->start_time;
                                		$end_time       = $event->end_time;
                                		$conf_mail      = $event->conf_mail;
                                        //$custom_mail    = $event->custom_mail;
                                		$start_date     = $event->start_date;
                                		$end_date       = $event->end_date;
                                        $event_close    = $event->close;
                            $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id= %d",$event_id));
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
                               //$current_dt= date('Y-m-d H:i a',current_time('timestamp',0));
$current_dt= date('Y-m-d H:i',current_time('timestamp',0));
if ($event_close == "start"){$close_dt = $start_date." ".$start_time;}
else if ($event_close == "end"){$close_dt = $end_date." ".$end_time;}
else if ($event_close == ""){$close_dt = $start_date." ".$start_time;}
$stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
$expiration_date = strtotime($stp);
$today = strtotime($current_dt);
if ($expiration_date <= $today){
           					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE','evr_language').'</span>';
            				}   
  
                            //div for popup goes here.
                            include "evr_event_popup_pop.php";         
                            }}
?>
</div>
<?php
}
?>