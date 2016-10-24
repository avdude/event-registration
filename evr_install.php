<?php
/**
 * @author David Fleming
 * @copyright 2010
 */
/*
6.02.00 - No db changes
6.01.09 - No db changes
6.01.08 - Modify question table to use installer/updater
6.01.07 - No db changes
6.01.06 - No db changes
6.01.05 - No db changes
6.01.04 - resolved issue with custom answer table - dropped primary key (will retro drop existing key on earler version table)
6.01.01 - changed options table for company settings
        - various patches
6.00.32 - added waiver info - event & attendee tables
        - created evr_options table for settings instead of array
6.00.30 - droped memo_old from payments
6.00.29 - No DB Changes
6.00.28 - No DB Changes
6.00.27 - No DB Changes
6.00.26 - added remark field to custom questions
        - fixed extra spacing issue with dbDelta
        - added location_list VARCHAR(4) DEFAULT NULL to event db
6.00.25 - No DB Changes
6.00.24 - No DB Changes
6.00.23 - No DB Changes
6.00.22 - No DB Changes
6.00.21 - No DB Changes
*/
/*
6.00.20 -
Added token field to attendee table to prevent duplicate entries.
*/
/*
6.00.19 -
Added close field to events to signify when events close, start or end date.
*/
/*
6.00.18 -
No db changes
*/
/*
6.00.17
increased field size for Events - Reg_Form_defaults to 200
Removed type definition for Question and Answer tables
-removed automatic notification of plugin activation
*/
/*
6.00.16 -
No db changes
*/
/*
6.00.15 -
added company fields to attendee
*/
/*
6.00.14 -
added tax to attendee
*/
/*
6.00.13 -Added fields to event
send_coord VARCHAR(2) DEFAULT NULL,
coord_email VARCHAR(65) DEFAULT NULL,
coord_msg VARCHAR (1000) DEFAULT NULL,
coord_pay_msg VARCHAR (1000) DEFAULT NULL
*/
/* 6.00.12 - Added fields to event table  
send_mail VARCHAR (2) DEFAULT NULL,
send_contact VARCHAR (2) DEFAULT NULL,
contact_email VARCHAR(65) DEFAULT NULL,
added conditionals to upgrading attendee table for conflicts with expresso*/
function EVR_Offset($dt, $year_offset = '', $month_offset = '', $day_offset = '')
{
    return ($dt == '0000-00-00') ? '' : date("Y-m-d", mktime(0, 0, 0, substr($dt, 5,
        2) + $month_offset, substr($dt, 8, 2) + $day_offset, substr($dt, 0, 4) + $year_offset));
}
function evr_table_upgrade($evr_new_tbl,$evr_old_tbl){
     global $wpdb;
     if ($wpdb->get_var("SHOW TABLES LIKE '$evr_new_tbl'") != $evr_new_tbl) {
        $wpdb->query("CREATE TABLE IF NOT EXISTS " . $evr_new_tbl ." LIKE " . $evr_old_tbl);
        $wpdb->query("REPLACE INTO " . $evr_new_tbl ." SELECT * FROM " . $evr_old_tbl); 
        }   
}
//function to install plugin - load tables and wp_options
function evr_install()
{
    global $evr_date_format, $evr_ver, $wpdb, $cur_build, $table_message;
    $table_message = '';
    $cur_build = "6.02.00";
    $old_event_tbl = $wpdb->prefix . "events_detail";
    $old_db_version = get_option('events_detail_tbl_version');
//Update pages to new shortcode for events
    $wpdb->query("SELECT id FROM " . $wpdb->prefix . "posts " . " WHERE (post_content LIKE '%{EVRREGIS}%' AND post_type = 'page')");
    if ($wpdb->num_rows > 0) {

			$wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'{EVRREGIS}','[EVR_EVENTS]')");
            
            } 
            //End update
    if ((get_option('evr_was_upgraded')!= "Y")&& ($old_db_version < $cur_build)){
    if ($wpdb->get_var("SHOW TABLES LIKE '$old_event_tbl'") == $old_event_tbl) {
        evr_upgrade_tables();
    //create option in the wordpress options table to bypass upgrade in the future    
        $option_name = 'evr_was_upgraded' ;
    	$newvalue = "Y";
    	update_option($option_name, $newvalue);
     }}
    evr_attendee_db();
    evr_category_db();
    evr_question_db();
    evr_answer_db();    
    evr_event_db();
    evr_cost_db();
    evr_payment_db();
    evr_config_company();
    evr_generator();
    evr_change_array2options();
    //evr_createCompanyyArray();
    
    //add_evr_option("Test","Revised Value of Test Box");
    //evr_notification();removed automatic notification of plugin activation
}

