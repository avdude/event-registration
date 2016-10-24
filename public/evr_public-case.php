<?php
function evr_registration_main(){
    
    global $wpdb,$company_options;
    //$company_options = get_option('evr_company_settings');
    if (!isset($_REQUEST['action'])) {$action = '';} else { $action = $_REQUEST['action'];}
    if (!isset($_REQUEST['event_id'])) {$event_id = '';} 
    else {  
        if (is_numeric($_REQUEST['event_id'])){
        $event_id = (int)$_REQUEST['event_id'];
    }}
    switch ($action) {
            
            case "evregister":
            if (is_numeric($event_id)){
              evr_regform_new($event_id);}
			  else {evr_show_event_list();}
            break;
            
            case "confirm":
                evr_confirm_form();
            break;
            
            case "post":
               evr_process_confirmation();
            break;
            
            case "show_confirm_mess":
               evr_show_confirmation();  
            break;
            
            
            case "pay":
                evr_retun_to_pay();
            break;
            
            case "key":
                echo "</br>";
                echo get_option('siteurl')." - ".get_option('plug-evr-activate');
                echo "</br>";
                echo get_option('siteurl')." -coordmodule- ".get_option('plug-evr_coord-activate');
                
            break;
            
            case "paypal_txn":
               if ($company_options['payment_vendor']=="PAYPAL"){          
                evr_paypal_txn();
                }
                else {
                    _e('IPN is only avialble with PAYPAL with this version of Event Reigstration','evr_language');
                }
            break;
            case "authorize_txn":
               if ($company_options['payment_vendor']=="AUTHORIZE"){          
                evr_authorize_txn();
                }
                else {
                    _e('IPN is currently not available.','evr_language');
                }
            break;
            case "payfast_itn":
                wp_mail('dfleming@microsoft.com'.'IPN Test Notice',"It works - 1!");
                evr_payfast_itn();
                wp_mail('dfleming@microsoft.com'.'IPN Test Notice',"It works - 2!");
            break;
            
            default:
$public_list_template = '';
                if ($company_options['evr_list_format']=="accordian"){evr_show_event_accordian();}
               // else if ($company_options['evr_list_format']=="link"){evr_show_event_links();}
                else {evr_show_event_list($public_list_template);}
                
               
           }
}

?>