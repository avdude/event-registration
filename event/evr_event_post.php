<?php
//function to post new event form data to db
function evr_post_event_to_db(){
        global $wpdb;
        //retrieve post data
        //note about coupon code - coupon code information is not posted here, but from the item cost page.
          $event_name = esc_html((string)@$_REQUEST ['event_name']);
          $event_identifier = esc_html((string)@$_REQUEST ['event_identifier']);
          $display_desc = (string)@$_REQUEST ['display_desc'];  // Y or N
          $event_desc = esc_html((string)@$_REQUEST ['event_desc']);
          $event_category = serialize((string)@$_REQUEST['event_category']);
          $reg_limit = (string)@$_REQUEST ['reg_limit'];
          $event_location = (string)@$_REQUEST ['event_location'];
          $event_address = (string)@$_REQUEST['event_street'];
          $event_city = (string)@$_REQUEST['event_city'];
          $event_state =(string)@$_REQUEST['event_state'];
          $event_postal=(string)@$_REQUEST['event_postcode'];
          $location_list = (string)@$_REQUEST['location_list'];
          $google_map = (string)@$_REQUEST['google_map'];  // Y or N
          
          $start_month = (string)@$_REQUEST ['start_month'];
          $start_day = (string)@$_REQUEST ['start_day'];
          $start_year = (string)@$_REQUEST ['start_year'];
          $end_month = (string)@$_REQUEST ['end_month'];
          $end_day = (string)@$_REQUEST ['end_day'];
          $end_year = (string)@$_REQUEST ['end_year'];
          $start_time = (string)@$_REQUEST ['start_time'];
          $end_time = (string)@$_REQUEST ['end_time'];
          $close = (string)@$_REQUEST['close'];
          $allow_checks = (string)@$_REQUEST['allow_checks'];
          $waiver = (string)@$_REQUEST['waiver'];
          $waiver_content = (string)@$_REQUEST['waiver_content'];
          
          $outside_reg = (string)@$_REQUEST['outside_reg'];  // Yor N
          $external_site = (string)@$_REQUEST['external_site'];
          $custom_cur = (string)@$_REQUEST['custom_cur'];
          $reg_form_defaults = serialize((array)@$_REQUEST['reg_form_defaults']);
          $more_info = (string)@$_REQUEST ['more_info'];
          $image_link = (string)@$_REQUEST ['image_link'];
          $header_image = (string)@$_REQUEST ['header_image'];
          $event_cost = (string)@$_REQUEST ['event_cost'];
          
          $is_active = (string)@$_REQUEST ['is_active'];
          $send_mail = (string)@$_REQUEST ['send_mail'];  // Y or N
          $conf_mail = esc_html((string)@$_REQUEST ['conf_mail']);
          //build start date
          $start_date = $start_year."-".$start_month."-".$start_day;
          //build end date
          $end_date = $end_year."-".$end_month."-".$end_day;
          //set reg limit if not set
          if ($reg_limit == ''){$reg_limit = 999;}
          //added ver 6.00.13 
          $send_coord = (string)@$_REQUEST ['send_coord'];  // Y or N
          $coord_email = (string)@$_REQUEST ['coord_email'];
          $coord_msg = esc_html((string)@$_REQUEST ['coord_msg']);
          $coord_pay_msg = esc_html((string)@$_REQUEST ['coord_pay_msg']);
                        
            
                    
            $sql=array(
            'event_name'=>$event_name,
            'event_desc'=>$event_desc, 
            'location_list'=>$location_list,
            'event_location'=>$event_location,
            'event_address'=>$event_address,
            'event_city'=>$event_city,
            'event_state'=>$event_state,
            'event_postal'=>$event_postal,
            'google_map'=>$google_map,
            'outside_reg'=>$outside_reg,
            'external_site'=>$external_site,
            'display_desc'=>$display_desc, 
            'image_link'=>$image_link, 
            'header_image'=>$header_image,
            'event_identifier'=>$event_identifier,  
            'more_info'=>$more_info, 
            'start_month'=>$start_month, 
            'start_day'=>$start_day, 
            'start_year'=>$start_year, 
            'start_time'=>$start_time, 
            'start_date'=>$start_date,
            'end_month'=>$end_month, 
            'end_day'=>$end_day,
            'end_year'=>$end_year, 
            'end_date'=>$end_date, 
            'end_time'=>$end_time, 
            'close'=>$close,
            'reg_limit'=>$reg_limit,
            'custom_cur'=>$custom_cur, 
            'reg_form_defaults'=>$reg_form_defaults, 
            'allow_checks'=>$allow_checks,
            'send_mail'=>$send_mail, 
            'conf_mail'=>$conf_mail, 
            'is_active'=>$is_active, 
            'category_id'=>$event_category,
            'send_coord'=>$send_coord,
            'coord_email'=>$coord_email,
            'coord_msg'=>$coord_msg,
            'coord_pay_msg'=>$coord_pay_msg,
            'waiver'=>$waiver,
            'waiver_content'=>$waiver_content); 
                          
        		
            $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        	
        
            if ($wpdb->insert( get_option('evr_event'), $sql, $sql_data )){ ?>
            	<div id="message" class="updated fade"><p><strong><?php _e('The event ','evr_language'); echo stripslashes($_REQUEST['event_name']); _e('has been added.','evr_language');?> </strong></p>
                <p><strong><?php _e(' . . .Now returning you to event list . . ');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event was not saved!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p>
                <p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                </div>
                <?php } 
}
?>