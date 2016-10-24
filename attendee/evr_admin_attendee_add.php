<?php
//remved date filter on pricing in attendee admin
function evr_admin_add_attendee(){
    global $wpdb;
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $event = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . get_option ( 'evr_event' ). " WHERE id = %d", $event_id ) );
$reg_form_defaults = (array)@unserialize($event->reg_form_defaults);
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
    $event_category = (array)@unserialize($event->event_category);

    $event_category = (array)@unserialize($event->event_category);
    $reg_limit = $event->reg_limit;
    $event_name = stripslashes($event->event_name);
    $use_coupon = $event->use_coupon;


?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Attendee Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:65%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3 class='hndle'><span><?php _e('Add Attendee:','evr_language');?><?php echo "  ".stripslashes($event->event_name);?></span></h3>
                         <div class="inside">
                            <div class="padding">        
                            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" onSubmit="return validateForm(this)">
                            <ul>
                                <li><label for="fname"><?php _e('First Name','evr_language');?></label><input id="fname" name="fname" /></li>
                                <li><label for="lname"><?php _e('Last Name','evr_language');?></label><input id="lname" name="lname" /></li>
                                <li><label for="email" ><?php _e('Email Address','evr_language');?></label><input id="email" name="email" /></li>
                                    <?php if ($inc_phone == "Y") { ?>
                                <li><label for="phone" ><?php _e('Phone Number','evr_language');?></label><input id="phone" name="phone" /></li>
                                    <?php } ?>
                                    <?php if ($inc_address == "Y") { ?> 
                                <li><label for="address"><?php _e('Address','evr_language');?></label><input id="address" name="address" /></li>
                                    <?php } ?>
                                    <?php if ($inc_city == "Y") { ?> 
                                <li><label for="city"><?php _e('City','evr_language');?></label><input id="city" name="city" /></li>
                                    <?php } ?>
                                    <?php if ($inc_state == "Y") { ?> 
                                <li><label for="state"><?php _e('State/Province','evr_language');?></label><input id="state" name="state" /></li>
                                    <?php } ?> 
                                    <?php if ($inc_zip == "Y") { ?> 
                                <li><label for="zip"><?php _e('Zip/Postal Code','evr_language');?></label><input id="zip" name="zip" /></li>
                                    <?php } ?>       
                                    <?php if ($use_coupon == "Y") { ?>
                                <li><label for="coupon"><?php _e('Enter coupon code for discount off primary registration?','evr_language');?></label><input id="coupon" name="coupon" /></li>
                                    <?php } ?>
                                    <?php     
                                    $questions = $wpdb->get_results("SELECT * from ".get_option('evr_question')." where event_id = '$event_id' order by sequence");
                                    if ($questions) {
                                        foreach ($questions as $question) { ?>
                                            <li><label><?php
                                            echo $question->question;
                                             ?>
                                             </label>
                                             <?php
                                            evr_form_build($question);
                                            ?></li>
                                            <?php }
                                            }
                                    ?>
                            </ul>
                            <?php 	
                            $num = 0;                              
                            $sql2= "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
                            $attendee_count  = $wpdb->get_var($sql2);
                            If ($attendee_count >= 1) {$num = $attendee_count;}
                            $available = $event->reg_limit - $num;
                            //echo "count is ". $attendee_count." !";
                            if ($available >= "1"){ 
                                        ?>                
                            <hr />
                            <h2 align="center">REGISTRATION FEES</h2><br />
                             <?php
                                $open_seats = $available;
                                $curdate = date("Y-m-d");
                                $items = $wpdb->get_results( "SELECT * FROM ". get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC" );
                                if ($items){
                                    foreach ($items as $item){
                                                        $event_id       = $event->id;
                                                        $item_id          = $item->id;
                                                        $item_sequence    = $item->sequence;
                                            			$event_id         = $item->event_id;
                                                        $item_title       = $item->item_title;
                                                        $item_description = $item->item_description; 
                                                        $item_cat         = $item->item_cat;
                                                        $item_limit       = $item->item_limit;
                                                        $item_price       = $item->item_price;
                                                        $free_item        = $item->free_item;
                                                        $item_start_date  = $item->item_available_start_date;
                                                        $item_end_date    = $item->item_available_end_date;
                                                        $item_custom_cur  = $item->item_custom_cur;
                                                        if ($item_custom_cur == "GBP"){$item_custom_cur = "&pound;";}
                                                        if ($item_custom_cur == "USD"){$item_custom_cur = "$";}
                            ?>
                            <input type="hidden" name="reg_type" value="RGLR"/>
                            <p><select name="PROD_<?php echo $event_id . "-" . $item_id . "_" . $item_price; ?>" id = "PROD_<?php echo
                                    $event_id . "-" . $item_id . "_" . $item_price; ?>" onChange="CalculateTotal(this.form)"  >
                            <option value="0">0</option>
                            <?php
                            if ($item_cat == "REG"){
                             if ($ticket_limit != ""){
                                if ($available >= $item_limit){$available = $item_limit;}
                                }
                             for($i=1; $i<$available+1; $i++) { ?>
                                    <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                            	<?php } }
                            if ($item_cat != "REG"){
                            $num_select = "10";    
                            if ($ticket_limit != ""){
                                $num_select = $item_limit;}
                             for($i=1; $i<$num_select+1; $i++) { ?>
                                    <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                            	<?php } } 
                            ?>
                            </select>
                            <?php echo $item_title . "    " . $item_custom_cur . " " . $item_price; ?></p>
                            <?php 
                            // }
                             }}?>
                            <br /><b>Registration TOTAL  <input type="text" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></b>
                            <br />
                            <?php
                            } else {
                                echo '<hr><br><b><font color="red">';
                                _e('This event has reached registration capacity.','evr_language');
                                echo "<br>";
                                _e('Please provide your information to be placed on the waiting list.','evr_language');
                                echo '</b></font>';
                                ?>
                                <input type="hidden" name="reg_type" value="WAIT" />
                                <?php   
                            }
                            ?>
                            <script language="JavaScript" type="text/javascript">
                            <!--
                            /* This script is Copyright (c) Paul McFedries and 
                            Logophilia Limited (http://www.mcfedries.com/).
                            Permission is granted to use this script as long as 
                            this Copyright notice remains in place.*/
                            function CalculateTotal(frm) {
                                var order_total = 0
                                // Run through all the form fields
                                for (var i=0; i < frm.elements.length; ++i) {
                                    // Get the current field
                                    form_field = frm.elements[i]
                                    // Get the field's name
                                    form_name = form_field.name
                                    // Is it a "product" field?
                                    if (form_name.substring(0,4) == "PROD") {
                                        // If so, extract the price from the name
                                        item_price = parseFloat(form_name.substring(form_name.lastIndexOf("_") + 1))
                                        // Get the quantity
                                        item_quantity = parseInt(form_field.value)
                                        // Update the order total
                                        if (item_quantity >= 0) {
                                            order_total += item_quantity * item_price
                                        }
                                    }
                                }
                                // Display the total rounded to two decimal places
                                frm.total.value = round_decimals(order_total, 2)
                            }
                            function round_decimals(original_number, decimals) {
                                var result1 = original_number * Math.pow(10, decimals)
                                var result2 = Math.round(result1)
                                var result3 = result2 / Math.pow(10, decimals)
                                return pad_with_zeros(result3, decimals)
                            }
                            function pad_with_zeros(rounded_value, decimal_places) {
                                // Convert the number to a string
                                var value_string = rounded_value.toString()
                                // Locate the decimal point
                                var decimal_location = value_string.indexOf(".")
                                // Is there a decimal point?
                                if (decimal_location == -1) {
                                    // If no, then all decimal places will be padded with 0s
                                    decimal_part_length = 0
                                    // If decimal_places is greater than zero, tack on a decimal point
                                    value_string += decimal_places > 0 ? "." : ""
                                }
                                else {
                                    // If yes, then only the extra decimal places will be padded with 0s
                                    decimal_part_length = value_string.length - decimal_location - 1
                                }
                                // Calculate the number of decimal places that need to be padded with 0s
                                var pad_total = decimal_places - decimal_part_length
                                if (pad_total > 0) {
                                    // Pad the string with 0s
                                    for (var counter = 1; counter <= pad_total; counter++) 
                                        value_string += "0"
                                    }
                                return value_string
                            }
                            //-->
                            </script>
                            <hr />
                            
                            <input type="hidden" name="action" value="post_attendee"/> 
                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                            <p align="center"><input type="submit" name="Submit" value="<?php _e('SUBMIT','evr_language');?>"/> </p>
                            </form>
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