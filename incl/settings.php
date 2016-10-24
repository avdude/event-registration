<?php
function evr_issetor(&$variable, $or = NULL) {
     return $variable === NULL ? $or : $variable;
 }
function evr_createCompanyArray(){
    //get all rows from the options table
    //iterate through the rows putting them in the array
    global $wpdb, $company_options;
    $config_table = $wpdb->prefix . "evr_options";
    $options = $wpdb->get_results( "SELECT * FROM ". $config_table );
    $company_options = array();
    //global $company_options;
    foreach ( $options as $option ) 
    {
    	$name =  $option->evr_option_name;
        $value = $option->evr_option_value;
        $company_options[$name] = $value;
    }
    //return $company_options;
}
function add_evr_option($option,$value){
    global $wpdb;
    $config_table = $wpdb->prefix . "evr_options";
	$option = trim($option);
	if ( empty($option) )
		return false;
	if ( is_object($value) )
		$value = clone $value;
	//$value = sanitize_option( $option, $value );
	// Make sure the option doesn't already exist. We can check the 'notoptions' cache before we ask for a db query
	$notoptions = wp_cache_get( 'notoptions', 'options' );
	if ( !is_array( $notoptions ) || !isset( $notoptions[$option] ) )
		if ( false !== get_option( $option ) )
			return false;
	$serialized_value = maybe_serialize( $value );
	do_action( 'add_option', $option, $value );
	$result = $wpdb->query( $wpdb->prepare( "INSERT INTO `$config_table` (`evr_option_name`, `evr_option_value`) VALUES (%s, %s) ON DUPLICATE KEY UPDATE `evr_option_name` = VALUES(`evr_option_name`), `evr_option_value` = VALUES(`evr_option_value`)", $option, $serialized_value) );
	if ( ! $result )
		return false;
	return true;
}
function evr_adminGeneral(){
    global $wpdb, $company_options;
    //print_r($company_options);
    ?>
                    <!--Company Contact Info -->
                    <div class="padding">
                       <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><label for="company"><?php _e('Company Name:','event-registration');?></label></th>
                        <td><input name="company_name" type="text" value="<?php echo stripslashes($company_options['company_name']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_street1"><?php _e('Company Street 1:','event-registration');?></label></th>
                        <td><input name="company_street1" type="text"  value="<?php echo stripslashes($company_options['company_street1']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_street2"><?php _e('Company Street 2:','event-registration');?></label></th>
                        <td><input name="company_street2" type="text" size="45" value="<?php echo stripslashes($company_options['company_street2']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_city"><?php _e('Company City:','event-registration');?></label></th>
                        <td><input name="company_city" type="text" size="45" value="<?php echo stripslashes($company_options['company_city']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_state"><?php _e('Company State:','event-registration');?></label></th>
                        <td><input name="company_state" type="text" size="3" value="<?php echo $company_options['company_state'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_zip"><?php _e('Company Postal Code:','event-registration');?></label></th>
                        <td><input name="company_postal" type="text" size="10" value="<?php echo $company_options['company_postal'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="contact"><?php _e('Primary contact email:','event-registration');?></label></th>
                        <td><input name="company_email" type="text" size="45" value="<?php echo $company_options['company_email'];?>" class="regular-text" /></td>
                        </tr>
                        </table>  
                      </div>
                      <!--End Company Contact--> 
                      <hr /> 
                    <!--End Event List Type-->
                    <hr />
                    <!--Registration Page URL Settings-->
                    <div class="padding">
                         <?php if(  evr_issetor($_POST['evr_page_id'])|| $company_options['evr_page_id']=='0' )
                         {
                          ?>
                        <p class="updated fade red_text" align="center"><strong><span>**<?php _e('Attention','event-registration');?>**</strong><br />
                        <?php _e('These settings must be configured for the plugin to function correctly.','event-registration');?></span>.</p>
                        <?php }?>	    
                            <p><?php _e('Main registration page','event-registration');?>:"
                            <select name="evr_page_id">
                            <option value="0">
                            <?php _e ('Main page','event-registration'); ?>
                            </option>
                            <?php parent_dropdown ($default=$company_options['evr_page_id']); ?>
                            </select>
                            <a class="ev_reg-fancylink" href="#registration_page_info">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a><br />
                            <font  size="-2">
                            <?php _e('(This page should contain the ','event-registration')._e(' <strong>[EVR_EVENTS]</strong> ','')._e('shortcode. This page should be public and can be hidden from navigation, if desired.)','event-registration');?></font></p>
                            </div>
                         <!--Paylment Page URL Settings-->   
                          <div class="padding"> 
                            <p><?php _e('Return URL for Payments','event-registration');?>:
                            <select name="return_url">
                            <option value="0"><?php _e ('Main page','event-registration'); ?></option>
                            <?php parent_dropdown ($default=$company_options['return_url']); ?>
                            </select>
                            <a class="ev_reg-fancylink" href="#payment_page_info"><img src="<?php echo EVR_PLUGINFULLURL?>/images/question-frame.png" width="16" height="16" /></a><br />
                            <font  size="-2"><?php _e('(This page will contain the ','event-registration')._e(' <strong>EVR_PAYMENT</strong> ','')._e('payment shortcode. This page should public and hidden from navigation.)','event-registration');?></font></p>
                    </div>
                    <hr />
                    <hr />
                       <!--Event List Type-->
                     <br />
                     <div class="padding">
                        <label for="captcha"><?php _e('Select how the events will be listed on Event Listing Page','event-registration');?>&nbsp;&nbsp;</label>
                        <input name="evr_list_format" type="radio" value="popup" class="regular-radio" <?php if ($company_options['evr_list_format']=="popup"){echo "checked";}?> />&nbsp;<?php _e('PopUp Window','event-registration');?>&nbsp;&nbsp;
                        <input name="evr_list_format" type="radio" value="accordian" class="regular-radio" <?php if ($company_options['evr_list_format']=="accordian"){echo "checked";}?> />&nbsp;<?php _e('Accordian List','event-registration');?>&nbsp;&nbsp;
                        <input name="evr_list_format" type="radio" value="link" class="regular-radio" <?php if ($company_options['evr_list_format']=="link"){echo "checked";}?> />&nbsp;<?php _e('Link Only','event-registration');?>&nbsp;&nbsp;
                    </div>
                     <!--End Page URL Settings-->
                     <br />
                     <!--Use Captcha?-->
                     <div class="padding">
                      <label for="captcha"><?php _e('Use Captcha on registration form?','event-registration');?>&nbsp;&nbsp;</label>
                        <input name="captcha" type="radio" value="Y" class="regular-radio" <?php if ($company_options['captcha']=="Y"){echo "checked";}?> />&nbsp;<?php _e('Yes','event-registration');?>&nbsp;&nbsp;
                        <input name="captcha" type="radio" value="N" class="regular-radio" <?php if ($company_options['captcha']=="N"){echo "checked";}?> />&nbsp;<?php _e('No','event-registration');?>
                     </div>
                     <br />
                     <!--End Use Captcha?-->
                     <div class="padding">
                    <label for="form_css"><?php _e('CSS Overrides for registration form?','event-registration');?></label><a class="ev_reg-fancylink" href="#css_override_help">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a><br />
                    <br /><textarea name="form_css" id="form_css" style="width: 600px; height: 200px;">
                        <?php echo $company_options['form_css'];?></textarea>
                    </div> 
    <?php
}
function evr_adminCalendar(){
    global $wpdb, $company_options;
    ?>
    <div class="padding">
                   <p><h2><?php _e('Calendar Settings','event-registration');?></h2></p>
                   <br />
                   <p><label><?php _e('Start Day of Week','event-registration');?></label> 
                   <select name="start_day">
                    <option value="0" <?php if ($company_options['start_day'] == 0){ echo "selected";} ?> ><?php _e('Sunday','event-registration');?></option>
                    <option value="1" <?php if ($company_options['start_day'] == 1){ echo "selected";} ?> ><?php _e('Monday','event-registration');?></option>
                   </select></p>
                    <p><?php _e('Do you want to use Category color coding?','event-registration');?>
                        <input type="radio" name="evr_cal_use_cat" class="regular-radio" value="Y"  <?php if ($company_options['evr_cal_use_cat'] == "Y"){echo "checked";}?> /><?php _e('Yes','event-registration');?>
                        <input type="radio" name="evr_cal_use_cat" class="regular-radio" value="N"  <?php if ($company_options['evr_cal_use_cat'] == "N"){echo "checked";}?> /><?php _e('No','event-registration');?><br />  
                        </p>
                        <p><?php _e('Select color for Calendar Display','event-registration');?>:</p>
                                        <script type="text/javascript" charset="utf-8">
                                          jQuery(document).ready(function($){
                                                   $('.color-picker').wpColorPicker();
                                          });
                                        </script>
                                        <small><?php _e('Click on each field to display the color picker. Click again to close it.','event-registration');?></small>
                                        <hr />
                                        <p><?php _e('Do you want to use the Date selector?','event-registration');?>
                        <input type="radio" name="evr_date_select" class="regular-radio" value="Y"  <?php if ($company_options['evr_date_select'] == "Y"){echo "checked";}?> /><?php _e('Yes','event-registration');?>
                        <input type="radio" name="evr_date_select" class="regular-radio" value="N"  <?php if ($company_options['evr_date_select'] == "N"){echo "checked";}?> /><?php _e('No','event-registration');?><br />  
                        </p>
                                        <p><label for="color"><?php _e('Calender Date Selector Background Color','event-registration');?>: 
                                        <input type="text" class="color-picker" id="evr_cal_head" name="evr_cal_head" value="<?php if ($company_options['evr_cal_head'] !="") {echo $company_options['evr_cal_head'];} else {echo "#583c32";}?>"  style="width: 195px"/>
                                        </label><div id="picker" style="margin-bottom: 1em;"></div></p><p><?php _e('Selector Text Color','event-registration');?>: <select style="width:70px;" name='cal_head_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_head_txt_clr'];?>"><?php if ($company_options['cal_head_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_head_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','event-registration');?></option>
                                        <option value="#FFFFFF"><?php _e('White','event-registration');?></option></select></p>
                                        <hr />
                                        
                                        <p><label for="color"><?php _e('Calender Day Header Background Color','event-registration');?>: 
                                        <input type="text" class="color-picker" id="evr_cal_day_head" name="evr_cal_day_head" value="<?php  if ($company_options['evr_cal_day_head'] !=""){
                                        echo $company_options['evr_cal_day_head'];} else {echo "#b8ced6";}?>"  style="width: 195px"/>
                                        </label><div id="hdrpicker" style="margin-bottom: 1em;"></div></p>
                                        <p><?php _e('Selector Text Color','event-registration');?>: <select style="width:70px;" name='cal_day_head_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_day_head_txt_clr'];?>"><?php if ($company_options['cal_day_head_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_day_head_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','event-registration');?></option>
                                        <option value="#FFFFFF"><?php _e('White','event-registration');?></option></select></p>
                                        <hr />
                                        <p><label for="color"><?php _e('Current Day Background Color','event-registration');?>: 
                                        <input type="text" class="color-picker" id="evr_cal_cur_day" name="evr_cal_cur_day" value="<?php if ($company_options['evr_cal_cur_day'] !="") {echo $company_options['evr_cal_cur_day'];} else {echo  "#b8ced6"; }
                                        ?>"  style="width: 195px"/>
                                        </label><div id="daypicker" style="margin-bottom: 1em;"></div></p>
                                        <p><?php _e('Current Day Text Color','event-registration');?>: <select style="width:70px;" name='cal_day_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_day_txt_clr'];?>"><?php if ($company_options['cal_day_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_day_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','event-registration');?></option>
                                        <option value="#FFFFFF"><?php _e('White','event-registration');?></option></select></p>
                                        <hr />
                                        <p><label for="color"><?php _e('Description Pop Border Color','event-registration');?>: 
                                        <input type="text" class="color-picker" id="evr_cal_pop_border" name="evr_cal_pop_border" value="<?php  if ($company_options['evr_cal_pop_border'] !=""){ echo $company_options['evr_cal_pop_border'];} else {echo  "#b8ced6";}?>"  style="width: 195px"/>
                                        </label><div id="brdrpicker" style="margin-bottom: 1em;"></div></p>
                    </div>  
