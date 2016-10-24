<?php
function evr_clean_old_db(){
    if ( isset( $_POST['purge'], $_POST['purge_confirm'] ) ) {evr_delete_old_tables();}
    elseif (get_option('evr_was_upgraded')== "Y"){
        //echo '<link rel="stylesheet" type="text/css" media="all" href="' . EVR_PLUGINFULLURL . 'evr_admin_style.css' . '" />';
        ?>
        <div class="wrap"><br />
        <a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a>
        <br />
        <br />
        <div class="evr_plugin">
            <div class="content">
            	<div class="evr_content_third">
            		<h3>Purge Previous Version Data</h3>
            		<div class="inside">
                        <form method="POST"  action="admin.php?page=purge">
                      <div id="message" class="error"><p><strong><?php _e('You must check the confirm box before continuing!','evr_language');?></strong></p></div>    
                     <p>The options and data for a previous version of this plugin were not removed during the upgrade.</p> 
                     <p>If you have verified the data migration and you wish to remove all the previous data tables (new tables were created during the installation/upgrade.</p>
                     <p><font color="red">NOTE: There is no way to recover the old data once you complete this process.</font></p>
                      <p><input name="purge_confirm" type="checkbox" value="1" />Yes, I want to remove the previous version Event Registration Data. Please confirm before proceeding </p>
                      <input class="button-primary" name="purge" type="submit" value="PURGE" onclick="return confirm('<?php _e('Are you sure you want to purge the old data from a previous version?','evr_language');?>')"/>
                     </form>
                     </div>
                </div>  		
            </div>
           </div>  		
        </div>        
        <?php
        }
}

function evr_remove_db_menu(){
       // echo '<link rel="stylesheet" type="text/css" media="all" href="' . EVR_PLUGINFULLURL . 'evr_admin_style.css' . '" />';
    if ( isset( $_POST['uninstall'], $_POST['uninstall_confirm'] ) ) {evr_uninstall();}
    ?>
        <div class="wrap"><br />
        <a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a>
        <br />
        <br />
        <div class="evr_plugin">
            <div class="content">
            	<div class="evr_content_third">
            		<h3>Permanently Remove All Data</h3>
            		<div class="inside">
                        <form method="post">
                        <input id="plugin" name="plugin" type="hidden" value="EVENTREG.php" />     
                     <?php if ( isset( $_POST['uninstall'] ) && ! isset( $_POST['uninstall_confirm'] ) ) { ?>
                        <div id="message" class="error"><p><strong><?php _e('You must check the confirm box before continuing!','evr_language');?>
                        </strong></p></div>
                        <?php  } ?>
                     <p>The options and data for this plugin are not removed on deactivation to ensure that no data is lost unintentionally.</p> 
                     <p>If you wish to remove all Event Registration plugin information from your database, be sure to run this uninstall utility first.</p>
                     <p><font color="red">NOTE: There is no way to recover data once you complete this process.</font></p>
                      <p><input name="uninstall_confirm" type="checkbox" value="1" />Yes, I want to remove all Event Registration Data. Please confirm before proceeding </p>
                      <input class="button-primary" name="uninstall" type="submit" value="Uninstall" onclick="return confirm('<?php _e('Are you sure you want to delete all Event Registratin data','evr_language');?>')"/>
                     </form>
                     </div>
                </div>  		
            </div>
           </div>  		
        </div>        
        <?php 
}

function evr_uninstall(){
   
    global $wpdb;
    //Drop Attendee Table
    $thetable = $wpdb->prefix . "evr_attendee";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_attendee');
    delete_option('evr_attendee_version');

    //Drop Events Detail Table
    $thetable = $wpdb->prefix . "evr_event";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_event');
    delete_option('evr_event_version');

    //Drop Events Question Table
    $thetable = $wpdb->prefix . "evr_question";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_question');
    delete_option('evr_question_version');

    //Drop Events Answer Table
    $thetable = $wpdb->prefix . "evr_answer";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_answer');
    delete_option('evr_answer_version');

    //Drop Events Category Table
    $thetable = $wpdb->prefix . "evr_category";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_category');
    delete_option('evr_category_version');

    //Drop Events Cost Table
    $thetable = $wpdb->prefix . "evr_cost";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_cost');
    delete_option('evr_cost_version');

    //Drop Attendee Payment Table
    $thetable = $wpdb->prefix . "evr_payment";
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
    delete_option('evr_payment');
    delete_option('evr_payment_version');
    
    //Remove Company Settings
    
    delete_option('evr_company_settings');
    delete_option('evr_was_upgraded');

    $current = get_option('active_plugins');    
    array_splice($current, array_search( $_POST['plugin'], $current), 1 ); // Array-function!    
    update_option('active_plugins', $current); 
    ?>
<div id="message" class="error"><p><strong><?php _e('Now deleting data tables and options for Event Registration','evr_language');?></strong></p>
            </div>
   
    <div id="message" class="error"><p><strong><?php _e('All Event Registration Data Tables and Options have been deleted!','evr_language');?>
                </strong></p></div> 
                <meta http-equiv="Refresh" content="1; url=plugins.php?deactivate=true">
                
                <?php 
                exit();
}


function evr_delete_old_tables(){
    
global $wpdb;  
?>
<div id="message" class="error"><p><strong><?php _e('Now deleting old data tables and options from previous version of Event Registration','evr_language');?></strong></p></div>
<?php

//Drop Organization Table
$thetable = $wpdb->prefix."events_organization";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_organization_tbl');
delete_option('events_organization_tbl_version');  

//Drop Events Detail Table
$thetable = $wpdb->prefix."events_detail";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_detail_tbl');
delete_option('events_detail_tbl_version'); 

//Drop Events Attendee Table
$thetable = $wpdb->prefix."events_attendee";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_attendee_tbl');
delete_option('events_attendee_tbl_version'); 

//Drop Events Category Table
$thetable = $wpdb->prefix."events_cat_detail_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_cat_detail_tbl');
delete_option('events_cat_detail_tbl_version'); 

//Drop Events Payment Table
$thetable = $wpdb->prefix."events_payment_transactions";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_payment_transactions_tbl');
delete_option('events_payment_transactions_tbl_version'); 

//Drop Events Question Table
$thetable = $wpdb->prefix."events_question_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_question_tbl');
delete_option('events_question_tbl_version'); 

//Drop Events Answer Table
$thetable = $wpdb->prefix."events_answer_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_answer_tbl');
delete_option('events_answer_tbl_version'); 


//Remove other Events Registration Options
delete_option('all_events_sample_page_id'); 
delete_option('er_link_for_calendar_url'); 
delete_option('single_event_sample_page_id'); 
delete_option('category_sample_page_id'); 
delete_option('current_event'); 
delete_option('payment_vendor_id'); 
delete_option('show_thumb'); 
delete_option('er_link_for_calendar_url'); 
delete_option('currency_format');
delete_option('events_listing_type');
delete_option('return_url');
delete_option('cancel_return');
delete_option('notify_url');
delete_option('return_method');
delete_option('use_sandbox');
delete_option('image_url');
delete_option('registrar');
delete_option('currency_format');
delete_option('attendee_first');
delete_option('attendee_last');
delete_option('attendee_name');
delete_option('attendee_email');
delete_option('attendee_id');

delete_option('evr_was_upgraded');
?>
<div id="message" class="error"><p><strong><?php _e('All previous version Event Registration Data Tables and Options have been deleted!','evr_language');?></strong></p></div> 
<?php
    
}