function evr_change_array2options(){
    global $wpdb;
    //used once to update to version 6.01.00 or later
    if (!get_option('evr_company_settings')){
      //option has already been wiped!
      //no action required  
    }
    else {
        $company_options = get_option('evr_company_settings');
        //iterate through old options and add to new table
        foreach($company_options as $key=>$val) {
            add_evr_option($key, $val);
        }
        //now options have been added to new table, erase old option
        delete_option( 'evr_company_settings' );  
    }
}




function evr_upgrade_tables(){
    global $wpdb;
    $upgrade_version = "6.01.08";
//
// Attendee Table Copy Table, Replace Data, Add Colulmns        
//
        $new_attendee_tbl = $wpdb->prefix . "evr_attendee";
        $old_attendee_tbl = $wpdb->prefix . "events_attendee";
        evr_table_upgrade($new_attendee_tbl,$old_attendee_tbl);//order - ()new_table,old_table)
        //create option in the wordpress options tale for the event attendee table name
        $option_name = 'evr_attendee';
        $newvalue = $new_attendee_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event attendee table version
        $option_name = 'evr_attendee_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
        //Get an array of column names from attendee table      
           $sql = "SELECT * FROM ".$new_attendee_tbl;
           $wpdb->query($sql);
           $fields = $wpdb->get_col_info('name', -1);
           //change column names from values to keys for identifcation 
            $field_names = array_flip($fields);
        $value = "num_people";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD num_people varchar(45) COLLATE utf8_general_ci NULL;";
        if (!array_key_exists($value, $field_names)) {
            $wpdb->query($sql);
            } 
        $wpdb->query("UPDATE " . $new_attendee_tbl . " SET quantity = num_people");
        //
        $value = "reg_type";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD reg_type varchar(45) COLLATE utf8_general_ci NULL AFTER zip";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
        $value = "tickets";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD tickets mediumint NULL AFTER quantity";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
        $value = "payment_status";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD payment_status varchar(45) COLLATE utf8_general_ci NULL AFTER payment";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
        $value = "payment_date";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD payment_date varchar(30) COLLATE utf8_general_ci NULL AFTER txn_id";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "attendees";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD attendees mediumtext COLLATE utf8_general_ci NULL AFTER quantity";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "tax";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD tax VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
         //added for 6.00.15   
        $value = "company";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD company VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "co_address";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD co_address VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "co_city";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD co_city VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }       
        $value = "co_state";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD co_state VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }       
        $value = "co_zip";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD co_zip VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
        $value = "token";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD token VARCHAR(32) NOT NULL  DEFAULT'0'";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
                   $value = "waiver_agree";
        $sql = "ALTER TABLE ".$new_attendee_tbl." ADD waiver_agree VARCHAR(4) DEFAULT NULL COLLATE utf8_general_ci NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }        
