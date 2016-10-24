<?php
//function to create a new event
function evr_new_event(){
    global $wpdb, $wp_version,$company_options;
   
    $editor_settings= array('wpautop'=>false,'media_buttons' => false,'textarea_rows' => '4', 'tinymce'=>false); 
    $body =   "***This is an automated response - Do Not Reply***<br />";
    $body .= "Thank you [fname] [lname] for registering for [event].<br />";
    $body .= "We hope that you will find this event both informative and enjoyable.";
    $body .= "Should have any questions, please contact [contact].";
    $body .= "If you have not done so already, please submit your payment in the amount of [cost].";
    $body .= "Click here to review your payment information [payment_url].<br />";
    $body .= "Thank You.";                
    
?>
<script>
 var tinymceConfigs = [ {
                        theme : "advanced",        
                        mode : "none",        
                        language : "en",        
                        height:"200",        
                        width:"100%",        
                        theme_advanced_layout_manager : "SimpleLayout",        
                        theme_advanced_toolbar_location : "top",        
                        theme_advanced_toolbar_align : "left",        
                        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",        
                        theme_advanced_buttons2 : "",        
                        theme_advanced_buttons3 : "" },
                            { 
                                theme : "advanced",        
                                mode : "none",        
                                language : "en",        
                                height:"200",        
                                width:"100%",        
                                theme_advanced_layout_manager : "SimpleLayout",        
                                theme_advanced_toolbar_location : "top",        
                                theme_advanced_toolbar_align : "left"
                                }];
 function tinyfy(settingid,el_id) {    
                        tinyMCE.settings = tinymceConfigs[settingid];    
                        tinyMCE.execCommand('mceAddControl', true, el_id);}
</script>


<?php //evr_check_form_submission();?>
<div class="wrap">
<h2 style="font-family: segoe;"><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL; ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
<form id="er_popup_Form" method="post" action="admin.php?page=events">                        
<div class="evr_container">
	<h1><?php _e('ADD NEW EVENT','evr_language');?></h1>
    <ul class="tabs">
        <li><a href="#tab1"><?php _e('Event Description','evr_language');?></a></li>
        <li><a href="#tab2"><?php _e('Event Venue','evr_language');?></a></li>
        <li><a href="#tab3"><?php _e('Event Date/Time','evr_language');?></a></li>
        <li><a href="#tab4"><?php _e('Options','evr_language');?></a></li>
        <li><a href="#tab5"><?php _e('Coordinator','evr_language');?></a></li>
        <li><a href="#tab6"><?php _e('Confirmation Mail','evr_language');?></a></li>
    </ul>
    <div class="evr_tab_container">
        <div id="tab1" class="tab_content">
            
            <input type="hidden" name="action" value="post"/>
            <table>
                <tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Use a concise but descriptive name.','evr_language');?>">
                    <?php _e('Event Name/Title ','evr_language');?><a><span>?</span></a></label>
                    </td>
                    <td>
                    <input class="title" name="event_name" size="50"/>
                    </td>
                <tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Provide a short Unique ID for this event. i.e. BOB001','evr_language');?>">
                    <?php _e('Unique Event Identifier','evr_language');?>  <a><span>?</span></a></label> 
                    </td>
                    <td>
                    <input name="event_identifier" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <label class="tooltip" title="<?php _e('If you want the description to display under the event title on the registration form, select yes.','evr_language');?>">
                    <?php _e('Display description on registration form page? ','evr_language');?><a><span>?</span></a></label>
                    <label for="display_desc"><input type="radio" class="radio" name="display_desc" value="Y"><?php _e('Yes','evr_language');?></label>
                    <label for="display_desc"><input type="radio" class="radio" name="display_desc" value="N"><?php _e('No','evr_language');?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <label for="event_desc" class="tooltip" title="<?php _e('Provide a detailed description of the event, 
                    include key details other than when and where. Do not use any html code. This is a text only display.
                    To create new display lines just press Enter.','evr_language');?>">
                    <?php _e('Detailed Event Description','evr_language');?> <a><span>?</span></a>
                    
                    <?php
                    if(function_exists('the_editor')){
                    echo "</td></tr></table>";
                    the_editor('', "event_desc", '', false);
           
				    }else{ ?>
					
                    <a href="javascript:void(0)" onclick="tinyfy(1,'event_desc')"><input type="button" value="WYSIWG"/></a>
                    </td></tr></table>
                    <textarea name="event_desc" id="event_desc" style="width: 100%; height: 200px;"></textarea>
                    <?php }  ?>
                    
                                    
                    
              <br/>      
            
              <table><tr></tr>
                
                <tr>
                    <td colspan="2">
                    <label class="tooltip" title="<?php _e('Select one or many categories for an event','evr_language');?>">
                    <strong><?php _e('Event Categories','evr_language');?> </strong> <a><span> ?</span></a></label>
                    </td>
                </tr>
               <tr><td colspan="2">
                    <?php 
                    $sql = "SELECT * FROM ". get_option('evr_category');
                    $myrows = $wpdb->get_results($sql);
                    foreach ($myrows as $row){
                        echo '<input class="checkbox" value="'.$row->id.'" type="checkbox" name="event_category[]" id="in-event-category-'.$row->id.'"'. ($checked ? ' checked="checked"' : "" ). '/>  '."&nbsp;". $row->category_name. "&nbsp;&nbsp;&nbsp; ";
                    }
                    ?>
                    </td>
               </tr></table> 
            
            
    </div>
    <div id="tab2" class="tab_content">
            <h2><?php _e('EVENT VENUE','evr_language');?></h2>
            <table>
                   <tr>
                    <td>
                    <label  class="tooltip" title="<?php _e('Enter the number of available seats at your event venue. Leave blank if their is no limit on registrations.','evr_language');?>" for="reg_limit">
                    <?php _e('Event Seating Capacity','evr_language');?> <a><span>?</span></a>
                    </td>
                    <td>
                    <input  class="count" name="reg_limit">
                    </td>
                </tr>
                
            </table>
<?php    
    global $wpdb;
    $locations_array = array();
    $location_status 	= get_option( 'evr_location_license_status' );
    if ($location_status !== false && $location_status == 'valid' ) {
    $sql = "SELECT * FROM " . get_option('evr_location')." ORDER BY location_name";
    $locations_array = $wpdb->get_results( $sql );
    }

    if( (!empty( $locations_array )) && ($location_status=="valid")) {
    ?><script type="text/javascript">

	/* <![CDATA[ */
$j = jQuery.noConflict();
jQuery(document).ready(function($j){
		$j("#location_list").change(function(){

			if ($j(this).val() == "0" ) {
               	$j("#hide1").slideDown("fast"); 
                 $j('#hide1 :input').attr('disabled', false);
                 


			} else {

				$j("#hide1").slideUp("fast");	
                $j('#hide1 :input').attr('disabled', true);
			}
		});

	});


   
    /* ]]> */

</script>


<style type="text/css">
 .custom_addrs
		{
			display:none;
		}
	
</style> 
    <div class="input select">
	<table>	<tr><td><label for="select_location">Event Location: </label></td><td>
			<select name="location_list" id="location_list" onchange="showUser(this.value)">
				<option value="">(choose one)</option>
				<option value="0">Custom</option>
    <?php
		foreach( $locations_array as $location ) { 
        ?>
			<option value="<?php echo $location->id; ?>"><?php echo stripslashes($location->location_name); ?></option>
		<?php
		}
        ?>
        </select>
            </td></tr></table>
		</div>
        
		<div class="custom_addrs" id="hide1"><!-- this select box will be hidden at first -->
			<table><tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Enter the name of the business or facility where the event is being held','evr_language');?>" for="event_location">
                    <?php _e('Event Location/Venue','evr_language');?><a><span> ?</span></a></label>
                    </td>
                    <td>
                    <input class= "title" id="event_location" name="event_location" type="text" size="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="first" for="event_street"><?php _e('Street','evr_language');?></label>
                    </td>
                    <td>
                    <input  class= "title" id="event_street" name="event_street" type="text" value="Street" />
                    </td>
                </tr>		
				<tr>
                    <td><label for="event_city">
					<?php _e('City','evr_language');?></label></td><td><input id="event_city" name="event_city" type="text" value="City" /></td></tr>
                <tr>
                    <td><label for="event_state">
					<?php _e('State','evr_language');?></label></td><td><input id="event_state" name="event_state" type="text" value="State" /></td></tr>
                <tr>
                    <td>
                    <label for="event_postcode">
					<?php _e('Postcode','evr_language');?></label>
                    </td>
                    <td>
                    <input id="event_postcode" name="event_postcode" type="text" value="Postcode" />
                    </td>
                </tr>
                </table>
		</div>
        <table>
        <?php
	} else { ?>
		<table><tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Enter the name of the business or facility where the event is being held','evr_language');?>" for="event_location">
                    <?php _e('Event Location/Venue','evr_language');?><a><span> ?</span></a></label>
                    </td>
                    <td>
                    <input class= "title" id="event_location" name="event_location" type="text" size="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="first" for="event_street"><?php _e('Street','evr_language');?></label>
                    </td>
                    <td>
                    <input  class= "title" id="event_street" name="event_street" type="text" value="Street" />
                    </td>
                </tr>		
				<tr>
                    <td><label for="event_city">
					<?php _e('City','evr_language');?></label></td><td><input id="event_city" name="event_city" type="text" value="City" /></td></tr>
                <tr>
                    <td><label for="event_state">
					<?php _e('State','evr_language');?></label></td><td><input id="event_state" name="event_state" type="text" value="State" /></td></tr>
                <tr>
                    <td>
                    <label for="event_postcode">
					<?php _e('Postcode','evr_language');?></label>
                    </td>
                    <td>
                    <input id="event_postcode" name="event_postcode" type="text" value="Postcode" />
                    </td>
                </tr></table>
		<?php 
	} 
?>  
                
                <tr>
                    <td>
                    <legend class="tooltip" title="<?php _e('All location information must be complete for Google Map feature to work.','evr_language');?>">
					<?php _e('Use Google Maps On Registration Page','evr_language');?> <a><span>?</span></a></legend>
                    </td>
                    <td>
                    <label for="google_map_yes"><input type="radio" class="radio" name="google_map" value="Y" checked><?php _e('Yes','evr_language');?></label>
                    <label for="google_map_no"><input type="radio" class="radio" name="google_map" value="N"><?php _e('No','evr_language');?>
                    </label>
                    </td>
                </tr> <tr></tr></table><br/>
                <?php
                if ($location_status!=="valid"){
                    echo "<hr><br/><br/><p align='center'>Stop typing your locations over and over<br/>";
                    echo "Get the Location Add-On for Event Registration Today<br/>";
                    echo "Have all your locations accessible in an easy dropdown list<br/>";
                    echo '<a href="http://wpeventregister.com/a-new-add-on-for-event-registration/">Location Module</a><br/></p>';
                    
                }
                ?>
        </div>
        <div id="tab3" class="tab_content">
            <h2><?php _e('EVENT TIMES','evr_language');?></h2>
          	              <table><tr>
                        <td><b><?php _e('Start Date','evr_language');?></b></td>
                        <?php 
                        $start = strtotime('6:00am');
                        $end = strtotime('11:45pm');
                        ?>
                        <td><label  for="start_date"><?php evr_DateSelector( "\"start"); ?></label></td>
                        <td><b><?php _e('Start Time','evr_language');?></b></td><td><label for="start_time"><?php 
                            echo '<select name="start_time">';
                            for ($i = $start; $i <= $end; $i += 900)
                            {echo '<option>' . date('g:i a', $i);}
                            echo '</select>';
                            ?></label></td>
                        </tr>
                        <tr><td><b><?php _e('End Date','evr_language');?></b></td><td><label for="end_date"><?php evr_DateSelector( "\"end"); ?></label></td>
                        <td><b><?php _e('End Time','evr_language');?></b></td><td><label for="end_time"><?php
                            echo '<select name="end_time">';
                            for ($i = $start; $i <= $end; $i += 900)
                            { echo '<option>' . date('g:i a', $i); }
                            echo '</select>';?></label></td>
                        </tr>
                        <tr></tr>
                        <tr><td>Close Registration on </td><td><select name="close"><option value="start">Start of Event</option><option value="end">Close of Event</option></select></td></tr>
                    </table>
        </div>

        <div id="tab4" class="tab_content">
        <h2><?php _e('EVENT OPTIONS','evr_language');?></h2>
            <table>
                <tr>
                    <td colspan="2">
                    <br />
                    <label class="tooltip" title="<?php _e('If you will accept checks or cash, usually when accepting payment at event/on-site.','evr_language');?>">
   					<?php _e('Will you accept checks/cash for this event? ','evr_language');?><a><span>?</span></a></label>
                    <label for="accept_checks"><input type="radio" name="allow_checks" class="radio" id="accept_checks_yes" value="Y" /><?php _e('Yes','evr_language');?></label>
                    <label for="accept_chacks"><input type="radio" name="allow_checks" class="radio" id="accept_checks_no" value="N" checked /><?php _e('No ','evr_language');?></label>
                    </td>
                </tr>
            
            
                <tr>
                    <td colspan="2">
                    <br />
                    <label class="tooltip" title="<?php _e('You can point your register now button to an external registration site/page by selecting yes and entering the url!','evr_language');?>">
                    <?php _e('Are you using an external registration?','evr_language');?> <a><span>?</span></a></label>
                    <label><input type="radio" name="outside_reg" class="radio" id="outside_reg_yes" value="Y" /><?php _e('Yes','evr_language');?> 
                    </label><label><input type="radio" name="outside_reg" class="radio" id="outside_reg_no" value="N" checked /><?php _e('No','evr_language');?> 
                    </label></td>
                </tr>
                <tr>
                    <td  colspan="2">
                    <label class="tooltip" title="<?php _e('Enter the url hyperlink to another webpage or website external registration','evr_language');?>">
                    <?php _e('External registration URL','evr_language');?> <a><span> ?</span></a><input class= "title" id="external_site" name="external_site" type="text" /></label> 
                    </td>
                </tr>
                <tr></tr>
                 <tr></tr>
                  <tr></tr>
                <tr>
                    <td colspan="2">
                    <legend class="tooltip" title="<?php _e('Select the default fields for the registration form.  Note that name and email or not optional','evr_language');?>" >
                    <?php _e('Default Registration Information (Name and Email Required)','evr_language');?><a><span> ?</span></a></legend>
                    </td>
                </tr>
                <tr>
                   <td colspan="2">
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Address" checked /><?php _e('Street Address','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="City" checked /><?php _e('City','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="State" checked /><?php _e('State or Province','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Zip" checked /><?php _e('Zip or Postal Code','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Phone" checked /><?php _e('Phone Number','evr_language');?></label>
                 </tr>
                 <tr>
                   <td colspan="2">
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Company" /><?php _e('Company','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoAddress"  /><?php _e('Co. Addr','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoCity"  /><?php _e('Co. City','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoState"  /><?php _e('Co. State/Prov','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoPostal"  /><?php _e('Co. Postal','evr_language');?></label>
                   </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
                
                
        </table>
        <hr />
                <h4><?php _e('Event Waiver Options','evr_language');?>  <font color="red"><?php _e('Optional','evr_language');?></font></h4>
        <table>
            <tr><td>
            <label  class="tooltip" title="<?php _e('Will require the person signing up to accept or decline an event waiver','evr_language');?>">
            <?php _e('Use event waiver?','evr_language');?> <a><span>?</span></a></label></td>
            <td><label>
            <input type="radio" name="waiver" class="radio" value="Y" />&nbsp;<?php _e('Yes','evr_language');?>
            </label></td>
            <td>
            <label>
            <input type="radio" name="waiver" class="radio" value="N" />&nbsp;<?php _e('No','evr_language');?> 
            </label></td></tr>
            <tr><td colspan="3">          
            <label  class="tooltip" title="<?php _e('Enter the content for the event waiver','evr_language');?>" >
            <?php _e('Waiver Content','evr_language');?> <a><span>?</span></a></label>
              <?php
             
             
              if (function_exists('wp_editor')){
               echo "</td></tr></table>";
              wp_editor( '', 'waiver_content', $editor_settings ); 
                    } else {  ?>
               <a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
               </td></tr></table>
               <textarea name="waiver_content" id="waiver_content" style="width: 100%; height: 200px;"></textarea>
                    <?php } ?>
             
                    
        <hr />
        <br />
        <h3><?php _e('Event Listing Options','evr_language');?>  <font color="red"><?php _e('Optional','evr_language');?></font></h3>
        <table>
                 <tr>
                <td>
                <label class="tooltip" title="<?php _e('Enter the url hyperlink to another webpage or website with more event information','evr_language');?>">
                <?php _e('More Info URL','evr_language');?> <a><span> ?</span></a>
                </td>
                <td>
                <input class= "title" id="event_location" name="more_info" type="text" /></label>
                </td>
            </tr>
            <tr>
                <td><label class="tooltip" title="<?php _e('Enter the url to an image you would like displayed next to the event in the event listings. Size should be 150 x112','evr_language');?>">
                <?php _e('Thumbnail Image URL','evr_language');?> <a><span> ?</span></a></td><td><input class= "title" id="event_location" name="image_link" type="text" />
				    </label>
                </td>
            </tr>
            <tr>
                <td><label class="tooltip" title="<?php _e('Enter the url of an image you wish displayed above the registration form.  The image should be no wider than 450.','evr_language');?>">
                <?php _e('Header Image URL','evr_language');?> <a><span> ?</span></a></td><td><input class= "title" id="event_location" name="header_image" type="text" /></label>
                </td>
            </tr>
        </table>
    </div>
    
    <?php 
        $coordinator_status 	= get_option( 'evr_coordinator_license_status' );
        if( ($coordinator_status !== false && $coordinator_status == 'valid' )|| (get_option('evr_coordinator_active')=="Y")) { ?>
        <div id="tab5" class="tab_content">
            <h2><?php _e('Coordinator Options','evr_language');?></h2>
            <label  class="tooltip" title="<?php _e('If you want to send alerts to a unique event coordinator','evr_language');?>">
            <?php _e('Do you want to send alerts to a coordinator for this event?','evr_language');?> <a><span>?</span></a></label>
            <label><input type="radio" name="send_coord" class="radio" id="send_coord_yes" value="Y" /><?php _e('Yes','evr_language');?>
            </label><label><input type="radio" name="send_coord" class="radio" id="send_coord_no" value="N" checked /><?php _e('No','evr_language');?> 
            </label><br />
            <br /> 
            <table>
            <tr>
            <td><label for="contact"><?php _e('Coordinator email:','evr_language');?></label></td>
            <td><input name="coord_email" type="text" size="65" value="<?php echo $company_options['company_email'];?>" class="regular-text" /></td>
        </tr></table>
            <br />
            <br />
            <label  class="tooltip" title="<?php _e('Enter the text for the registration alert email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Coordinator Registration Alert Email','evr_language');?> <a><span>?</span></a></label>
            <?php
                $body = "***This is an automated response - Do Not Reply***<br />
                        [fname] [lname] has registered for [event].<br />
                        Registration is for [num_people] person(s) for a total of [cost].";     
                
            if (function_exists('the_editor')){
					//wp_editor( $body, 'coord_msg', $editor_settings );
                    the_editor($body, "coord_msg", '', false);
                                     
				    }else{ ?>
					<a href="javascript:void(0)" onclick="tinyfy(1,'coord_msg')"><input type="button" value="WYSIWG"/></a>
                        <textarea name="coord_msg" id="coord_msg" style="width: 100%; height: 200px;"><?php echo $body;?></textarea>
                    
                    <?php }  ?>   
               
               
                          
            
            <br />
            <br />
            
            <label  class="tooltip" title="<?php _e('Enter the text for the payment alert email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Coordinator Payment Alert Email','evr_language');?> <a><span>?</span></a></label>
            <?php
                $body = "***This is an automated response - Do Not Reply***<br />An instant payment notification was 
                successfully posted from [payer_email] on behalf of [fname] [lname] ([attendee_email])  for event 
                [event_name]([event_id]) on [pay_date] at [pay_time].<br />Details: [details]";    
                
                
                	if (function_exists('the_editor')){
					//wp_editor( $body, 'coord_pay_msg', $editor_settings );
                     the_editor($body, "coord_pay_msg", '', false);                
				    }else{ ?>
					<a href="javascript:void(0)" onclick="tinyfy(1,'coord_pay_msg')"><input type="button" value="WYSIWG"/></a>
                        <textarea name="coord_pay_msg" id="coord_pay_msg" style="width: 100%; height: 200px;"><?php echo $body;?></textarea>
                    
                    <?php }  ?>
                
                
                     
        </div>
        <?php } else { ?>
             <div id="tab5" class="tab_content">
                <h2><?php _e('Coordinator Options','evr_language');?></h2>
                <font color="red">This feature is available in an add on module. </font>
                <ul>
                <li>Option to send unique email to a unique coordinators email address for each event registration.</li>
                <li>WYSIWYG editor for coordinator's email registration alert.</li>
                <li>Option to send unique email to a unique coordinators email address for each event payment recieved via PayPal IPN.</li>
                <li>WYSIWYG editor for coordinator's email payment notification alert.</li>
                </ul>
                <p><a href="http://wpeventregister.com/downloads/event-registration-coordinator-module/">BUY COORDINATOR MODULE</a></p><br />
                <p>Once purchased, you will recieve an email with activation directions.</p>

                
            </div>
      <?php  } ?>
            <div id="tab6" class="tab_content">
            <h2><?php _e('Confirmation eMail','evr_language');?></h2>
            <table>
                <tr>
                    <td>
            <label  class="tooltip" title="<?php _e('If you have send mail option enabled in the company settings, you can override the default mail by creating a custom mail for this event.','evr_language');?>">
            <?php _e('Do you want to use a custom email for this event?','evr_language');?> <a><span>?</span></a></label>
            </td><td><label>
            <input type="radio" name="custom_mail" class="radio" id="accept_checks_yes" value="Y" /><?php _e('Yes','evr_language');?>
            </label>
            </td><td><label>
            <input type="radio" name="custom_mail" class="radio" id="accept_checks_no" value="N" checked /><?php _e('No','evr_language');?> 
            </label>
            </td></tr>
            <tr><td colspan="3">         
            <label  class="tooltip" title="<?php _e('Enter the text for the confirmation email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Custom Confirmation Email','evr_language');?> <a><span>?</span></a></label>
            <?php
               
                
              
           	if (function_exists('wp_editor')){
           	    echo "</td></tr></table>";
					wp_editor($body, 'conf_mail', $editor_settings );
                                     
				    }else{ ?>
					<a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
                        </td></tr></table>
                        <textarea name="conf_mail" id="conf_mail" style="width: 100%; height: 200px;"><?php echo $body;?></textarea>
                    
                    <?php }  ?>
                 
            <br />
            <br />         
            <input  type="submit" name="Submit" value="<?php _e('Submit New Event','evr_language'); ?>" id="add_new_event" />
            </form>
        </div>
    </div>
</div>
<div style="clear: both; display: block; padding: 10px 0; text-align:center;"><font color="blue"><?php _e('Please make sure you complete each section before submitting!','evr_language');?></font></div>

</div>
<?php
}

function evr_prep_content($content='') {
    return wpautop(stripslashes_deep(html_entity_decode($content, ENT_QUOTES, "UTF-8")));
}
?>