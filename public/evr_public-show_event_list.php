<?php
/**
* Function evr_show_event_list($public_list_template){}
* Retrieves list of events from database and returns them to the screen in the list option selected in the company settings
* If no option was defined the default is a table format with Event Name hyperlink to a popup window with event details
* The string $public_list_template can contain a user defined table that changes what data is displayed on the list
*
*/
function evr_show_event_list($public_list_template){
    global $wpdb,$evr_date_format, $company_options;
    #retrieve company and configuration settings
    //$company_options = get_option('evr_company_settings');
    $curdate = date ( "Y-m-j" );
    # Get events that end date is later than today and order by start date
    $sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
    $rows = $wpdb->get_results( $sql );
#Begin html table layout
echo '<div class="evr_event_list">';
echo '<table class="evr_events" cellspacing="0" summary="'.__('The list of upcoming events.','evr_language').'">';
echo '<caption>'.__('Click on Event Name for description/registration','evr_language').'</caption>';
echo '<thead><tr><th width="40%">'.__('EVENT','evr_language').'</th><th></th><th></th></th><th>'.__('START','evr_language').'</th><th>-</th><th>'.__('END','evr_language').'</th></tr></thead>';
echo '<tbody>';
#Set the count for the alternating color rows of the table
    $color_row= "1";
#Set the the default month end number for events in case none is defined
    $month_no = $end_month_no = '01'; 
#Clear start date and end date fields to ensure no carry over data 
    $start_date = $end_date = '';
#Check and see if the sql querry returned rows, if they did then begin to return each row
    if ($rows){
        $codeToReturn = '';
        foreach ($rows as $event){
                #Determine when the event ends and compare that date and time to today's date and time
                $current_dt= date('Y-m-d H:i',current_time('timestamp',0));
                $close_dt = $event->end_date." ".$event->end_time;
                $today = strtotime($current_dt);
                $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
                $expiration_date = strtotime($stp);
                #check to see if there is a custom template for the table if not, use the default.
                #GT Populate variable
                $outside_reg = $event->outside_reg;
                ?>
<script>
		jQuery(document).ready(function($){
	 					   		   
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>
<p><a class='inline' href="#inline_content">Inline HTML</a></p>

		<div style='display:none'>
			<div id='inline_content' style='padding:10px; background:#fff;'>
			<p><strong>This content comes from a hidden element on this page.</strong></p>
			<p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
			<p><a id="click" href="#" style='padding:5px; background:#ccc;'>Click me, it will be preserved!</a></p>
			
			<p><strong>If you try to open a new Colorbox while it is already open, it will update itself with the new content.</strong></p>
			<p>Updating Content Example:<br />
			<a class="ajax" href="../content/ajax.html">Click here to load new content</a></p>
			</div>
		</div>
                <?php
                if ($public_list_template == ''){
                    #Check to see if the end time of this event is later than now, if so then display then send the event details to a string
            		if ($stp >= $current_dt){
                        #Set the row color for this row
                        if($color_row==1){ $td_class = "odd"; } else if($color_row==2){ $td_class = "even"; } 
                        #Begin creation of string that will return event data in html format for table                 
                        $codeToReturn .= '<tr><td class="er_title er_ticket_info '.$td_class.'" colspan="3" ><b>';
                        #Check to see if link only in company settings
                        if ($company_options['evr_list_format']=="link"){
                            if ($outside_reg == "Y"){  
                                $codeToReturn .=  '<a href="'.$event->external_site.'">{EVENT_NAME}</a>' ;
                                }  
                            else {
                                $codeToReturn .= '<a href="{EVENT_URL}">{EVENT_NAME}</a>';
                                }
                            }
                        else { 
                            if ($outside_reg == "Y"){  
                                $codeToReturn .=  '<a href="'.$event->external_site.'">{EVENT_NAME}</a>' ;
                                }  
                            else {
                           // $codeToReturn .= '<a class="thickbox" href="#TB_inline?width=640&height=1005&inlineId=popup{EVENT_ID}&modal=false title='.stripslashes($event->event_name).'">{EVENT_NAME}</a>';
                            #changed to use colorbox popup
                            $codeToReturn .= '<a class="inline" href="#event_content_{EVENT_ID}">{EVENT_NAME}</a>';
                            }
                        }
                        $codeToReturn .= '</b></br>Open Seats {EVENT_AVAIL_SPOTS}</td>
                            <td class="er_date '.$td_class.'">{EVENT_DATE_START}</br> {EVENT_TIME_START}</td>
                            <td class="'.$td_class.'">-</td><td class="er_date '.$td_class.'">';
                        #Check to see if the start date and end date are the same, if they are don''t display end date, only time
                        if ($event->end_date != $event->start_date) {
                                         $codeToReturn .='{EVENT_DATE_END}</br>';} 
                        $codeToReturn .='{EVENT_TIME_END}</td></tr>';
                        }
                }
                #If a custom table template was defined use it instead of the default.
                else {
				    $codeToReturn .= $public_list_template;
				    }
                #Now that we have created the row, change color for next row
                if ($color_row ==1){$color_row = "2";} else if ($color_row ==2){$color_row = "1";}
                #Now that we have created the string for this row, lets replace the tags with the real event data     
                $event_name = stripslashes($event->event_name);
    			$event_desc = stripslashes($event->event_desc);
    			$codeToReturn = str_replace("\r\n", '', $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_URL}", evr_permalink($company_options['evr_page_id']).'action=evregister&event_id='.$event->id, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_ID}", $event->id, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_NAME}", stripslashes($event->event_name), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_SHORTNAME}", evr_truncateWords(stripslashes($event->event_name), 8, "..."), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DESC}", stripslashes($event->event_desc), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_LOC}", stripslashes($event->event_location), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_ADDRESS}", stripslashes($event->event_address), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_CITY}", stripslashes($event->event_city), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_STATE}", stripslashes($event->event_state), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_POSTAL}", stripslashes($event->event_postal), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_START_NUMBER}", $event->start_month, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", date("F",strtotime($event->start_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_START_NAME_3}", date("M",strtotime($event->start_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_START_NUMBER}", $event->start_day, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_START_NAME}",date("l",strtotime($event->start_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_START_NAME_3}",date("D",strtotime($event->start_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_YEAR_START}", $event->start_year, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_TIME_START}", $event->start_time, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DATE_START}", $event->start_date, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_END_NUMBER}", $event->end_month, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_END_NAME}", date("F",strtotime($event->end_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_MONTH_END_NAME_3}", date("M",strtotime($event->end_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_END_NUMBER}", $event->end_day, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_END_NAME}",date("l",strtotime($event->end_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DAY_END_NAME_3}",date("D",strtotime($event->end_date)), $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_YEAR_END}", $event->end_year, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_DATE_END}", $event->end_date, $codeToReturn);
    			$codeToReturn = str_replace("{EVENT_TIME_END}", $event->end_time, $codeToReturn);
                #In order to get the number of seats we need to count all attendees for this event
                #Retrieve the number of registered attendees for this event from attendee db
                    $qty_count = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id= %d", $event->id));
                    $available_spaces = 0; 
            		if ($event->reg_limit != ""){$available_spaces = $event->reg_limit - $qty_count;}
            	    if ($event->reg_limit == "" || $event->reg_limit == " " || $event->reg_limit == "999"){$available_spaces = "UNLIMITED";}
                $codeToReturn = str_replace("{EVENT_AVAIL_SPOTS}", $available_spaces, $codeToReturn);
                #We have now finished this row, repeat the process for the remaining row(s)
                }
            #All rows should have been returned and put into string
            #Output html string to screen              
            echo $codeToReturn;     
        }