//
// Event Table Copy Table, Replace Data, Add Colulmns        
//
        $new_event_tbl = $wpdb->prefix . "evr_event";
        $old_event_tbl = $wpdb->prefix . "events_detail";
        evr_table_upgrade($new_event_tbl,$old_event_tbl);//order - ()new_table,old_table)
        //create option for table name
        $option_name = 'evr_event';
        $newvalue = $new_event_tbl;
        update_option($option_name, $newvalue);
        //create option for table version
        $option_name = 'evr_event_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
        //Add new fields to table
        //Get an array of column names from attendee table      
        $sql = "SELECT * FROM ".$new_event_tbl;
        $wpdb->query($sql);
        $fields = $wpdb->get_col_info('name', -1);
        //change column names from values to keys for identifcation 
        $field_names = array_flip($fields);
        $value = "event_address";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD event_address VARCHAR(100) DEFAULT NULL AFTER event_location";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "event_city";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD event_city VARCHAR(100) DEFAULT NULL AFTER event_address";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "event_state";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD event_state VARCHAR(100) DEFAULT NULL AFTER event_city";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "event_postal";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD event_postal VARCHAR(100) DEFAULT NULL AFTER event_state";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "google_map";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD google_map VARCHAR (4) DEFAULT NULL AFTER event_postal";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "outside_reg";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD outside_reg VARCHAR (4) DEFAULT NULL AFTER google_map";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "external_site";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD external_site VARCHAR (100) DEFAULT NULL AFTER outside_reg";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "send_coord";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD send_coord VARCHAR (2) DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "coord_email";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD coord_email VARCHAR (65) DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "coord_msg";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD coord_msg TEXT DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "coord_pay_msg";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD coord_pay_msg TEXT DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "close";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD close VARCHAR (65) DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }   
        $value = "location_list";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD location_list VARCHAR(4) DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            } 
        //added by df for waiver
        $value = "waiver";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD waiver VARCHAR (2) DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        $value = "waiver_content";
        $sql = "ALTER TABLE ".$new_event_tbl." ADD waiver_content TEXT DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }    
        /*
                  send_coord VARCHAR(2) DEFAULT NULL,
                  coord_email VARCHAR(65) DEFAULT NULL,
                  coord_msg VARCHAR (1000) DEFAULT NULL,
                  coord_pay_msg VARCHAR (1000) DEFAULT NULL,
        $value = "";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
        */
        //
