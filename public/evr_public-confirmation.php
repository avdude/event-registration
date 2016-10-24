<?php

function evr_confirm_form(){

    $_SESSION['token'] = md5(session_id() . time());
    global $wpdb, $qanda, $posted_data, $company_options;
    //$company_options = get_option('evr_company_settings');
    $num_people = 0;
    $item_order = array();
        
    $passed_event_id = $_POST['event_id'];
    if (is_numeric($passed_event_id)){$event_id = $passed_event_id;}
    else {
        _e('Failure - please retry!','evr_language'); 
        exit;
        }
    
    //Begin gather registrtion data for database input
    if (isset($_POST['fname'])){ $fname = $_POST['fname'];}
    if (isset($_POST['lname'])){$lname = $_POST['lname'];} 
    if (isset($_POST['email'])){$email = $_POST['email'];} 
    if (isset($_POST['address'])){$address = $_POST['address'];} else { $address = '';}
    if (isset($_POST['city'])){$city = $_POST['city'];} else {$city = '';}
    if (isset($_POST['state'])){$state = $_POST['state'];} else { $state = '';}
    if (isset($_POST['zip'])){$zip = $_POST['zip'];} else { $zip = '';}
    if (isset($_POST['phone'])){$phone = $_POST['phone'];} else {$phone = '';}
     
    if (isset($_POST['fees'])){$fees = $_POST['fees'];} else { $fees = '';}
    if (isset($_POST['tax'])){$tax = $_POST['tax'];} else { $tax = '';}
    if (isset($_POST['total'])){$payment = $_POST['total'];} else {$payment = '';}
    if (isset($_POST['coupon'])){$coupon = $_POST['coupon'];} else { $coupon = '';}
    if (isset($_POST['reg_type'])){$reg_type = $_POST['reg_type'];} else { $reg_type = '';}
    if (isset($_POST['company'])){$company = $_POST['company'];} else { $company  = '';}
    if (isset($_POST['co_address'])){$coadd = $_POST['co_address'];} else {$coadd = '';}
    if (isset($_POST['co_city'])){$cocity = $_POST['co_city'];} else {$cocity  = '';}
    if (isset($_POST['co_state'])){ $costate = $_POST['co_state'];} else { $costate = '';}
    if (isset($_POST['co_zip'])){$cozip = $_POST['co_zip'];} else { $cozip = '';}
    if (isset($_POST['co_phone'])){$cophone = $_POST['co_phone'];} else { $cophone = '';}
    if (isset($_POST['waiver_agree'])){$waiver_agree = $_POST['waiver_agree'];} else { $waiver_agree = '';}
    
    
    $attendee_name = $fname." ".$lname;
    
    //echo "Registration Type is: ".$reg_type."!";
    
    $items = $wpdb->get_results( "SELECT * FROM ". get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC" );
                                                if ($items){
                                                    foreach ($items as $item){
                                                        
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
                                                        if ($item_custom_cur == "GBP"){$item_display_cur = "&pound;";}
                                                        if ($item_custom_cur == "USD"){$item_display_cur = "$";}
                                            
   
    
                             
            $item_post = str_replace(".", "_", $item->item_price);
            $item_qty = $_REQUEST['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];
            
            if ($item_cat == "REG"){$num_people = $num_people + $item_qty;}
            
            $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat'=>$item_cat,
                'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
                $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
                'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
            array_push($item_order, $item_info);
            
            }}
    if ($reg_type == "WAIT"){$quantity = "1";}
    else {$quantity = $num_people;}
    
    $ticket_data = serialize($item_order);

                  
                
     $reg_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
     $qanda=array();
            $questions = $wpdb->get_results("SELECT * from ".get_option('evr_question')." where event_id = '$event_id'");
            if ($questions) {
                  foreach ($questions as $question) {
                    switch ($question->question_type) {
                        case "TEXT":
                        case "TEXTAREA":
                        case "DROPDOWN":
                            $post_val = $_POST[$question->question_type . '_' . $question->id];
                            
                            $custom_response = array( 'email'=>$email, 'question' => $question->id, 'response'=>$post_val);
                            array_push($qanda,$custom_response);
                            break;
                        case "SINGLE":
                            $post_val = $_POST[$question->question_type . '_' . $question->id];
                            $custom_response = array( 'email'=>$email, 'question' => $question->id, 'response'=>$post_val);
                            array_push($qanda,$custom_response);
                            break;
                        case "MULTIPLE":
                            $value_string = '';
                            for ($i = 0; $i < count($_POST[$question->question_type . '_' . $question->id]);
                                $i++) {
                                $value_string .= $_POST[$question->question_type . '_' . $question->id][$i] .",";
                            }
                            $custom_response = array( 'email'=>$email, 'question' => $question->id, 'response'=>$value_string);
                            array_push($qanda,$custom_response);
                            break;
                        }
                    }
                }    
                
                
     
    $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id = $event_id";
    $event = $wpdb->get_row($sql);               

        $event_id           = $event->id;
		$event_name         = stripslashes($event->event_name);
		$event_location     = stripslashes($event->event_location);
        $event_address      = $event->event_address;
        $event_city         = $event->event_city;
        $event_postal       = $event->event_postal;
        $reg_limit          = $event->reg_limit;
		$start_time         = $event->start_time;
		$end_time           = $event->end_time;
		$start_date         = $event->start_date;
		$end_date           = $event->end_date;
        $use_coupon         = $event->use_coupon;
        $coupon_code        = $event->coupon_code;
        $coupon_code_price  = $event->coupon_code_price;
                            
                           

// GT Validate coupon code and deduct discount	
if ($use_coupon == "Y"){
    if ($coupon == $coupon_code) { $payment = ($payment + $coupon_code_price);}
}
# GT Added event_name to the array
$posted_data =array('lname'=>$lname, 'fname'=>$fname, 'address'=>$address, 'city'=>$city, 
                'state'=>$state, 'zip'=>$zip, 'reg_type'=>$reg_type, 'email'=>$email,
                'phone'=>$phone, 'email'=>$email, 'coupon'=>$coupon, 'event_id'=>$event_id,'event_name'=>$event_name,
                'company'=>$company, 'co_add'=>$coadd, 'co_city'=>$cocity, 'co_state'=>$costate, 'co_zip'=>$cozip,
                'num_people'=>$quantity, 'tickets'=>$ticket_data, 'payment'=>$payment, 'fees'=>$fees, 'tax'=>$tax,'waiver_agree'=>$waiver_agree);
       

#Begin display of confirmation form
$item_custom_cur  = $item_order[0]['ItemCurrency'];
    if ($item_custom_cur == "GBP"){$item_display_cur = "&pound;";}
    elseif ($item_custom_cur == "USD"){$item_display_cur = "$";}
    else {$item_display_cur = $item_custom_cur;}
echo '<script type="text/javascript" src="'.EVR_PLUGINFULLURL.'public/validate.js.php"></script>';
echo '<p align="left"><strong>'.__('Please verify your registration details:','evr_language').'</strong></p>';
echo '<table width="95%" border="0"><tr><td><strong>'.__('Event Name/Cost:','evr_language').'</strong></td><td>';
echo $event_name.' - '.$item_display_cur.'&nbsp;'.$payment.'</td></tr><tr><td><strong>';
_e('Registering Name:','evr_language');
echo '</strong></td><td>'.$attendee_name.'</td></tr><tr><td><strong>'.__('Email Address:','evr_language').'</strong></td><td>';
echo $email.'</td></tr><tr><td><strong>'.__('Number of Attendees:','evr_language');
echo '</strong></td><td>'.$quantity.'</td></tr><tr><td><strong>'.__('Order Details:','evr_language').'</strong></td><td>';
#Registration Type
if ($reg_type == "WAIT"){echo "WAIT LIST";}
else {
    $row_count = count($item_order);
    for ($row = 0; $row < $row_count; $row++) {
    if ($item_order[$row]['ItemQty'] >= "1"){ 
        
        echo $item_order[$row]['ItemQty']." ".$item_order[$row]['ItemCat']."-".$item_order[$row]['ItemName']." ".$item_display_cur . '  ' . $item_order[$row]['ItemCost']."<br \>";}
    } }
echo '</td></tr><tr>';    
if ($use_coupon == "Y"){
    if($coupon == $coupon_code) {
        echo '<td><strong>'.__('Coupon:','evr_language').'</strong></td><td>'.$coupon_code_price.'</td>';
        }
    elseif ($coupon != $coupon_code) {
        echo '<td><strong>'.__('Coupon:','evr_language').'</strong></td><td>'.__('Invalid Code!','evr_language').'</td>';
        }
}
echo '</tr>';
if ($company_options['use_sales_tax'] == "Y"){ 
    echo '<tr><td></td><td>';
    _e('Sales Tax:','evr_language'); 
    echo '  '.$tax.'</td></tr>';
}
                                                        
echo '<tr><td colspan="2"></td></tr><tr><td><strong>'.__('Event Name / Total Cost:','evr_language').'</strong></td><td>';
echo $event_name.': '.$item_display_cur.'<strong>  '.number_format($payment,2).'</strong></td></tr></table>';
echo '<p align="left"><strong>';
if ($reg_type == "WAIT"){
    $type = __('You are on the waiting list.','evr_language');
}
if ($reg_type == "RGLR"){
    $type = __('You are registering for','evr_language')." ".$quantity." ".__('person(s).','evr_language')."   ".__('Please provide the first and last name of each person:','evr_language');
    }
echo $type;
echo '</strong><br />';
echo '<form id="attendee_confirm" class="evr_regform" method="post" action="';
echo evr_permalink($company_options['evr_page_id']);
echo '" onSubmit="mySubmit.disabled=true;return validateConfirmationForm(this)"><p>';
if ( $quantity >"0"){
    echo '<div style="width:95%;">';
    $i = 0;
    do {
        $person = $i + 1;
        echo __('Attendee','evr_language').' #'.$person.'<br/>&nbsp;&nbsp;&nbsp;'.__('First Name','evr_language').
        ': <input name="attendee['.$i.'][first_name]"';
        if ($i == 0){ echo 'value ="'.$fname.'"';}
        echo '/>';
        echo '<br/>&nbsp;&nbsp;&nbsp;'.__('Last Name','evr_language').': <input name="attendee['.$i.'][last_name]"';
        if ($i == 0){ echo 'value ="'.$lname.'"';}
        echo '/></br>';
        
     ++$i;
     } 
     while ($i < $quantity);
     echo '</div>';
 }
 $form_post = urlencode(serialize($posted_data));
 $question_post = urlencode(serialize($qanda));
echo '<br /><div style="float:left;"><input type="button" value=" &lt;-- '.__('BACK','evr_language').'" onclick="history.go(-1);return false;" /></div>';
$count = (int)$quantity;
if ( $count <= 0){
    echo '<br/><font color="red"><b>';
    _e('You must select at least one registration item.','evr_language').'<br />';
    _e('Please go back and select an item!','evr_language');  
    echo '</b></font>';
    } 
else {
    echo '<input type="hidden" name="reg_form" value="'.$form_post.'" />';
    echo '<input type="hidden" name="questions" value="'.$question_post.'" />';
    echo '<input type="hidden" name="action" value="post"/>';
    echo '<input type="hidden" name="token" value="'.$_SESSION['token'].'" />';
    echo '<input type="hidden" name="event_id" value="'.$event_id.'" />';
    echo '<div style="margin-left: 150px;">';
    echo '<input type="submit" name="mySubmit" id="mySubmit" value="'.__('Confirmed','evr_language').'" /></div>';
    } 
echo '</form>';

}
?>