<?php
}
function evr_adminPayments(){
global $wpdb, $company_options;
?>
<div class="padding">
                        <p><label for="checks"><?php _e('Will you accept checks/cash?','event-registration');?></label>
                        <select name = 'checks' class="regular-select">
                            <option value="Yes" <?php if ($company_options['checks'] == "Yes"){ echo "selected";}?>>Yes</option>
                            <option value="No" <?php if ($company_options['checks'] == "No"){ echo "selected";}?>>No</option>
                        </select>
                        </p>
                        <p>
                        <label for="accept_donations"><?php _e('Will you accept donations?','event-registration');?></label>
                        <select name = 'donations' class="regular-select">
                            <option value="Yes" <?php if ($company_options['donations'] == "Yes"){ echo "selected";}?>>Yes</option>
                            <option value="No" <?php if ($company_options['donations'] == "No"){ echo "selected";}?>>No</option>
                        </select>
                        </p>
                       <hr />
                                               <p>
                        <label for="currency_format"><?php _e('Currency Format:','event-registration');?></label>
                        <select name = "default_currency" class="regular-select">
                            <option value="USD" <?php if ($company_options['default_currency'] == "USD"){ echo "selected";}?>>USD</option>
                            <option value="AUD" <?php if ($company_options['default_currency'] == "AUD"){ echo "selected";}?>>AUD</option>
                            <option value="GBP" <?php if ($company_options['default_currency'] == "GBP"){ echo "selected";}?>>GBP</option>
                            <option value="CAD" <?php if ($company_options['default_currency'] == "CAD"){ echo "selected";}?>>CAD</option>
                            <option value="CZK" <?php if ($company_options['default_currency'] == "CZK"){ echo "selected";}?>>CZK</option>
                            <option value="DKK" <?php if ($company_options['default_currency'] == "DKK"){ echo "selected";}?>>DKK</option>
                            <option value="EUR" <?php if ($company_options['default_currency'] == "EUR"){ echo "selected";}?>>EUR</option>
                            <option value="HKD" <?php if ($company_options['default_currency'] == "HKD"){ echo "selected";}?>>HKD</option>
                            <option value="HUF" <?php if ($company_options['default_currency'] == "HUF"){ echo "selected";}?>>HUF</option>
                            <option value="ILS" <?php if ($company_options['default_currency'] == "ILS"){ echo "selected";}?>>ILS</option>
                            <option value="JPY" <?php if ($company_options['default_currency'] == "JPY"){ echo "selected";}?>>JPY</option>
                            <option value="MXN" <?php if ($company_options['default_currency'] == "MXN"){ echo "selected";}?>>MXN</option>
                            <option value="NZD" <?php if ($company_options['default_currency'] == "NZD"){ echo "selected";}?>>NZD</option>
                            <option value="NOK" <?php if ($company_options['default_currency'] == "NOK"){ echo "selected";}?>>NOK</option>
                            <option value="PLN" <?php if ($company_options['default_currency'] == "PLN"){ echo "selected";}?>>PLN</option>
                            <option value="SGD" <?php if ($company_options['default_currency'] == "SGD"){ echo "selected";}?>>SGD</option>
                            <option value="SEK" <?php if ($company_options['default_currency'] == "SEK"){ echo "selected";}?>>SEK</option>
                            <option value="CHF" <?php if ($company_options['default_currency'] == "CHF"){ echo "selected";}?>>CHF</option>
                            <option value="ZAR" <?php if ($company_options['default_currency'] == "ZAR"){ echo "selected";}?>>ZAR</option>
                            </select>
                        </p>
                        <hr />
                       <p><?php _e('Do you want to charge sales tax?','event-registration');?>&nbsp;&nbsp; <input type="radio" name="use_sales_tax" class="regular-radio" value="Y"  <?php if ($company_options['use_sales_tax'] == "Y"){echo "checked";}?> /><?php _e('Yes','event-registration');?>&nbsp;
                        <input type="radio" name="use_sales_tax" class="regular-radio" value="N"  <?php if (($company_options['use_sales_tax'] == "N")||($company_options['use_sales_tax'] != "Y")){echo "checked";}?> /><?php _e('No','event-registration');?><br />  
                        <font size="-5" color="red"><?php _e('(This option must be enable to charge sales tax)','event-registration');?></font></p>
                        <p><label for="sales_tax_rate"><?php _e('Sales Tax Rate: ','event-registration');?><?php _e('(must be decimal, i.e. .085 )','event-registration');?></label>
                        <input name="sales_tax_rate" type="text"  value="<?php echo $company_options['sales_tax_rate'];?>" class="regular-text" />
                        </p>
                        <hr />
                        <p><label for="pay_msg"><?php _e('Payment Message on Confirmation Screen','event-registration');?></label>
                        <input  name="pay_msg" value="<?php  
                        if ($company_options['pay_msg'] != ""){echo stripslashes($company_options['pay_msg']);} else {
                            _e("To pay online, please select the Payment button to be taken to our payment vendor's site.",'event-registration');
                        }
                        ?>"  maxlength="93" size="95"/></p>
                        <p><label for="pay_now"><?php _e('Payment Button Text','event-registration');?></label>
                        <input name="pay_now" value="<?php  if ($company_options['pay_now'] !=""){echo $company_options['pay_now'];} else {_e('PAY NOW');}?>" class="regular-text" /></p>
                        <hr />
                        <h2>Payment Gateway</h2>
                        <p>A Payment Gateway is an online payment processing solution which empowers organizations to accept credit cards and electronic checks.</p> 
                        <label for="payment_vendor"><?php _e('Payment Gateway Provider:','event-registration');?></label>
                        <a class="ev_reg-fancylink" href="#payment_gateway_info"><img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a>
                        <select name="payment_vendor" id="payment_vendor" class="regular-select">
                            <option value="NONE" <?php if ($company_options['payment_vendor'] == "NONE"){ echo "selected";}?> >NONE</option>
                             <option value="AUTHORIZE" <?php if ($company_options['payment_vendor'] == "AUTHORIZE"){ echo "selected";}?>>AUTHORIZE.NET</option>
                            <!-- <option value="GOOGLE" <?php if ($company_options['payment_vendor'] == "GOOGLE"){ echo "selected";}?>>GOOGLE</option> -->
                            <option value="PAYPAL" <?php if ($company_options['payment_vendor'] == "PAYPAL"){ echo "selected";}?>>PAYPAL</option>
                            <option value="PAYFAST" <?php if ($company_options['payment_vendor'] == "PAYFAST"){ echo "selected";}?>>PAYFAST</option>
                            <!-- <option value="TOUCHNET" <?php if ($company_options['payment_vendor'] == "TOUCHNET"){ echo "selected";}?>>TOUCHNET</option> -->
                            <option value="MONSTER" <?php if ($company_options['payment_vendor'] == "MONSTER"){ echo "selected";}?>>MONSTER PAY</option>
                            <option value="CUSTOM" <?php if ($company_options['payment_vendor'] == "CUSTOM"){ echo "selected";}?>>CUSTOM</option>
                        </select>
                        <div id="NONE" class="block">None</div>
                        <div id="AUTHORIZE" class="block">
                            <h2>Authorize.Net Access Settings</h2>
                            <p>In order for this plugin to connect to the Authorize.Net payment gateway, 
                            you will need to provide your API Login ID and Transaction Key. These values authenticate 
                            you as an authorized merchant when submitting transaction requests.</p>
                            <p>
                                <a href="https://ems.authorize.net/oap/home.aspx?SalesRepID=98&ResellerID=16334">
                                <img src="http://www.authorize.net/images/reseller/oap_sign_up.gif" height="38" width="135" border="0"/></a> 
                            </p>
                            <p>
                                <label for="authorize_id"><?php _e('Enter your Authorize.Net API Login ID','event-registration');?></label><a class="ev_reg-fancylink" href="#authorize_api_id">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a>
                                <input name="authorize_id" value="<?php  echo $company_options['authorize_id'];?>" class="regular-text" /></td>
                            <p>
                                <label for="authorize_key"><?php _e('Authorized.Net Txn Key','event-registration');?></label><a class="ev_reg-fancylink" href="#authorize_txn_key">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a>
                                <input name="authorize_key" value="<?php  echo $company_options['authorize_key'];?>" class="regular-text" />
                            </p>
                            <hr />
                            <p><label for="testmode"><?php _e('Authorize.net test mode','event-registration');?><a class="ev_reg-fancylink" href="#testmode_info">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a><br /><font size="-6"><?php _e('(used for testing/debug)','event-registration');?></font></label>
                               <input type="radio" name="use_authorize_testmode" value="Y" <?php  if ($company_options['use_authorize_testmode']=="Y"){echo "checked";}?>/><?php _e('Yes','event-registration');?>
                               <input type="radio" name="use_authorize_testmode" value="N" <?php  if ($company_options['use_authorize_testmode']=="N"){echo "checked";}?>/><?php _e('No','event-registration');?>
                            </p>
                            <hr /><hr />
                            <h3>Development Testing</h3>
                            <p><label for="use_sandbox"><?php _e('Use Authorize.net Sandbox','event-registration');?><a class="ev_reg-fancylink" href="#authorize_sandbox_info">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a><br /><font size="-6"><?php _e('(used for testing/debug)','event-registration');?></font></label>
                               <input type="radio" name="use_authorize_sandbox" value="Y" <?php  if ($company_options['use_authorize_sandbox']=="Y"){echo "checked";}?>/><?php _e('Yes','event-registration');?>
                               <input type="radio" name="use_authorize_sandbox" value="N" <?php  if ($company_options['use_authorize_sandbox']=="N"){echo "checked";}?>/><?php _e('No','event-registration');?>
                            </p>
                            <p>
                                <label for="authorize_id"><?php _e('Enter your Sandbox Authorize.Net ID','event-registration');?></label>
                                <input name="authorize_sandbox_id" value="<?php  echo $company_options['authorize_sandbox_id'];?>" class="regular-text" /></td>
                            <p>
                                <label for="authorize_key"><?php _e('Enter your Sandbox Authorized.Net Txn Key','event-registration');?></label>
                                <input name="authorize_sandbox_key" value="<?php  echo $company_options['authorize_sandbox_key'];?>" class="regular-text" />
                            </p>
                           <!-- Having the account in Test Mode can allow a merchant or developer to test their website/software implementation 
                           without submitting live transactions. While in Test Mode, transactions will not be saved to the database 
                           or be viewable in search results or reports. -->
                        </div>
                        <div id="PAYFAST" class="block">
                        <h2>Payfast Gateway Settings</h2>
                        <p>
                            <label for="id"><?php _e('Enter your PayFast merchant ID..','event-registration');?></label>
                            <input name="payfast_merchant_id" value="<?php  echo $company_options['payfast__merchant_id'];?>" class="regular-text" />
                        </p>
                        <p>
                            <label for="key"><?php _e('Enter your PayFast merchant key.','event-registration');?></label>
                            <input name="payfast_merchant_key" value="<?php  echo $company_options['payfast_merchant_key'];?>" class="regular-text" />
                        </p>
                            <p><label for="payfast_sandbox"><?php _e('PayFast Mode','event-registration');?><a class="ev_reg-fancylink" href="#payfast_sandbox_info">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a></label>
                            <?php if ($company_options['payfast_sandbox']==""){$company_options['payfast_sandbox'] = "0";}?>
                               <input type="radio" name="payfast_sandbox" value="0" <?php  if ($company_options['payfast_sandbox']=="0"){echo "checked";}?>/><?php _e('Live','event-registration');?>
                               <input type="radio" name="payfast_sandbox" value="1" <?php  if ($company_options['payfast_sandbox']=="1"){echo "checked";}?>/><?php _e('Sandbox/Test','event-registration');?>
                            </p>
                            <hr />
                        <p><label for="payfast_return"><?php _e('Payfast return URL','event-registration');?><br /><font size="-6"><?php _e('(page you return to after making payments)','event-registration');?></font></label>
                        <input name="payfast_return" value="<?php  echo $company_options['payfast_cancel'];?>" class="regular-text" /></td>
                        </p>
                        <p><label for="payfast_cancel"><?php _e('Payfast cancel URL','event-registration');?><br /><font size="-6"><?php _e('(page you return to for cancelled payment)','event-registration');?></font></label>
                        <input name="payfast_cancel" value="<?php  echo $company_options['payfast_cancel'];?>" class="regular-text" /></td>
                        </p>
                        <p><label for="payfast_notify"><?php _e('Payfast ITN URL','event-registration');?><br /><font size="-6"><?php _e('(used to process payments into the database)','event-registration');?></font></label>
                        <input name="" value="No Selection Required - based registration page" class="regular-text" />
                          </p>
                            <hr />
                            <p><label for="payfast_debug"><?php _e('Use PayFast debug?','event-registration');?>
                            <a class="ev_reg-fancylink" href="#payfast_debug_info"><img src="<?php echo EVR_PLUGINFULLURL?>/img/question-frame.png" width="16" height="16" /></a><br /><font size="-6">
                            <?php _e('(used for debug)','event-registration');?></font></label>
                               <input type="radio" name="payfast_debug" value="Y" <?php  if ($company_options['payfast_debug']=="1"){echo "checked";}?>/><?php _e('Yes','event-registration');?>
                               <input type="radio" name="payfast_debug" value="N" <?php  if ($company_options['payfast_debug']=="0"){echo "checked";}?>/><?php _e('No','event-registration');?>
                            </p>
                        </div>
                        <div id="GOOGLE" class="block">
                        <h2>Google Wallet Gateway Settings</h2>
                        <p>
                            <label for="id"><?php _e('Enter your Google Wallet merchant ID..','event-registration');?></label>
                            <input name="google_id" value="<?php  echo $company_options['google_id'];?>" class="regular-text" />
                        </p>
                        <p>
                            <label for="key"><?php _e('Enter your Google Wallet merchant key.','event-registration');?></label>
                            <input name="google_key" value="<?php  echo $company_options['google_key'];?>" class="regular-text" />
                        </p>
                        </div>
                        <div id="MONSTER" class="block">
                        <h2>MonsterPay Gateway Settings</h2>
                        <p>
                            <label for="id"><?php _e('Enter your MonsterPay merchant ID..','event-registration');?></label>
                            <input name="monster_id" value="<?php  echo $company_options['monster_id'];?>" class="regular-text" />
                        </p>
                        </div>
                        <div id="TOUCHNET" class="block">
                        <h2>TouchNet UPAY Settings</h2>
                        <p>
                            <label for="upay_id"><?php _e('Enter your UPAY Site ID:','event-registration');?></label>
                            <input name="upay_id" value="<?php  echo $company_options['upay_id'];?>" class="regular-text" />
                        </p>
                        <p>
                            <label for="upay_url"><?php _e('Enter your UPAY URL that you submit to:','event-registration');?></label>
                            <input name="upay_url" value="<?php  echo $company_options['upay_url'];?>" class="regular-text" />
                        </p>
                        <p>
                            <label for="upay_success"><?php _e('Enter your UPAY Success URL.','event-registration');?></label>
                            <input name="upay_success" value="<?php  echo $company_options['upay_success'];?>" class="regular-text" />
                        </p>
                        </div>
                        <div id="PAYPAL" class="block">
                        Paypal
                        <p>
                            <label for="paypal_id"><?php _e('Enter your PayPal ID.','event-registration');?></label>
                            <input name="paypal_id" value="<?php  echo $company_options['paypal_id'];?>" class="regular-text" />
                        </p>
                        <hr />
                        <table class="form-table">
                        <tr><td colspan="2"><font color="red"><u>For Paypal Users Only</u></font></td></tr>
                        <tr valign="top">
                        <th scope="row"><label for="image_url"><?php _e('Image URL','event-registration');?><br /><font size="-6"><?php _e('(For your logo on PayPal page)','event-registration');?></font></label></th>
                        <td><input name="image_url" value="<?php  echo $company_options['image_url'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="cancel_return"><?php _e('Cancel Return URL','event-registration');?><br /><font size="-6"><?php _e('(page you setup for cancelled payment)','event-registration');?></font></label></th>
                        <td><input name="cancel_return" value="<?php  echo $company_options['cancel_return'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="notify_url"><?php _e('Notify URL','event-registration');?><br /><font size="-6"><?php _e('(used to process payments)','event-registration');?></font></label></th>
                        <td><input name="" value="No Selection Required - based registration page" class="regular-text" /></td>
                        </tr>    
                        <tr valign="top">
                        <th scope="row"><label for="return_method"><?php _e('Return Method:','event-registration');?></label></th>
                        <td><select name = "return_method" class="regular-select">
                            <?php  
                            if ($company_options['return_method']=="1"){echo "<option value='1'>".__('GET','event-registration')."</option>";}
                            if ($company_options['return_method']=="2"){echo "<option value='2'>".__('POST','event-registration')."</option>";}
                            ?>
                            <option value="1"><?php _e('GET');?></option>
                            <option value="2"><?php _e('POST');?></option>
                            </select></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="use_sandbox"><?php _e('Use PayPal Sandbox','event-registration');?><br /><font size="-6"><?php _e('(used for testing/debug)','event-registration');?></font></label></th>
                        <td><input type="radio" name="use_sandbox" value="Y" <?php  if ($company_options['use_sandbox']=="Y"){echo "checked";}?>/><?php _e('Yes','event-registration');?>
                        <input type="radio" name="use_sandbox" value="N" <?php  if ($company_options['use_sandbox']=="N"){echo "checked";}?>/><?php _e('No','event-registration');?>
                        </td>
                        </tr>
                         </table>
                        </div>
                        <div id="CUSTOM" class="block">Custom</div>
</div>
<?php    
 }