// Question Table Copy Table, Replace Data, Add Colulmns        
//
        $new_question_tbl = $wpdb->prefix . "evr_question";
        $old_question_tbl = $wpdb->prefix . "events_question_tbl";
        evr_table_upgrade($new_question_tbl,$old_question_tbl);//order - ()new_table,old_table)
        //create option in the wordpress options tale for the event question table name
        $option_name = 'evr_question';
        $newvalue = $new_question_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event question table version
        $option_name = 'evr_question_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
        $value = "remark";
        $sql = "ALTER TABLE ".$new_question_tbl." ADD remark TEXT DEFAULT NULL";
        if (!array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
//
// Answer Table Copy Table, Replace Data, Add Colulmns        
//        
        $new_answer_tbl = $wpdb->prefix . "evr_answer";
        $old_answer_tbl = $wpdb->prefix . "events_answer_tbl";
        evr_table_upgrade($new_answer_tbl,$old_answer_tbl);//order - ()new_table,old_table)
        //create option in the wordpress options tale for the event answer table name
        $option_name = 'evr_answer';
        $newvalue = $new_answer_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event answer table version
        $option_name = 'evr_answer_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
//
// Category Table Copy Table, Replace Data, Add Colulmns        
//        
        $new_category_tbl = $wpdb->prefix . "evr_category";
        $old_category_tbl = $wpdb->prefix . "events_cat_detail_tbl";
        evr_table_upgrade($new_category_tbl,$old_category_tbl);//order - ()new_table,old_table)
        //create option in the wordpress options table for the event category table
        $option_name = 'evr_category';
        $newvalue = $new_category_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event attendee table version
        $option_name = 'evr_category_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
//
// Payment Table Copy Table, Replace Data, Add Colulmns        
//        
        $new_payment_tbl = $wpdb->prefix . "evr_payment";
        $old_payment_tbl = $wpdb->prefix . "events_payment_transactions";
        evr_table_upgrade($new_payment_tbl,$old_payment_tbl);//order - ()new_table,old_table)
        //create option in the wordpress options tale for the event payment transaction table name
        $option_name = 'evr_payment';
        $newvalue = $new_payment_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event payment transaction table version
        $option_name = 'evr_payment_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
//
// Cost Table Copy Table, Replace Data, Add Colulmns        
//        
        $new_cost_tbl = $wpdb->prefix . "evr_cost";
        $old_cost_tbl = $wpdb->prefix . "events_detail";
        //Need to run query of events detail and create cost table based on that.
        if ($wpdb->get_var("SHOW TABLES LIKE '$new_cost_tbl'") != $new_cost_tbl) {
        $sql = "CREATE TABLE " . $new_cost_tbl . " (
                			id MEDIUMINT NOT NULL auto_increment,
                			sequence int(11) NOT NULL default '0',
                			event_id int(11) NOT NULL default '0',
                            item_title VARCHAR(75) DEFAULT NULL,
                            item_description VARCHAR(150) DEFAULT NULL,
                            item_cat VARCHAR (10) DEFAULT NULL,
                            item_limit VARCHAR (10) DEFAULT NULL,
                            item_price decimal(7,2) DEFAULT NULL,
                            free_item VARCHAR (4) DEFAULT NULL,
                            item_available_start_date VARCHAR (15) DEFAULT NULL,
                            item_available_end_date VARCHAR (15) DEFAULT NULL,
                            item_custom_cur VARCHAR(10) DEFAULT NULL,
                            PRIMARY KEY (id)
                			) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        //create option in the wordpress options tale for the event question table name
        $option_name = 'evr_cost';
        $newvalue = $new_cost_tbl;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event question table version
        $option_name = 'evr_cost_version';
        $newvalue = $upgrade_version;
        update_option($option_name, $newvalue);
        //Now get the pricing information from the events.
        $old_events = $wpdb->get_results("SELECT * from  " . $old_cost_tbl ." ORDER BY id");
        if ($old_events) {
            foreach ($old_events as $old_event) {
                //put old event into new table
                $event_id = $old_event->id;
                $item_start_date = EVR_Offset($old_event->start_date, 0, -2, 0);
                $sequence = '1';
                $title = 'Registration Fee';
                $description = 'Cost for registration for this event';
                $category = 'REG';
                $limit = '10';
                if ($old_event->event_cost == "" || $old_event->event_cost == "0") {
                    $free_item = "Y";
                } else {
                    $free_item = "N";
                }
                $sql = array('sequence' => $sequence, 'event_id' => $event_id, 'item_title' => $title,
                    'item_description' => $description, 'item_cat' => $category, 'item_limit' => $limit,
                    'item_price' => $old_event->event_cost, 'free_item' => $free_item,
                    'item_available_start_date' => $item_start_date, 'item_available_end_date' => $old_event->
                    end_date, 'item_custom_cur' => $old_event->custom_cur);
                $sql_data = array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s',
                    '%s');
                $wpdb->insert($new_cost_tbl, $sql, $sql_data) or die($wpdb->last_error);
            }
        }
        //Now update the ticket information for each attendee
        //Get attendee
        $attendees = $wpdb->get_results("SELECT * FROM " . get_option('evr_attendee') . " ORDER by id");
        if ($attendees) {
            foreach ($attendees as $attendee) {
                $attendee_id = $attendee->id;
                $num_people = $attendee->quantity;
                $event_id = $attendee->event_id;
                $item_order = array();
                $costs = $wpdb->get_results("SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC");
                if ($costs) {
                    foreach ($costs as $cost) {
                        $item_info = array('ItemID' => $cost->id, 'ItemEventID' => $cost->event_id,
                            'ItemCat' => $cost->item_cat, 'ItemName' => $cost->item_title, 'ItemCost' => $cost->
                            item_price, 'ItemCurrency' => $cost->item_custom_cur, 'ItemFree' => $cost->
                            free_item, 'ItemStart' => $cost->item_available_start_date, 'ItemEnd' => $cost->
                            item_available_end_date, 'ItemQty' => $num_people);
                        array_push($item_order, $item_info);
                        $cost = $cost->item_price;
                    }
                }
                $ticket_data = serialize($item_order);
                $payment = $num_people * $cost;
                $wpdb->update(get_option('evr_attendee'), array('reg_type' => 'RGLR', 'payment' =>
                    $payment, 'tickets' => $ticket_data), array('id' => $attendee_id), array('%s',
                    '%s', '%s'), array('%d')) or die($wpdb->last_error);
                }
           }
        }
        //Update shortcodes if previous version
    	$wpdb->query("SELECT id FROM " . $wpdb->prefix . "posts " . " WHERE (post_content LIKE '%{EVENTREGIS}%' AND post_type = 'page') ".
        "OR (post_content LIKE '%{EVENTREGPAY}%' AND post_type = 'page') ".
        "OR (post_content LIKE '%{EVENTPAYPALTXN}%' AND post_type = 'page') ".
        "OR (post_content LIKE '%[Event_Registration_Calendar]%' AND post_type = 'page') ".
        "OR (post_content LIKE '%[EVENT_REGIS_CATEGORY%' AND post_type = 'page') ".
        "OR (post_content LIKE '%[Event_Registration_Single%' AND post_type = 'page')");
		if ($wpdb->num_rows > 0) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'{EVENTREGIS}','{EVRREGIS}')");
			$wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'{EVENTREGPAY}','[EVR_PAYMENT]')");
			$wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'[Event_Registraiton_Calendar]','{EVR_CALENDAR}')");
            $wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'[Event_Registration_Single','[EVR_SINGLE')");
            $wpdb->query("UPDATE " . $wpdb->prefix . "posts SET post_content = REPLACE(post_content,'[EVENT_REGIS_CATEGORY','[EVR_CATEGORY')");
		}

