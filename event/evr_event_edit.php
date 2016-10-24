<?php
//function to edit an existing event built into event listing page.
function evr_edit_event(){
    global $wpdb, $wp_version;
    $editor_settings= array('wpautop','media_buttons' => false,'textarea_rows' => '4');   
    $event_id = $_REQUEST['id'];
    $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id = $event_id";
    $event = $wpdb->get_row($sql);

	    
?>
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<br />
<form id="er_popup_Form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="evr_container">
	<h2><?php _e('EDIT EVENT: ','evr_language');?> <?php echo " ".stripslashes($event->event_name);?></h2>
    <ul class="tabs">
    <script type="text/javascript">
 /* <![CDATA[ */

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
                                skin : "o2k7",        
                                language : "en",
                                height:"300",        
                                width:"100%",        
                                theme_advanced_layout_manager : "SimpleLayout",        
                                theme_advanced_toolbar_location : "top",        
                                theme_advanced_toolbar_align : "left"
                                }];
                    function tinyfy(settingid,el_id) {    
                        tinyMCE.settings = tinymceConfigs[settingid];    
                        tinyMCE.execCommand('mceAddControl', true, el_id);}

/* ]]> */
</script>	
        <li><a href="#tab1"><?php _e('Event Description','evr_language');?></a></li>
        <li><a href="#tab2"><?php _e('Event Venue','evr_language');?></a></li>
        <li><a href="#tab3"><?php _e('Event Date/Time','evr_language');?></a></li>
        <li><a href="#tab4"><?php _e('Options','evr_language');?></a></li>
        <li><a href="#tab5"><?php _e('Coordinator','evr_language');?></a></li>
        <li><a href="#tab6"><?php _e('Confirmation Mail','evr_language');?></a></li>
    </ul>
    <div class="evr_tab_container">
 <div id="tab1" class="tab_content">
            
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="event_id" value="<?php echo $event->id;?>">
            <table>
                <tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Use a concise but descriptive name.','evr_language');?>">
                    <?php _e('Event Name/Title ','evr_language');?><a><span>?</span></a></label>
                    </td>
                    <td>
                    <input class="title" name="event_name" size="50" value="<?php echo stripslashes($event->event_name);?>"/>
                    </td>
                <tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Provide a short Unique ID for this event. i.e. BOB001','evr_language');?>">
                    <?php _e('Unique Event Identifier','evr_language');?>  <a><span>?</span></a></label> 
                    </td>
                    <td>
                    <input name="event_identifier" value="<?php echo $event->event_identifier;?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <label class="tooltip" title="<?php _e('If you want the description to display under the event title on the registration form, select yes.','evr_language');?>">
                    <?php _e('Display description on registration form page? ','evr_language');?><a><span>?</span></a></label>
                    <label for="display_desc"><input type="radio" class="radio" name="display_desc" value="Y" <?php if ($event->display_desc == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?></label>
                    <label for="display_desc"><input type="radio" class="radio" name="display_desc" value="N" <?php if ($event->display_desc == "N"){echo "checked";}?> /><?php _e('No','evr_language');?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <label for="event_desc" class="tooltip" title="<?php _e('Provide a detailed description of the event, include key details other than when and where. Do not use any html code. This is a text only display. 
                    To create new display lines just press Enter.','evr_language');?>">
                    <?php _e('Detailed Event Description','evr_language');?> <a><span>?</span></a>
                   
                   <?php
                    if (function_exists('the_editor')){
                        echo "</td></tr></table>";
                        the_editor(htmlspecialchars_decode(stripslashes($event->event_desc)), "event_desc", '', false);
                    } else {  ?>
                    <a href="javascript:void(0)" onclick="tinyfy(1,'event_desc')"><input type="button" value="WYSIWG"/></a>
                    </td></tr></table>
                    <textarea name="event_desc" id="event_desc" style="width: 100%; height: 200px;"><?php echo stripslashes($event->event_desc);?></textarea>
                    <?php }  ?>
                   
                    <br />
                    
	            <hr />
              <table><tr></tr>
                
                <tr>
                    <td colspan="2">
                    <label class="tooltip" title="<?php _e('Select one or many categories for an event','evr_language');?>">
                    <strong><?php _e('Event Categories','evr_language');?> </strong> <a><span> ?</span></a></label>
                    </td>
                </tr>
                </table>
                    
                    <?php
                      $event_category = unserialize($event->category_id); 
                      $sql = "SELECT * FROM ". get_option('evr_category') ." ORDER BY id ASC";
                      $rows = $wpdb->get_results( $sql );
                        if ($rows){
                        	foreach ($rows as $category){ 
                        	   if(!empty($event_category)){ 
                        	       $checked = in_array( $category->id, $event_category );
                                }
       					           
                                    
                        echo '<input class="checkbox" value="'.$category->id.'" type="checkbox" name="event_category[]" id="in-event-category-'.$category->id.'"'. ($checked ? ' checked="checked"' : "" ). '/>  '."&nbsp;". $category->category_name. "&nbsp;&nbsp;&nbsp;";
                     }} else{ _e('No Categories Created!','evr_language');}
                        
                        
                    ?>
                    
                   <br />
            <hr />
    </div>
    <div id="tab2"class="tab_content">
    <?php
   $location_status 	= get_option( 'evr_location_license_status' );
   $location_list = $event->location_list;
    echo "List is ".$location_list;
            if(($location_status=="valid") && ( $event->location_list >= '1')){
                
                $sql = "SELECT * FROM " . get_option('evr_location')." WHERE id = $location_list";
                $location = $wpdb->get_row( $sql, OBJECT );//default object
                if( !empty( $location ) ) {
                    $location_tag = stripslashes($location->location_name);
                    $event_location = stripslashes($location->location_name);
                    $event_address  = $location->street;
                    $event_city     = $location->city;
                    $event_state    = $location->state;
                    $event_postal   = $location->postal;
                    $event_phone    = $location->phone;
                } 
                else {
                    $location_list = '0';
                    $location_tag = 'Custom';    
                    $event_location = stripslashes($event->event_location);
                    $event_address = $event->event_address;
                    $event_city = $event->event_city;
                    $event_state =$event->event_state;
                    $event_postal=$event->event_postal;
                }
            }
            elseif (($location_status=="valid") ){
                    $location_list = '0';
                    $location_tag = 'Custom';    
                    $event_location = stripslashes($event->event_location);
                    $event_address = $event->event_address;
                    $event_city = $event->event_city;
                    $event_state =$event->event_state;
                    $event_postal=$event->event_postal;
            }
                
            
            //set reg limit if not set
            /*
            if ($event->reg_limit == ''|| $reg_limit == ' '){$reg_limit = 9999;}
            else {$reg_limit = $event->reg_limit;}
            */
            $reg_limit = $event->reg_limit;
    ?>
            <h2><?php _e('EVENT VENUE','evr_language');?></h2>
            <table>
                   <tr>
                    <td>                    <label  class="tooltip" title="<?php _e('Enter the number of available seats at your event venue. Leave blank if their is no limit on registrations.','evr_language');?>" for="reg_limit">
                    <?php _e('Event Seating Capacity','evr_language');?> <a><span>?</span></a>
                    </td>
                    <td>
                    <input  class="count" name="reg_limit" value="<?php echo $reg_limit;?>"/>
                    </td>
                </tr>
<?php    
    global $wpdb;
    $locations_array = array();
    $location_status 	= get_option( 'evr_location_license_status' );
    if ($location_status !== false && $location_status == 'valid' ) {
    $sql = "SELECT * FROM " . get_option('evr_location')." ORDER BY location_name";
    $locations_array = $wpdb->get_results( $sql );
    }

    if( (!empty( $locations_array )) && ($location_status=="valid")):
?>
</table>
<script type="text/javascript">
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
<?php 
if($location_list >= '1'){
echo '<style type="text/css">.custom_addrs{display:none;}</style>'; 
}
?>
    <div class="input select">
	<table>	<tr><td><label for="select_location">Event Location: </label></td><td>
			<select name="location_list" id="location_list" onchange="showUser(this.value)">
				<option value="<?php echo $location_list;?>"><?php echo $location_tag;?> </option>
				<option value="0">Custom</option>
    <?php
		foreach( $locations_array as $location ) : 
        ?>
			<option value="<?php echo $location->id; ?>"><?php echo stripslashes($location->location_name); ?></option>
		<?php
		endforeach;
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
                    <input class= "title" id="event_location" name="event_location" type="text" size="50" value="<?php echo $event_location;?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="first" for="event_street"><?php _e('Street','evr_language');?></label>
                    </td>
                    <td>
                    <input  class= "title" id="event_street" name="event_street" type="text"  value="<?php echo $event_address;?>"/>
                    </td>
                </tr>		
				<tr>
                    <td><label for="event_city">
					<?php _e('City','evr_language');?></label></td><td><input id="event_city" name="event_city" type="text" value="<?php echo $event_city;?>"/></td></tr>
                <tr>
                    <td><label for="event_state">
					<?php _e('State','evr_language');?></label></td><td><input id="event_state" name="event_state" type="text"  value="<?php echo $event_state;?>"/></td></tr>
                <tr>
                    <td>
                    <label for="event_postcode">
					<?php _e('Postcode','evr_language');?></label>
                    </td>
                    <td>
                    <input id="event_postcode" name="event_postcode" type="text" value="<?php echo $event_postal;?>" />
                    </td>
                </tr>
                </table>
		</div>
        <table>
        <?php
	else : ?>
		<tr>
                    <td>
                    <label class="tooltip" title="<?php _e('Enter the name of the business or facility where the event is being held','evr_language');?>" for="event_location">
                    <?php _e('Event Location/Venue','evr_language');?><a><span> ?</span></a></label>
                    </td>
                    <td>
                    <input class= "title" id="event_location" name="event_location" type="text" size="50" value="<?php echo stripslashes($event->event_location);?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="first" for="event_street"><?php _e('Street','evr_language');?></label>
                    </td>
                    <td>
                    <input  class= "title" id="event_street" name="event_street" type="text"  value="<?php echo $event->event_address;?>" />
                    </td>
                </tr>		
				<tr>
                    <td><label for="event_city">
					<?php _e('City','evr_language');?></label></td><td><input id="event_city" name="event_city" type="text" value="<?php echo $event->event_city;?>"/></td></tr>
                <tr>
                    <td><label for="event_state">
					<?php _e('State','evr_language');?></label></td><td><input id="event_state" name="event_state" type="text" value="<?php echo $event->event_state;?>" /></td></tr>
                <tr>
                    <td>
                    <label for="event_postcode">
					<?php _e('Postcode','evr_language');?></label>
                    </td>
                    <td>
                    <input id="event_postcode" name="event_postcode" type="text" value="<?php echo $event->event_postal;?>"/>
                    </td>
                </tr>
		<?php 
	endif; 
?>  

                <tr>
                    <td>
                    <legend class="tooltip" title="<?php _e('All location information must be complete for Google Map feature to work.','evr_language');?>">
					<?php _e('Use Google Maps On Registration Page','evr_language');?> <a><span>?</span></a></legend>
                    </td>
                    <td>
                    <label for="google_map_yes"><input type="radio" class="radio" name="google_map" value="Y" <?php if ($event->google_map == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?></label>
                    <label for="google_map_no"><input type="radio" class="radio" name="google_map" value="N"  <?php if ($event->google_map == "N"){echo "checked";}?> /><?php _e('No','evr_language');?>
                    </label>
                    </td>
                </tr> </table>
                <?php
                if ($location_status!=="valid"){
                    echo "<hr><font color='red'><br/><br/><p align='center'>Stop typing your locations over and over<br/>";
                    echo "Get the Location Add-On for Event Registration Today<br/>";
                    echo "Have all your locations accessible in an easy dropdown list<br/>";
                    echo '<a href="http://wpeventregister.com/a-new-add-on-for-event-registration/">Location Module</a><br/></p></font>';
                    
                }
                ?>
        </div>
        <div id="tab3"class="tab_content">
            <h2>EVENT TIMES</h2>
          	              <table><tr>
                        <td><b><?php _e('Start Date','evr_language');?></b></td>
                        <?php 
                        $start = strtotime('6:00am');
                        $end = strtotime('11:45pm');
                        
                        
                        ?>
                        <td><label  for="start_date"><?php evr_DateSelector( "\"start", strtotime($event->start_date));?></label></td>
                        <td><b><?php _e('Start Time','evr_language');?></b></td><td><label for="start_time"><?php 
                        echo '<select name="start_time">';
                        
                        if ($event->start_time != ""){echo '<option>'.$event->start_time.'</option>';}
                        for ($i = $start; $i <= $end; $i += 900)
                        	{echo '<option>' . date('g:i a', $i);}
                        echo '</select>';
                        ?></label></td>
                        </tr>
                        <tr><td><b><?php _e('End Date','evr_language');?></b></td><td><label for="end_date"><?php evr_DateSelector( "\"end",strtotime($event->end_date)); ?></label></td>
                        <td><b><?php _e('End Time','evr_language');?></b></td><td><label for="end_time"><?php
                        echo '<select name="end_time">';
                        if ($event->end_time != ""){echo '<option>'.$event->end_time.'</option>';}
                        for ($i = $start; $i <= $end; $i += 900)
                        	{ echo '<option>' . date('g:i a', $i); }
                        echo '</select>';?></label></td>
                        </tr>
                        <tr></tr>
                        <tr><td>Close Registration on </td><td><select name="close" >
                        <?php
                        
                         if ($event->close == "start"){echo '<option value="start">Start of Event</option>';}
                         if ($event->close == "end"){echo '<option value="end">End of Event</option>';}
                         
                         ?>
                        <option value="start">Start of Event</option><option value="end">End of Event</option></select></td></tr>
                    </table>
        </div>

        <div id="tab4"class="tab_content">
            <table>
                <tr>
                    <td colspan="2">
                    <br />
                    <label  class="tooltip" title="<?php _e('If you will accept checks or cash, usually when accepting payment at event/on-site.','evr_language');?>">
   					<?php _e('Will you accept checks/cash for this event? ','evr_language');?><a><span>?</span></a></label>
                    <label for="accept_checks"><input type="radio" name="allow_checks" class="radio" id="accept_checks_yes" value="Y" <?php if ($event->allow_checks == "Y"){echo "checked";};?>/><?php _e('Yes','evr_language');?></label>
                    <label for="free_event_no"><input type="radio" name="allow_checks" class="radio" id="accept_checks_no" value="N" <?php if ($event->allow_checks == "N"){echo "checked";};?> /><?php _e('No ','evr_language');?></label>
                    </td>
                </tr>
            
            
                <tr>
                    <td colspan="2">
                    <br />
                    <label class="tooltip" title="<?php _e('You can point your register now button to an external registration site/page by selecting yes and entering the url!','evr_language');?>">
                    <?php _e('Are you using an external registration?','evr_language');?> <a><span>?</span></a></label>
                    <label>
                    <input type="radio" name="outside_reg" class="radio" id="outside_reg_yes" value="Y" <?php if ($event->outside_reg == "Y"){echo "checked";};?>/><?php _e('Yes','evr_language');?> 
                    </label><label>
                    <input type="radio" name="outside_reg" class="radio" id="outside_reg_no" value="N" <?php if ($event->outside_reg == "N"){echo "checked";};?> /><?php _e('No','evr_language');?> 
                    </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <label class="tooltip" title="<?php _e('Enter the url hyperlink to another webpage or website external registration','evr_language');?>">
                    <?php _e('External registration URL','evr_language');?> <a><span> ?</span></a><input class= "title" id="external_site" name="external_site" type="text" value="<?php echo $event->external_site;?>" /></label> 
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
                <?php
                $reg_form_defaults = unserialize($event->reg_form_defaults);
                if ($reg_form_defaults !=""){
                    if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";} else {$inc_address = "N";}
                    if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";} else {$inc_city = "N";}
                    if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";} else {$inc_state = "N";}
                    if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";} else {$inc_zip = "N";}
                    if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";} else {$inc_phone = "N";}
                    if (in_array("Company", $reg_form_defaults)) {$inc_comp = "Y";} else {$inc_comp = "N";}
                    if (in_array("CoAddress", $reg_form_defaults)) {$inc_coadd = "Y";} else {$inc_coadd = "N";}
                    if (in_array("CoCity", $reg_form_defaults)) {$inc_cocity = "Y";} else {$inc_cocity = "N";} 
                    if (in_array("CoState", $reg_form_defaults)) {$inc_costate = "Y";} else {$inc_costate = "N";}
                    if (in_array("CoPostal", $reg_form_defaults)) {$inc_copostal = "Y";} else {$inc_copostal = "N";}
                    if (in_array("CoPhone", $reg_form_defaults)) {$inc_cophone = "Y";} else {$inc_cophone = "N";}
                    }
                    else {
                        $inc_address = "N";
                        $inc_city = "N";
                        $inc_state = "N";
                        $inc_zip = "N";
                        $inc_phone = "N";
                        $inc_comp = "N";
                        $inc_coadd = "N";
                        $inc_cocity = "N";
                        $inc_costate = "N";
                        $inc_copostal = "N";
                        $inc_cophone = "N";
                        
                    }
                ?>
                <tr>
                    <td colspan="2">
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Address" <?php if ($inc_address == "Y"){echo "checked";};?> /><?php _e('Street Address','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="City" <?php if ($inc_city == "Y"){echo "checked";};?> /><?php _e('City','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="State" <?php if ($inc_state == "Y"){echo "checked";};?> /><?php _e('State or Province','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Zip" <?php if ($inc_zip == "Y"){echo "checked";};?> /><?php _e('Zip or Postal Code','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Phone" <?php if ($inc_phone == "Y"){echo "checked";};?> /><?php _e('Phone Number','evr_language');?></label>
                </tr>
                <tr>
                    <td colspan="2">
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="Company" <?php if ($inc_comp == "Y"){echo "checked";};?>  /><?php _e('Company','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoAddress" <?php if ($inc_coadd == "Y"){echo "checked";};?> /><?php _e('Co. Addr','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoCity" <?php if ($inc_cocity == "Y"){echo "checked";};?> /><?php _e('Co. City','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoState" <?php if ($inc_costate == "Y"){echo "checked";};?>  /><?php _e('Co. State/Prov','evr_language');?></label>
                    <label><INPUT class="radio" type="checkbox" name="reg_form_defaults[]" value="CoPostal" <?php if ($inc_copostal == "Y"){echo "checked";};?> /><?php _e('Co. Postal','evr_language');?></label>
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
            <input type="radio" name="waiver" class="radio" value="Y" <?php if($event->waiver == "Y"){echo "checked";};?> />&nbsp;<?php _e('Yes','evr_language');?>
            </label></td>
            <td>
            <label>
            <input type="radio" name="waiver" class="radio" value="N" <?php if($event->waiver == "N"){echo "checked";};?> />&nbsp;<?php _e('No','evr_language');?> 
            </label></td></tr>
            <tr><td colspan="3">          
            <label  class="tooltip" title="<?php _e('Enter the content for the event waiver','evr_language');?>" >
            <?php _e('Waiver Content','evr_language');?> <a><span>?</span></a></label>
              <?php
             
             
              if (function_exists('wp_editor')){
               echo "</td></tr></table>";
              wp_editor( htmlspecialchars_decode($event->waiver_content), 'waiver_content', $editor_settings ); 
                    } else {  ?>
               <a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
               </td></tr></table>
               <textarea name="waiver_content" id="waiver_content" style="width: 100%; height: 200px;"><?php echo $event->waiver_content;?></textarea>
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
                <input class= "title" id="event_location" name="more_info" type="text" value="<?php echo $event->more_info;?>" /></label>
                </td>
            </tr>
            <tr>
                <td><label class="tooltip" title="<?php _e('Enter the url to an image you would like displayed next to the event in the event listings. Size should be 150 x112','evr_language');?>">
                <?php _e('Thumbnail Image URL','evr_language');?> <a><span> ?</span></a></td><td><input class= "title" id="image_link" name="image_link" type="text" value="<?php echo $event->image_link;?>" />
				    </label>
                </td>
            </tr>
            <tr>
                <td><label class="tooltip" title="<?php _e('Enter the url of an image you wish displayed above the registration form.  The image should be no wider than 450.','evr_language');?>">
                <?php _e('Header Image URL','evr_language');?> <a><span> ?</span></a></td><td><input class= "title" id="header_image" name="header_image" type="text" value="<?php echo $event->header_image;?>" /></label>
                </td>
            </tr>
        </table>
    </div>
     <?php /* if (get_option('evr_coordinator_active')=="Y"){ */
        $coordinator_status 	= get_option( 'evr_coordinator_license_status' );
        if( ($coordinator_status !== false && $coordinator_status == 'valid' ) || (get_option('evr_coordinator_active')=="Y")){ 
     ?>
    <div id="tab5" class="tab_content">
            <h2><?php _e('Coordinator Options','evr_language');?></h2>
            <label  class="tooltip" title="<?php _e('If you want to send alerts to a unique event coordinator','evr_language');?>">
            <?php _e('Do you want to send alerts to a coordinator for this event?','evr_language');?> <a><span>?</span></a></label>
            <label>
            <input type="radio" name="send_coord" class="radio" id="send_coord_yes" value="Y" <?php if($event->send_coord == "Y"){echo "checked";};?>/><?php _e('Yes','evr_language');?>
            </label><label>
            <input type="radio" name="send_coord" class="radio" id="send_coord_no"  value="N" <?php if($event->send_coord == "N"){echo "checked";};?> /><?php _e('No','evr_language');?> 
            </label><br />
            <br /> 
            <table>
            <tr>
            <td colspan="2">
            <label for="contact"><?php _e('Coordinator email:','evr_language');?></label>
            <input name="coord_email" type="text" size="65" value="<?php echo $event->coord_email;?>" class="regular-text" /></td>
        </tr></table>
<table><tr><td colspan="2"><label  class="tooltip" title="<?php _e('Enter the text for the registration alert email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Coordinator Registration Alert Email','evr_language');?> <a><span>?</span></a></label></td></tr></table>
            
            <?php
              
                
               if (function_exists('the_editor')){
               //wp_editor( $coord_msg, 'coord_msg', $editor_settings );
                 the_editor(stripslashes($event->coord_msg), "coord_msg", '', false);
                    } else {  ?>
               <a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
               <textarea name="coord_msg" id="coord_msg" style="width: 100%; height: 200px;"><?php echo stripslashes($event->coord_msg);?></textarea>
                    <?php } ?>
            <hr />
<table><tr><td colspan="2"><label  class="tooltip" title="<?php _e('Enter the text for the payment alert email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Coordinator Payment Alert Email','evr_language');?> <a><span>?</span></a></label></td></tr></table>            
            
            <?php
                if (function_exists('the_editor')){
               //wp_editor( $coord_pay_msg, 'coord_pay_msg', $editor_settings );
                the_editor(stripslashes($event->coord_pay_msg), "coord_pay_msg", '', false);
                    } else {  ?>
               <a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
               <textarea name="coord_pay_msg" id="coord_pay_msg" style="width: 100%; height: 200px;"><?php echo stripslashes($event->oord_pay_msg);?></textarea>
                    <?php } ?>
               
        </div>
    <?php } else { ?>
             <div id="tab5" class="tab_content">
                <h2><?php _e('Coordinator Options','evr_language');?></h2>
                <font color="red">This feature is available in an add on module.</font>
                <ul>
                <li>Option to send unique email to a unique coordinators email address for each event registration.</li>
                <li>WYSIWYG editor for coordinator's email registration alert.</li>
                <li>Option to send unique email to a unique coordinators email address for each event payment recieved via PayPal IPN.</li>
                <li>WYSIWYG editor for coordinator's email payment notification alert.</li>
                </ul>
                <p>The module is a single site license.  To purchase this add on module:</p>

<p><a href="http://wpeventregister.com/downloads/event-registration-coordinator-module/">BUY COORDINATOR MODULE</a></p>
<p>&nbsp;</p>


            </div>
      <?php  } ?>
        <div id="tab6"class="tab_content">
            <h2><?php _e('Confirmation eMail','evr_language');?></h2>
            <table>
            <tr><td>
            <label  class="tooltip" title="<?php _e('If you have send mail option enabled in the company settings, you can override the default mail by creating a custom mail for this event.','evr_language');?>">
            <?php _e('Do you want to use a custom email for this event?','evr_language');?> <a><span>?</span></a></label></td>
            <td><label>
            <input type="radio" name="send_mail" class="radio" value="Y" <?php if($event->send_mail == "Y"){echo "checked";};?> /><?php _e('Yes','evr_language');?>
            </label></td>
            <td>
            <label>
            <input type="radio" name="send_mail" class="radio" value="N" <?php if($event->send_mail == "N"){echo "checked";};?> /><?php _e('No','evr_language');?> 
            </label></td></tr>
            <tr><td colspan="3">          
            <label  class="tooltip" title="<?php _e('Enter the text for the confirmation email.  This email will be sent in text format.  See User Manual for data tags.','evr_language');?>" >
            <?php _e('Custom Confirmation Email','evr_language');?> <a><span>?</span></a></label>
              <?php
             
             
              if (function_exists('wp_editor')){
               echo "</td></tr></table>";
              wp_editor( htmlspecialchars_decode(stripslashes($event->conf_mail)), 'conf_mail', $editor_settings ); 
                    } else {  ?>
               <a href="javascript:void(0)" onclick="tinyfy(1,'conf_mail')"><input type="button" value="WYSIWG"/></a>
               </td></tr></table>
               <textarea name="conf_mail" id="conf_mail" style="width: 100%; height: 200px;"><?php echo stripslashes($event->conf_mail);?></textarea>
                    <?php } ?>
             
             
            <br />
            <br />         
            <input  type="submit" name="Submit" value="<?php _e('Update Event','evr_language'); ?>" id="add_new_event" />
            </form>
        </div>
    </div>
</div>
<div style="clear: both; display: block; padding: 10px 0; text-align:center;"><font color="blue"><?php _e('Please make sure you complete each section before submitting!','evr_language');?></font></div>
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
<?php 
}
?>