<?php
//function to copy an existing event
function evr_copy_event(){
        global $wpdb;
    	$event_id = $_REQUEST ['id'];
        $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id =" . $event_id;
        $rows = $wpdb->get_results( $sql );
        //retrieve post data
        //note about coupon code - coupon code information is not posted here, but from the item cost page.
        //no cost information or coupon information is copied when and event is copiedif ($rows){
            foreach ($rows as $event){
                $event_id       = $event->id;
                $event_name        = "Copy of ".$event->event_name;
                $event_identifier  = "CPY-".$event->event_identifier;
				$event_desc        = $event->event_desc;
				$image_link        = $event->image_link;
				$header_image      = $event->header_image;
				$display_desc      = $event->display_desc;
				$event_location    = $event->event_location;
                $event_address     = $event->event_address;
                $event_city        = $event->event_city;
                $event_postal      = $event->event_postal;
                $event_state       = $event->event_state;
				$more_info         = $event->more_info;
				$reg_limit         = $event->reg_limit;
				$event_cost        = $event->event_cost;
                $allow_checks      = $event->allow_checks;
				$is_active         = $event->is_active;
				$start_month       = $event->start_month;
				$start_day         = $event->start_day;
				$start_year        = $event->start_year;
				$end_month         = $event->end_month;
				$end_day           = $event->end_day;
				$end_year          = $event->end_year;
				$start_time        = $event->start_time;
				$end_time          = $event->end_time;
				$conf_mail         = $event->conf_mail;
				$send_mail         = $event->send_mail;
  		        $event_category    = $event->event_category;
				$start_date        = $event->start_date;
				$end_date          = $event->end_date;
                $reg_form_defaults = $event->reg_form_defaults;
                $use_coupon         = $event->use_coupon;
                $coupon_code        = $event->coupon_code;
                $coupon_code_price  = $event->coupon_code_price;
            //build array to copy event
            $sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'event_address'=>$event_address,
            'event_city'=>$event_city,'event_state'=>$event_state,'event_postal'=>$event_postal,'display_desc'=>$display_desc, 
            'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
            'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
            'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
            'custom_cur'=>$custom_cur, 'reg_form_defaults'=>$reg_form_defaults, 'allow_checks'=>$allow_checks, 
            'send_mail'=>$send_mail, 'conf_mail'=>$conf_mail, 'is_active'=>$is_active, 'category_id'=>$event_category, 'use_coupon'=>$use_coupon,
            'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price); 
            $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
            if ($wpdb->insert( get_option('evr_event'), $sql, $sql_data )){ 
                $lastID = $wpdb->insert_id;
                ?><div id="message" class="updated fade"><p><strong><?php _e('The event ','evr_language'); echo stripslashes($_REQUEST['event_name']); _e('has been added.','evr_language');?> </strong></p></div>
                <?php 
                $events_question_tbl = get_option ( 'evr_question' );
                $questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where event_id = $event_id order by sequence ASC" );
                if ($questions) {
         				foreach ( $questions as $question ) {
         				   $sql = array('event_id'=>$lastID, 'sequence'=>$question->sequence,'question_type'=>$question->question_type, 
                              'question'=>$question->question,'response'=>$question->response ,'required'=>$question->required );
                        $sql_data = array('%s','%s','%s','%s','%s','%s');
                        if ($wpdb->insert( get_option('evr_question'), $sql, $sql_data )){ }
               				   }?>
                           <div id="message" class="updated fade"><p><strong><?php _e('The questions have been added.','evr_language');?> </strong></p></div>
                <?php }
                $items = $wpdb->get_results( "SELECT * FROM ". get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC" );
                if ($items){
                    foreach ($items as $item){
                        $sql=array('sequence'=>$item->sequence,'event_id'=>$lastID, 'item_title'=>$item->item_title, 'item_description'=>$item->item_description, 
                            'item_cat'=>$item->item_cat, 'item_limit'=>$item->item_limit, 'item_price'=>$item->item_price, 'free_item'=>$item->free_item,'item_available_start_date'=>$item->item_available_start_date,  
                            'item_available_end_date'=>$item->item_available_end_date, 'item_custom_cur'=>$item->item_custom_cur); 
                        $sql_data = array('%s','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s');
                        if ($wpdb->insert( get_option('evr_cost'), $sql, $sql_data )){
                            ?>
        	                   <div id="message" class="updated fade"><p><strong><?php _e('The cost ','evr_language'); echo $item_title; _e('has been added.','evr_language');?> </strong></p></div>
                            <?php 
                        } 
                        else { ?>
        		              <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The cost was not saved!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p></div>
                            <?php
                        }
                    }
                }
                ?>
                <div id="message" class="updated fade"><p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event was not saved!','evr_language');?><?php print $wpdb->last_error; ?>.</strong></p></div>
                <div id="message" class="updated fade"><p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php } 
           }     
}
?>