echo '</tbody></table></div>';
#Now that we have returned the table, we need to return the hidden html that provides the popups.
#Once again we will go through the returned event data to generate the popup html
    if ($rows){
        foreach ($rows as $event){                             
            #use the included file to put all the event data for this event into strings
            include "evr_event_array2string.php";   
            #Generate the html popup code for this event
            include "evr_event_colorbox_pop.php";
            }         
        }             
}
# End of Event Display List Function
/**
* Function evr_show_event_accordian(){}
* Retrieves list of events from database and returns them to the screen in the accordian option selected in the company settings
* If no option was defined the default is the list format with Event Name hyperlink to a popup window with event details
* This function uses accordian to show/hide details on equipment list
* 
*/
function evr_show_event_accordian(){
    global $wpdb,$evr_date_format, $company_options;
    $curdate = date ( "Y-m-j" );
    # Get events that end date is later than today and order by start date
    $sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
    $rows = $wpdb->get_results( $sql );
    #Set the the default month end number for events in case none is defined
    $month_no = $end_month_no = '01'; 
    #Clear start date and end date fields to ensure no carry over data 
    $start_date = $end_date = '';
    #retrieve company and configuration settings
    
    #include style sheet for accordian here to esnure style was not overwritten by other style elsewhere!
    include "evr_public_accordian_style.php";
    #start accordian html outpupt
echo '<div class="evr_accordion">';
echo '<section id="close"><h2><a href="#Close">Click on Event for Details - Click Here to Collapse All</a></h2><div></div></section>';
    #Check and see if the sql querry returned rows, if they did then begin to return each row   
    if ($rows){
            foreach ($rows as $event){                             
                #use the included file to put all the event data for this event into strings
                include "evr_event_array2string.php";   
                #Generate the html accordian code for this event
                //include "evr_public_event_accordian.php";
                $codeToReturn .='<section id="'.$event_id.'"><h2><a href="#'.$event_id.'">'
                                .strtoupper($event_name).'<br/><br/>'.date($evr_date_format,strtotime($start_date)).'  -  ';           
                if ($end_date != $start_date) {
                    $codeToReturn .= date($evr_date_format,strtotime($end_date));
                    }
                $codeToReturn .= __('&nbsp;&nbsp;&nbsp;&nbsp;Time: ','evr_language').' '.$start_time.' - '.$end_time.'</a></h2><div>';
                $codeToReturn .='<div class="evr_spacer"></div><div style="text-align: justify;white-space:pre-wrap;"><p>'
                                .html_entity_decode($event_desc).'</p></div><span style="float:right;">';			
                $codeToReturn .='<a href="'.EVR_PLUGINFULLURL.'evr_ics.php?event_id='.$event_id.'">
                                <img src="'.EVR_PLUGINFULLURL.'images/ical-logo.jpg" /></a></span>';
                $codeToReturn .='<div class="evr_spacer"><hr /></div><div style="float: left;width: 310px;">
                                <p><b><u>'.__('Location','evr_language').'</u></b><br/><br/>'.stripslashes($event_location);
                $codeToReturn .='<br />'.$event_address.'<br />'.$event_city.', '.$event_state.' '.$event_postal.'<br /></p></div>';
                $codeToReturn .='<div style="float: right;width: 280px;"> <div id="evr_pop_map">';
                if ($google_map == "Y"){
                    $codeToReturn .='<img border="0" src="http://maps.google.com/maps/api/staticmap?center='.
                                    $event_address.','.$event_city.','.$event_state.
                                    '&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|'.
                                    $event_address.','.$event_city.'&sensor=false" />';
                    }
                $codeToReturn .='</div></div><div id="evr_pop_price"><p><b><u>'.__('Event Fees','evr_language').':</u></b><br /><br />';    
                #Get event fees from the cost database for this event
                    $curdate = date("Y-m-d");
                    $sql_fees = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC";
                    $fee_rows = $wpdb->get_results( $sql_fees );
                    if ($fee_rows){
                            foreach ($fee_rows as $fee){ 
                                $item_custom_cur  = $fee->item_custom_cur;
                                if ($item_custom_cur == "GBP"){$item_custom_cur = "&pound; ";}
                                if ($item_custom_cur == "USD"){$item_custom_cur = "$ ";}
                                $codeToReturn .=$fee->item_title.'   '.$item_custom_cur.' '.$fee->item_price.'<br />';
                                }
                            } 
                $codeToReturn .='</p></div><div class="evr_spacer"></div><div id="evr_pop_foot"><p align="center">';            
                if ($more_info !=""){
                    $codeToReturn .='<input type="button" onClick="window.open(\''.$more_info.'\');" value=\'MORE INFO\'/>';
                    }
                if ($outside_reg == "Y"){            
                    $codeToReturn .='<input type="button" onClick="window.open(\''.$external_site.'\');" value=\'External Registration\'/>'; 
                    }  
                else {        
                    $codeToReturn .='<input type="button" onClick="location.href=\''.evr_permalink($company_options['evr_page_id']).    
                                    'action=evregister&event_id='.$event_id.'\'" value=\'REGISTER\'/>';
                    }
                $codeToReturn .='</p></div></div></section>';
                #end of event
                } 
        echo $codeToReturn;        
    }
echo '</div>';
}
?>