/*
//removed by df for new evr_options
//
// Company Table Copy Table, Replace Data, Add Colulmns        
//
        $old_organization_tbl = $wpdb->prefix . "events_organization";
        if (($wpdb->get_var("SHOW TABLES LIKE '$old_organization_tbl'") == $old_organization_tbl) && (get_option('evr_company_settings')=="")){
        $ER_org_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $old_organization_tbl . " WHERE id=%d", 1), ARRAY_A) or die($wpdb->last_error);
        $company_options['company'] = $ER_org_data['organization'];
        $company_options['company_street1'] = $ER_org_data['organization_street1'];
        $company_options['company_street2'] = $ER_org_data['organization_street2'];
        $company_options['company_city'] = $ER_org_data['organization_city'];
        $company_options['company_state'] = $ER_org_data['organization_state'];
        $company_options['company_postal'] = $ER_org_data['organization_zip'];
        $company_options['company_email'] = $ER_org_data['contact_email'];
        $company_options['evr_page_id'] = "";
        $company_options['splash'] = "";
        $company_options['send_confirm'] = $ER_org_data['default_mail'];
        $company_options['message'] = htmlentities2($ER_org_data['message']);
        $company_options['thumbnail'] = $ER_org_data['show_thumb'];
        $company_options['calendar_url'] = ""; //$_POST['calendar_url';
        $company_options['default_currency'] = $ER_org_data['currency_format'];
        $company_options['donations'] = $ER_org_data['accept_donations'];
        $company_options['checks'] = "";
        $company_options['pay_now'] = "";
        $company_options['payment_vendor'] = $ER_org_data['payment_vendor'];
        $company_options['payment_vendor_id'] = $ER_org_data['payment_vendor_id'];
        $company_options['payment_vendor_key'] = $ER_org_data['txn_key'];
        $company_options['return_url'] = "";
        $company_options['notify_url'] = "";
        $company_options['cancel_return'] = "";
        $company_options['return_method'] = "";
        $company_options['use_sandbox'] = "N";
        $company_options['image_url'] = $ER_org_data['image_url'];
        $company_options['admin_message'] = "";
        $company_options['payment_subj'] = "Payment Received";
        $company_options['payment_message'] = "We received your event payment";
        $company_options['captcha'] = $ER_org_data['captcha'];
        update_option('evr_company_settings', $company_options);
    }
    */
    
}
function evr_attendee_db()
{
    //Define global variables
    global $wpdb, $cur_build,$table_message;
    global $evr_attendee_version;
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_attendee";
    $evr_attendee_version = $cur_build;
    //check the SQL database for the existence of the Event Attendee Database - if it does not exist create it.
    $sql = "CREATE TABLE " . $table_name . " (
					  id MEDIUMINT NOT NULL AUTO_INCREMENT,
					  lname VARCHAR(45) DEFAULT NULL,
					  fname VARCHAR(45) DEFAULT NULL,
					  address VARCHAR(45) DEFAULT NULL,
					  city VARCHAR(45) DEFAULT NULL,
					  state VARCHAR(45) DEFAULT NULL,
					  zip VARCHAR(45) DEFAULT NULL,
					  reg_type VARCHAR (45) DEFAULT NULL,
					  email VARCHAR(85) DEFAULT NULL,
					  phone VARCHAR(45) DEFAULT NULL,
                      company VARCHAR(45) DEFAULT NULL,
                      co_address VARCHAR(45) DEFAULT NULL,
                      co_city VARCHAR(45) DEFAULT NULL,
                      co_state VARCHAR(45) DEFAULT NULL,
                      co_zip VARCHAR(45) DEFAULT NULL,
                      date timestamp NOT NULL default CURRENT_TIMESTAMP,
                      event_id VARCHAR(45) DEFAULT NULL,
                      coupon VARCHAR(45) DEFAULT NULL,
                      quantity VARCHAR(45) DEFAULT NULL,
                      attendees MEDIUMTEXT DEFAULT NULL,
                      tickets MEDIUMTEXT DEFAULT NULL,
                      payment VARCHAR(45) DEFAULT NULL,
                      tax VARCHAR(45) DEFAULT NULL,
                      payment_status VARCHAR(45) DEFAULT NULL,
                      amount_pd VARCHAR (45) DEFAULT NULL,
                      payment_date varchar(30) DEFAULT NULL,
                      token varchar(32) NOT NULL DEFAULT '0',
                      waiver_agree varchar(4) DEFAULT NULL,
                      UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        if (dbDelta($sql)){
            //create option in the wordpress options tale for the event attendee table name
        $option_name = 'evr_attendee';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event attendee table version
        $option_name = 'evr_attendee_version';
        $newvalue = $evr_attendee_version;
        update_option($option_name, $newvalue);
        $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
}
function evr_category_db()
{
    //Define global variables
    global $wpdb, $cur_build,$table_message;
    global $evr_category_version;
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_category";
    $evr_category_version = $cur_build;
        $sql = "CREATE TABLE " . $table_name . " (
					  id MEDIUMINT NOT NULL AUTO_INCREMENT,
					  category_name VARCHAR(100) DEFAULT NULL,
					  category_identifier VARCHAR(45) DEFAULT NULL,
					  category_desc TEXT,
					  display_desc VARCHAR (4) DEFAULT NULL,
                      category_color VARCHAR(30) NOT NULL ,
                      font_color VARCHAR(30) NOT NULL DEFAULT '#000000',
                      UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        if (dbDelta($sql)){
        //create option in the wordpress options table for the event category table
        $option_name = 'evr_category';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event attendee table version
        $option_name = 'evr_category_version';
        $newvalue = $evr_category_version;
        update_option($option_name, $newvalue);
                    $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
}
function evr_event_db()
{
    //Define global variables
    global $wpdb, $cur_build, $table_message;
    global $evr_event_version;
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_event";
    $evr_event_version = $cur_build;
        $sql = "CREATE TABLE " . $table_name . " (
          id MEDIUMINT NOT NULL AUTO_INCREMENT,
          event_name VARCHAR(100) DEFAULT NULL,
          event_desc TEXT DEFAULT NULL,
          location_list VARCHAR(4) DEFAULT NULL,
          event_location VARCHAR(300) DEFAULT NULL,
          event_address VARCHAR(100) DEFAULT NULL,
          event_city VARCHAR(100) DEFAULT NULL,
          event_state VARCHAR(100) DEFAULT NULL,
          event_postal VARCHAR(100) DEFAULT NULL,
          google_map VARCHAR (4) DEFAULT NULL,
          outside_reg VARCHAR (4) DEFAULT NULL,
          external_site VARCHAR (100) DEFAULT NULL,
          display_desc VARCHAR (4) DEFAULT NULL,
          image_link VARCHAR(100) DEFAULT NULL,
          header_image VARCHAR(100) DEFAULT NULL,
          event_identifier VARCHAR(45) DEFAULT NULL,
          more_info VARCHAR(100) DEFAULT NULL,
          start_month VARCHAR (15) DEFAULT NULL,
          start_day VARCHAR (15) DEFAULT NULL,
          start_year VARCHAR (15) DEFAULT NULL,
          start_time VARCHAR (15) DEFAULT NULL,
          start_date VARCHAR (15) DEFAULT NULL,
          end_month VARCHAR (15) DEFAULT NULL,
          end_day VARCHAR (15) DEFAULT NULL,
          end_year VARCHAR (15) DEFAULT NULL,
          end_date VARCHAR (15) DEFAULT NULL,
          end_time VARCHAR (15) DEFAULT NULL,
          reg_limit VARCHAR (15) DEFAULT NULL,
          custom_cur VARCHAR(10) DEFAULT NULL,
          reg_form_defaults VARCHAR(200) DEFAULT NULL,
          allow_checks VARCHAR(45) DEFAULT NULL,
          send_mail VARCHAR (2) DEFAULT NULL,
          send_contact VARCHAR (2) DEFAULT NULL,
          contact_email VARCHAR(65) DEFAULT NULL,
          contact_msg TEXT DEFAULT NULL,
          is_active VARCHAR(45) DEFAULT NULL,
          conf_mail TEXT DEFAULT NULL,
          use_coupon VARCHAR(2) DEFAULT NULL,
          coupon_code VARCHAR(50) DEFAULT NULL,
          coupon_code_price decimal(7,2) DEFAULT NULL,
          category_id TEXT DEFAULT NULL,
          send_coord VARCHAR(2) DEFAULT NULL,
          coord_email VARCHAR(65) DEFAULT NULL,
          coord_msg TEXT DEFAULT NULL,
          coord_pay_msg TEXT DEFAULT NULL,
          close VARCHAR (65) DEFAULT NULL,
          waiver VARCHAR(2) DEFAULT NULL,
          waiver_content TEXT DEFAULT NULL,
          UNIQUE KEY id (id)
          ) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        if (dbDelta($sql)){
        //create option for table name
        $option_name = 'evr_event';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option for table version
        $option_name = 'evr_event_version';
        $newvalue = $evr_event_version;
        update_option($option_name, $newvalue);
        $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
}
function evr_cost_db()
{
    //Define global variables
    global $wpdb, $cur_build, $table_message;
    global $evr_cost_version;
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_cost";
    $evr_cost_version = $cur_build;
    $sql = "CREATE TABLE " . $table_name . " (
			id MEDIUMINT NOT NULL auto_increment,
			sequence int(11) NOT NULL default '0',
			event_id int(11) NOT NULL default '0',
            item_title VARCHAR(75) DEFAULT NULL,
            item_description VARCHAR(150) DEFAULT NULL,
            item_cat VARCHAR (10) DEFAULT NULL,
            item_limit VARCHAR (10) DEFAULT NULL,
            item_price decimal(7,2) DEFAULT NULL,
            free_item VARCHAR (4) DEFAULT NULL,
            item_available_start_date VARCHAR (15) DEFAULT NULL,
            item_available_end_date VARCHAR (15) DEFAULT NULL,
            item_custom_cur VARCHAR(10) DEFAULT NULL,
            UNIQUE KEY id (id)
			) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        if (dbDelta($sql)){ 
        //create option in the wordpress options tale for the event question table name
        $option_name = 'evr_cost';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event question table version
        $option_name = 'evr_cost_version';
        $newvalue = $evr_cost_version;
        update_option($option_name, $newvalue);
        $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
}
function evr_payment_db()
{
    //Define global variables
    global $wpdb, $cur_build, $table_message;
    global $evr_payment_version;
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_payment";
    $evr_payment_version = $cur_build;
    
    $sql = "CREATE TABLE " . $table_name . " (
				  id MEDIUMINT NOT NULL AUTO_INCREMENT,
				  payer_id varchar(15) NOT NULL,
                  event_id varchar (15) NOT NULL,
				  payment_date varchar(30) DEFAULT NULL,
				  txn_id varchar(20) NOT NULL,
				  first_name varchar(50) NOT NULL,
				  last_name varchar(50) NOT NULL,
				  payer_email varchar(100) NOT NULL,
				  payer_status varchar(10) NOT NULL,
				  payment_type varchar(20) NOT NULL,
				  memo text NOT NULL,
                  item_name text NOT NULL,
				  item_number varchar(50) NOT NULL,
				  quantity int(3) NOT NULL,
				  mc_gross decimal(10,2) NOT NULL,
				  mc_currency varchar(3) NOT NULL,
				  address_name varchar(32) DEFAULT NULL,
				  address_street varchar(64) DEFAULT NULL,
				  address_city varchar(32) DEFAULT NULL,
				  address_state varchar(32) DEFAULT NULL,
				  address_zip varchar(10) DEFAULT NULL,
				  address_country varchar(64) DEFAULT NULL,
				  address_status varchar(11) DEFAULT NULL,
				  payer_business_name varchar(64) DEFAULT NULL,
				  payment_status varchar(17) NOT NULL,
				  pending_reason varchar(14) DEFAULT NULL,
				  reason_code varchar(15) DEFAULT NULL,
				  txn_type varchar(20) NOT NULL,
				  UNIQUE KEY id (id)
				) DEFAULT CHARSET=utf8;";
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        if (dbDelta($sql)){
        //create option in the wordpress options tale for the event payment transaction table name
        $option_name = 'evr_payment';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event payment transaction table version
        $option_name = 'evr_payment_version';
        $newvalue = $evr_payment_version;
        update_option($option_name, $newvalue);
        $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
        //get column names
    $sql = "SELECT * FROM ".$table_name;
    $wpdb->query($sql);
           $fields = $wpdb->get_col_info('name', -1);
    //change column names from values to keys for identifcation 
    $field_names = array_flip($fields);
    $value = "memo_old";
    $sql = "ALTER TABLE ".$table_name." DROP COLUMN memo_old";
    if (array_key_exists($value, $field_names)) {            
             $wpdb->query($sql);
            }
}
function evr_question_db(){
    //Define global variables
    global $wpdb, $cur_build, $table_message;
    global $evr_question_version;
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    //Create new variables for this function
    $table_name = $wpdb->prefix . "evr_question";
    $evr_question_version = $cur_build;

    $sql = "id mediumint(9) NOT NULL AUTO_INCREMENT,
    event_id int(11) NOT NULL DEFAULT '0',
    sequence int(11) NOT NULL DEFAULT '0',
    question_type enum('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN') NOT NULL DEFAULT 'TEXT',
    question text NOT NULL,
    response text NOT NULL,
    required enum('Y','N') NOT NULL DEFAULT 'N',
    remark text NOT NULL,
    UNIQUE KEY id (id)";
  evr_installer( "evr_question", $table_version = '', $sql );
    
    /*if (dbDelta($sql)){
            //create option in the wordpress options tale for the event question table name
        $option_name = 'evr_question';
        $newvalue = $table_name;
        update_option($option_name, $newvalue);
        //create option in the wordpress options table for the event question table version
        $option_name = 'evr_question_version';
        $newvalue = $evr_question_version;
        update_option($option_name, $newvalue);
        $table_message .= __('Success Updating table - ','').$table_name.'<br/>'; 
        }
        else {
        $table_message .= __('Failure Updating table - ','').$table_name.'<br/>'; 
        }
        */
}
//
//Create the table for the answers for the questions
function evr_answer_db()
{
//Define global variables
    global $wpdb, $cur_build, $table_message;
    global $evr_answer_version;
    $wp_table_name = $wpdb->prefix . "evr_answer";
    $sql = "ALTER TABLE ".$wp_table_name." DROP PRIMARY KEY;";
    $wpdb->query ($sql);
    $sql = "ALTER TABLE ".$wp_table_name." ADD PRIMARY KEY(registration_id,question_id);";
    $sql = "registration_id int(11) NOT NULL default '0',
			question_id int(11) NOT NULL default '0',
			answer text NOT NULL,
            PRIMARY KEY (registration_id,question_id)";
        
    evr_installer( "evr_answer", $table_version = '', $sql );

    

}
function evr_generator()
{
    global $evr_date_format, $evr_ver, $wpdb;
    $guid=md5(uniqid(mt_rand(), true));
    $option_name = 'plug-evr-activate';
    $newvalue = $guid;
    update_option($option_name, $newvalue);
    $installed_date = strtotime('now');
    update_option('evr_date_installed', $installed_date);
}
function evr_config_company(){
        //Define global variables
    global $wpdb;
    //Create new variables for this function
    $config_table = $wpdb->prefix . "evr_options";
        
    $sql = "CREATE TABLE IF NOT EXISTS ".$config_table." (
	  `evr_option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `evr_option_name` varchar(64) NOT NULL DEFAULT '',
      `evr_option_value` longtext NOT NULL,
      PRIMARY KEY (`evr_option_id`),
      UNIQUE KEY `evr_option_name` (`evr_option_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

	dbDelta($sql);
}

//This function installs the tables
function evr_installer( $table_name, $table_version = '', $sql ) {

	global $wpdb,$cur_build, $table_message;
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
    $wp_table_name = $wpdb->prefix . $table_name;

	$sql_create_table = "CREATE TABLE $wp_table_name ( $sql ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	dbDelta($sql_create_table);
    update_option($table_name, $wp_table_name);
    update_option($table_name.'_version', $cur_build);
}