function evr_adminMail(){
    global $company_options;
   /* $settings = array( 'textarea_name' => 'content','media_buttons' => false,
                       'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
  		               'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                                	);
                                    */
    $settings = array(  
    'media_buttons' => false,
    'tinymce' => array(
        'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
            'bullist,blockquote,|,justifyleft,justifycenter' .
            ',justifyright,justifyfull,|,link,unlink,|' .
            ',spellchecker,wp_fullscreen,wp_adv'
    ));
    ?>
    <div class="padding" style="width: 800px;">
                        <p><?php _e('Do you want to send Registration Confirmation emails?','event-registration');?>
                        <input type="radio" name="send_confirm" class="regular-radio" value="Y"  <?php if ($company_options['send_confirm'] == "Y"){echo "checked";}?> />&nbsp;<?php _e('Yes','event-registration');?>&nbsp;&nbsp;
                        <input type="radio" name="send_confirm" class="regular-radio" value="N"  <?php if ($company_options['send_confirm'] == "N"){echo "checked";}?> />&nbsp;<?php _e('No','event-registration');?>
                        <br />  
                        <font size="-5" color="red"><?php _e('(This option must be enable to send custom mails in events)','event-registration');?></font></p>
                        <p><?php _e('Default Email Message','event-registration');?>:&nbsp;&nbsp;<a class="ev_reg-fancylink" href="#custom_email_settings">Settings</a> | <a class="ev_reg-fancylink" href="#custom_email_example"><?php _e('Example','event-registration');?></a>   
                        <?php if (function_exists('wp_editor')){ wp_editor( stripslashes($company_options['message']), 'message', $settings );} ?>
                        </p>
                        </div> 
                    <hr />
                    <div class="padding" style="width: 800px;">
                        <p><?php _e('Do you want to send Payment Confirmation emails?','event-registration');?>
                        <input type="radio" name="pay_confirm" class="regular-radio" value="Y"  <?php if ($company_options['pay_confirm'] == "Y"){echo "checked";}?> /><?php _e('Yes','event-registration');?>&nbsp;
                        <input type="radio" name="pay_confirm" class="regular-radio" value="N"  <?php if ($company_options['pay_confirm'] == "N"){echo "checked";}?> /><?php _e('No','event-registration');?><br />  
                        <font size="-5" color="red">(This option must be enable to send payment confrimation emails)</font>
                        </p>
                        <p><label for="payment_subj"><?php _e('Payment Message Subject','event-registration');?></label>&nbsp;
                        <input name="payment_subj" value="<?php  echo $company_options['payment_subj'];?>" class="regular-text" /></p>
                        <p><?php _e('Default Payment Received Email Message','event-registration');?>:&nbsp;&nbsp;<a class="ev_reg-fancylink" href="#custom_payment_email_settings"><?php _e('Settings','event-registration');?></a> | 
                        <a class="ev_reg-fancylink" href="#custom_payment_email_example"><?php _e('Example','event-registration');?></a></p>
                        <?php
                        if (function_exists('wp_editor')){  
                            wp_editor(stripslashes($company_options['payment_message']), 'payment_message', $settings );}
                        ?> 
                        </p>
                    <div style="clear:both;"></div>
                    </div>   
<?php
}
function evr_adminMisc(){
    global $company_options;

    $settings = array(  
    'media_buttons' => false,
    'tinymce' => array(
        'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
            'bullist,blockquote,|,justifyleft,justifycenter' .
            ',justifyright,justifyfull,|,link,unlink,|' .
            ',spellchecker,wp_fullscreen,wp_adv'
    ));
    ?>
    <div class="padding" style="width: 800px;">
                        <!--Company Contact Info -->
                    <div class="padding">
                       <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><label for="eventpaging"><?php _e('How many Events to list per page in admin view: ','event-registration');?></label></th>
                        <td><input name="eventpaging" type="text" value="<?php echo $company_options['eventpaging'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="attendeepaging"><?php _e('How many Attendees to list per page in admin view: ','event-registration');?></label></th>
                        <td><input name="attendeepaging" type="text" value="<?php echo $company_options['attendeepaging'];?>" class="regular-text" /></td>
                        </tr>
                        </table>  
                      </div>
                      <!--End Company Contact--> 
                      <hr /> 
                    <div style="clear:both;"></div>
                    </div>   
<?php
}
function evr_adminHelp(){
    ?>
    <!--Admin Help Content-->
<div style="display:none;">
    <div id="registration_page_info" style="width:650px;height:350px;overflow:auto;">
        <h2>Main Events Page</h2>
        <p>This is the page that displays your events.</p>
        <p>Additionally, all registration process pages will use this page as well.</p>
        <p>This page should contain the <strong>[EVR_EVENTS]</strong> shortcode.</p>
    </div>
</div>
<div style="display:none;">
    <div id="payment_page_info" style="width:650px;height:350px;overflow:auto;">
        <h2>Return Payment Page</h2>
        <p>This is the page that attendees return to view/make payments.</p>
        <p>This is the page that PayPal IPN uses to post payments.</p>
        <p>This page should contain the <strong>[EVR_PAYMENT]</strong> shortcode.</p>
    </div> 
</div>
<!--Payment Tab Help-->
<div style="display:none;">
    <div id="payment_gateway_info" style="width:650px;height:350px;overflow:auto;">
    <h2>Authorize.Net</h2>
    <p>Authorize.Net Payment Gateway provides the complex infrastructure and security necessary to ensure fast, 
    reliable and secure transmission of transaction data. Authorize.Net manages the routing of transactions 
    just like a traditional credit card swipe machine you find in the physical retail world, however, Authorize.Net 
    uses the Internet instead of a phone line.</p>
    <p>The payment gateway offers many features and options that can be tailored to specific merchant business models. Various fees apply.
    </p>
    <p>This plugin uses the Server Intergration Method with Authorize.Net. This allows the Payment Gateway to handle all the steps 
    in the secure transaction process - payment data collection, data submission and the response to the customer - while keeping Authorize.Net virtually transparent.</p>
    <p>This plugin supports payment notification from Authorize.Net, wherein it posts registration payments back to the payments table of the plugin.</p>
    <a href="https://ems.authorize.net/oap/home.aspx?SalesRepID=98&ResellerID=16334"><img src="http://www.authorize.net/images/reseller/oap_sign_up.gif" height="38" width="135" border="0"/></a>
    <hr/>
    <h2>PayPal</h2>
    <p>PayPal is a service that enables you to accept payments online. Though PayPal does not charge monthly fees for its Standard processing services, 
    the online payment website the company typically charges a percentage-based transaction fees, which are currently 2.9% + $.30 per transaction.  
    There are other merchant options that do charge monthly fees.</p>
    <p>In order to accept credit cards, you must have a merchant account and be verified, however buyers who choose to pay with a credit card do not need to have PayPal account.</p>
    <p>If you do not accept credit card payments, you can use a basic paypal account, but you will only be able to accept PayPal and echeck transactions.</p>
    <p>This plugin supports Instant Payment Notification from PayPal provide it is activated on your PayPal account.</p>
    <hr/>
    <h2>PayFast</h2>    
    <p>PayFast is a South African payment gateway that allows transactions in South African Rand (ZAR). 
    At the moment, PayFast is one of the most popular payment gateways in South Africa and provides extremely competitive rates.</p>
    <p>Through PayFast, the user has further payment options, which include:</p>
    <ul>
    <li>Instant EFT</li>
    <li>Credit Card</li>
    <li>MiMoney</li>
    <li>UKash</li>
    </ul>
    <hr />
    <h2>Monster Pay</h2>
    <p>A South Affican online payment gateway provider similar to Paypal.</p>
    <p>Currently only accepts USD, EUR, GBP, ZAR country currency.  You can only take funds out in the form of your residing country which must be verified.</p>
    <p>This plugin currently does not accept payment notifications from MonsterPay.</p>
    </div> 
</div>
<div style="display:none;">
    <div id="authorize_api_id" style="width:650px;height:350px;overflow:auto;">
      <h2>API Login ID</h2>
        <p>The API Login ID is a complex value that is at least eight characters in length, includes uppercase and lowercase letters, numbers, and/or symbols and identifies your account to the payment gateway. It is <span style="font-weight: bold;"><b>not</b></span> the same as your login ID for logging into the Merchant Interface. The two perform two different functions. The API Login ID is a login ID that your website uses when communicating with the payment gateway to submit transactions. It is only ever used for your website or other business application’s connection to the payment gateway.</p>
        <p>The API Login ID for your account is available in the Settings menu of the Merchant Interface.</p>
        <p><span style="font-weight: bold;"><b>IMPORTANT:</b></span> The API Login ID is a sensitive piece of account information and should only be shared on a need-to-know basis, for example with your Web developer. Be sure to store it securely.</p>
        <p class="ToDo">To obtain your API Login ID:</p>
        <ol type="1">
        <li class="p">
        <p>Log into the Merchant Interface at <a href="https://secure.authorize.net" target="_blank">https://secure.authorize.net</a></p>
        </li>
        <li class="p">
        <p>Select <span class="InteriorTerm">Settings</span> under Account in the main menu on the left</p>
        </li>
        <li class="p">
        <p>Click <span class="InteriorTerm">API Login ID and Transaction Key</span> in the Security Settings section</p>
        </li>
        <li class="p">
        <p>If you have not already obtained an API Login ID and Transaction Key for your account, you will need to enter the secret answer to the secret question you configured at account activation.</p>
        </li>
        <li class="p">
        <p>Click <span class="InteriorTerm">Submit</span>.</p>
        </li>
        </ol>
        <p>The API Login ID for your account is displayed on the API Login ID and Transaction Key page.</p>
        <p>It is highly recommended that you reset your API Login ID regularly, such as every six months, to strengthen the security of your payment gateway account. To reset your API Login ID you will need to contact Authorize.Net Customer Support. You will then need to communicate the new API Login ID to your Web developer immediately to update your website integration code. Failure to do so will result in a disruption in transaction processing.</p>
        <p class="Note">Note: The above directions apply when Multiple User Accounts is activated for your account. If this feature is not enabled for your account, you will need to activate it in order to generate and view the API Login ID in the Merchant Interface. Otherwise your current login ID is the same as the API Login ID for your account.</p>
    </div>
</div>
<div style="display:none;">
    <div id="authorize_txn_key" style="width:650px;height:350px;overflow:auto;">
        <h2>Getting Your Authorize.Net Transaction Key</h2>
        <p>The Authorized.Net generated Transaction Key is similar to a password and is used to 
        authenticate requests submitted to the Authorized.Net gateway. If a request cannot be authenticated 
        using the Transaction Key, the request is rejected. If you don't already have your Transaction Key,
        you will have to get a new one from Authorize.Net.</p>
        <p><b><font color="red">Warning</font></b>: Authorize.Net does not allow the retrieval of an
        existing transaction key. Following these instructions will get a new
        transaction key and disable any old transaction key. <br />
        <em>If you have other software, such as a shopping cart or web site, that uses the
        Authorize.Net Transaction Key, you will need to update that software with
        the new Transaction Key within 24 hours, otherwise credit card
        processing in the other software will be disabled. In this situation,
        you may wish to get your transaction key from the configuration for
        your existing software rather than obtaining a new key from
        Authorize.Net.</em></p>
        <p>The following instructions for finding your Transaction Key are
        from Authorize.Net's
        <a href="http://www.authorize.net/support/merchant_guide.pdf" rel="external">Merchant
          Integration Guide</a>.</p>
        <blockquote cite="http://www.authorize.net/support/merchant_guide.pdf" class="long-passage">
        <p>The Transaction Key is a 16-character alphanumeric value that is
        randomly generated in the Merchant Interface and works in conjunction
        with your API Login ID to authenticate you as an authorized user of
        the Authorize.Net Payment Gateway when submitting transactions from
        your Web site.</p>
        <p>Like the API Login ID, the Transaction Key is a sensitive piece of
        account information that should only be shared on a need-to-know
        basis.</p>
        <p><b>To obtain a Transaction Key</b>:</p>
        <ol>
        <li>Log into the Merchant Interface at
          <a href="https://secure.authorize.net" rel="external">https://secure.authorize.net</a></li>
        <li>Select <b>Settings</b> under Account in the main menu on the
        left</li>
        <li>Click <b>API Login ID and Transaction Key</b> in the
        Security Settings section</li>
        <li>Enter the secret answer to the secret question you configured when
        you activated your user account</li>
        <li>Click <b>Submit</b></li>
        </ol>
        <p>The Transaction Key for your account is displayed on a confirmation page.</p>
        <p><b>IMPORTANT</b>: Be sure to record your Transaction Key
        immediately in a secure manner or copy it immediately to a file in a
        secure location as it is not always visible in the Merchant Interface
        like the API Login ID. Once you navigate away from the confirmation
        page there will be no other way to access the Transaction Key in the
        Merchant Interface. You would have to generate a new Transaction
        Key.</p>
        <p>It is highly recommended that you create a new Transaction Key
        regularly, such as every six months, to strengthen the security of
        your payment gateway account. You will then need to communicate the
        new Transaction Key to your Web developer immediately to update your
        Web site integration code. Failure to do so will result in a
        disruption in transaction processing.</p>
        </blockquote>
    </div>
</div>
<div style="display:none;">
    <div id="testmode_info" style="width:650px;height:350px;overflow:auto;">
        <p>This option in the plugin will allow you turn Test Mode feature of your Authorize.Net account On/Off 
        through your website/plugin integration, which will override the Authorize.Net Merchant Interface settings described below. 
        </p>
        <p>For security reasons, all new Authorize.Net accounts are set in Test Mode. When an account is in Test Mode, 
        transactions may be submitted that will not be authorized or charged to the account numbers provided in the 
        transactions. When the payment gateway is in Test Mode, each page in your Merchant Interface will show a red banner stating:</p>
        <blockquote><p><strong><font color="#ff0000">ACCOUNT IS IN TEST MODE - REAL TRANSACTIONS WILL NOT BE PROCESSED</font></strong></p></blockquote>
        <p>Email receipts generated by Authorize.Net will also indicate whether they were submitted in Test Mode, by having this text at the top of the receipt:</p>
        <blockquote>
        <p><code>************* TEST MODE *************</code></p>
        </blockquote>
        <p>Having the account in Test Mode can allow a merchant or developer to test their website/software implementation without submitting live transactions. 
        While in Test Mode, transactions will not be saved to the database or be viewable in search results or reports.</p>
        <p>For more details on how to use Test Mode, please review the online video tutorial at <a href="http://www.authorize.net/videos">http://www.authorize.net/videos</a>.</p>
        <blockquote>
        1. Log into your Merchant Interface at <a href="https://account.authorize.net/">https://account.authorize.net</a>. <br />
        2. Click <strong>Settings</strong> in the main left side menu.<br />
        3. Click <strong>Test Mode</strong>.<br />
        4. Click the <strong>Turn Test OFF</strong> button. The interface will confirm that the Test Mode Settings have been Successfully Applied and you're now in live mode. </blockquote>
        <p>Note: You may also turn Test Mode on by following the above steps. The button will appear as <strong>Turn Test ON</strong>.</p>
        <p>This option in the plugin will allow you turn this feature On/Off through your website/plugin integration, which will override the Authorize.Net Merchant Interface settings described above. 
        </p>
        <p><em>Note: You will incur applicable transaction fee charges when Test Mode is turned off, even if you use test credit card numbers. 
        Test credit card transactions should only be processed in Test Mode. When Test Mode is turned off, Authorize.Net will attempt to process all transactions they receive.</em></p>
    </div>
</div>
<div style="display:none;">
    <div id="authorize_sandbox_info" style="width:650px;height:350px;overflow:auto;">
<p> The sandbox functions like the live (production) environment. However, there are two key differences:</p>
    <ol>
        <li>No actual card processing is performed. The sandbox only simulates connections to the card networks, but no actual card payments are processed.</li>
        <li>Developers can trigger specific error conditions and transaction responses to aid in testing.</li>
    </ol>
    <p>You must create a sandbox account with different credentials from your production account.</p>
         <div style="width:300px;float:center;border:1px solid #EBEBEB; border-radius: 10px; padding:10px;">
            <h3 style="color:#B4A82F;">ADDITIONAL SANDBOX RESOURCES</h3>
            <ul class="graydot" style="margin-top:10px;">
                <li><a href="https://sandbox.authorize.net/">Sandbox Login</a></li>
                <li><a href="http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Authorize-Net-Sandbox-FAQs/ba-p/17440">Sandbox FAQs</a></li>
                <li><a href="http://community.developer.authorize.net/t5/Integration-and-Testing/Sandbox-Password-Reset-Secret-Question-Pet-Name/td-p/31840">Resetting your password</a></li>
                <li><a href="http://10.137.48.108/user/training/">Training Videos</a></li>
            </ul>
        </div>
    </div>
</div>
<!--End Payment Tab Help-->
<div style="display:none;">
    <div id="custom_email_settings" style="width:650px;height:350px;overflow:auto;">
        <h2>Email Settings</h2><p><strong>Email Confirmations:</strong><br>
        For customized confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
        <p>[id},[fname], [lname], [phone], [event],[description], [cost], [company], [co_add1], [co_add2], [co_city],[co_state], [co_zip],[contact], [payment_url], [start_date], [start_time], [end_date], [end_time]</p>
    </div>
</div>   
<div style="display:none;"><div id="custom_email_example" style="width:650px;height:350px;overflow:auto;">
    <h2>Sample Mail Send:</h2>
    <p>***This is an automated response - Do Not Reply***</p>
    <p>Thank you [fname] [lname] for registering for [event]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p>
    <p>If you have not done so already, please submit your payment in the amount of [cost].</p>
    <p>Your unique registration ID is: [id].</p>
    <p>Click here to review your payment information [payment_url].</p>
    <p>Thank You.</p>
</div>
</div>
<div style="display:none;"><div id="custom_wait_settings" style="width:650px;height:350px;overflow:auto;">
    <h2>Email Settings</h2><p><strong>Waitlist:</strong><br>
    For customized wait list emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
    <p>[fname], [lname], [event]</p>
</div>
</div>
<div style="display:none;"><div id="custom_wait_example" style="width:650px;height:350px;overflow:auto;">
    <p>Thank you [fname] [lname] for your interest in registering for [event].</p>
    <p>At this time, all seats for the event have been taken.  
    Your information has been placed on our waiting list.  
    The waiting list is on a first come, first serve basis.</p>  
    <p>You will be notified by email with directions for completing registration and payment should a seat become available.</p>
    <p>Thank You</p>
</div>
</div>
<div style="display:none;"><div id="custom_payment_email_settings" style="width:650px;height:350px;overflow:auto;">
    <h2>Payment Confirmation Email Settings</h2><p><strong>Payment Confirmations:</strong><br>
    For customized payment confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
    <p>[id],[fname], [lname], [payer_email], [event_name],[amnt_pd], [txn_id],[address_street],[address_city],[address_state],[address_zip],[address_country],[start_date],[start_time],[end_date],[end_time] 
</div></div> 
<div style="display:none;"><div id="custom_payment_email_example" style="width:650px;height:350px;overflow:auto;">
    <h2>Sample Payment Mail Send:</h2>
    <p>***This is an automated response - Do Not Reply***</p>
    <p>Thank you [fname] [lname] for your recent payment of [amnt_pd] ([txn_id]) for [event_name]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p>
    <p>Your unique registration ID is: [id].</p>
    <p>Click here to review your payment information [payment_url].</p>
    <p>Thank You.</p>
</div> </div>     
<div style="display:none;"><div id="css_override_help" style="width:650px;height:350px;overflow:auto;">
    <p>enter css to override theme css on form</p>
    <p>D0 NOT use style  tags (< style > </ style >)</p>
</div></div>     
<!--End Admin Help Content-->
<?php
}
function evr_adminDonate(){
    ?>
    <!--Donate Button for wpeventregister.com-->
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <!--End Donate Button
    <?php
}
?>