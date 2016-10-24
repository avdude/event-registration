<?php
function evr_questions_default(){
    
    global $wpdb,$company_options;
    if ($company_options['eventpaging'] >= "1"){
                   //define # of records to display per page
                    $record_limit = $company_options['eventpaging'];  
                } else
                {
                    //define # of records to display per page
                    $record_limit = 10; 
                }
    ?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Questions','evr_language');?></h2>
<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div class='postbox-container' style='width:auto;'>
        <div id='normal-sortables' class='meta-box-sortables'>
            <div id="dashboard_right_now" class="postbox " >
                <h3 class='hndle'><span><?php _e('Select An Event','evr_language');?></span></h3>
                <?php
                  $items = $wpdb->get_var( 'SELECT COUNT(*) FROM '.get_option('evr_event' ));              
	if($items > 0) {
                		$p = new evr_pagination;
                		$p->items($items);
                		$p->limit($record_limit); // Limit entries per page
                		$p->target("admin.php?page=questions");
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
                	_e('No Record Found','evr_language');
                }//End pagination
                ?>
                <div class="inside">
                    <div class="padding">
                    <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) { echo $p->show(); } // Echo out the list of paging. ?>
                        </div>
                    </div>
                         <table class="widefat">
                         <thead>
                          <tr>
                            <th><?php _e('Start Date','evr_language');?></th>
                            <th><?php _e('Event','evr_language');?></th>
                            <th><?php _e('Description','evr_language');?></th>
                            <th><?php _e('Custom Questions','evr_language');?></th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th><?php _e('Start Date','evr_language');?></th>
                            <th><?php _e('Event','evr_language');?></th>
                            <th><?php _e('Description','evr_language');?></th>
                            <th><?php _e('Custom Questions','evr_language');?></th>
                          </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        $rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                          if ($rows){
                            foreach ($rows as $event){
                                        $id       = $event->id;
                                        $event_id = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                        				$event_location = stripslashes($event->event_location);
                                        $event_address  = $event->event_address;
                                        $event_city     = $event->event_city;
                                        $event_state    = $event->event_state;
                                        $event_postal   = $event->event_postal;
                                        $reg_limit      = $event->reg_limit;
                                		$start_time     = $event->start_time;
                                		$end_time       = $event->end_time;
                                		$conf_mail      = $event->conf_mail;
                                        //$custom_mail    = $event->custom_mail;
                                		$start_date     = $event->start_date;
                                		$end_date       = $event->end_date;
                            ?>
            			    <tr><td><?php echo $start_date;?></td><td><a href="admin.php?page=questions&action=new&event_id=<?php echo $event_id;?>&event_name=<?php echo stripslashes($event_name);?>">
                                <?php echo $event_name;?> (<?php echo $event->event_identifier;?>)</a></td><td><?php echo $event_location.", ".$event_city.", ".$event_state;?></td><td>
                                <?php
                             $questions = $wpdb->get_results ( "SELECT * from ".get_option('evr_question')." where event_id = $id order by sequence" );
            			     if ($questions) {
            			         foreach ( $questions as $question ) { ?>
                                 <font size="1"><?php echo $question->question;?></font><br />
                                 <?php
                            }}
                           else {
                            ?>
                            <font color="red"><?php _e('No Custom Questions','evr_language');?></font>
                            <?php } ?>
                            </td></tr>
                            <?php
                        	}}
                            else {
                            ?>
                            <tr><td colspan="4"><font color="red"><?php _e('Please Create An Event First','evr_language');?></font></td></tr>
                            <?php }  ?>
                         </tbody>
                        </table>
                        <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) { echo $p->show(); } // Echo out the list of paging. ?>
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