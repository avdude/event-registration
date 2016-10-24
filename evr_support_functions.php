<?php
/**
 * @author David Fleming
 * @copyright 2010
 */
//functinos used by EVR for various things
function evr_dashboard_upcomingevents(){
    wp_add_dashboard_widget('dashboard_custom_feed', __( '<a href ="admin.php?page=event-registration/EVNTREG.php"><b> EVENTS REGISTRATION DASHBOARD</b></a>' ), 'evr_dashboard_events');
}
function evr_donate_add() {
    ?>
<h3>Support Event Registration</h3>
<div style="clear: both; display: block; padding: 10px 0; text-align:center;">
    <p><?php _e('If you find this plugin useful, please contribute to enable its continued development!','');?></p>
    <p><!--New Button for wpeventregister.com-->
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
        </form>
    </p>
</div>
    <?php
}
function evr_footer_ad(){
?>
<style>
.evr_foot_add{
    width:400px;
}
</style>
<div class="evr_foot_ad">
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></p>
/div>
<?php
}
function evr_retrieve_all_events($where ='', $orderby='',$limit=''){
    $sql = "SELECT * FROM " . get_option('evr_event');
    if($where !=''){$sql .= $where;}
    if($orderby !=''){$sql .= $orderby;}
    if($limit !=''){$sql .= $limit;}
    $results = $wpdb->get_results( $sql );
    return $results;
}
function evr_dashboard_events(){
    global $wpdb;
?>
<style>
#eventsnav {
	position:relative;
	float:left;
	width:100%;
	padding:0 0 1.75em 1em;
	margin:0;
	list-style:none;
	line-height:1em;
}
#eventsnav LI {
	float:left;
	margin:0;
	padding:0;
}
#eventsnav A {
	display:block;
	color:#444;
	text-decoration:none;
	font-weight:bold;
	background:#ddd;
	margin:0;
	padding:0.25em 1em;
	border-left:1px solid #fff;
	border-top:1px solid #fff;
	border-right:1px solid #aaa;
}
#eventsnav A:hover,
#eventsnav A:active,
#eventsnav A.here:link,
#eventsnav A.here:visited {
	background:#bbb;
}
#eventsnav A.here:link,
#eventsnav A.here:visited {
	position:relative;
	z-index:102;
}
</style>
<form name="form" method="post" action="admin.php?page=events">
                                <input type="hidden" name="action" value="new">
                                <input class="evr_button evr_add" type="submit" name="new" value="<?php  _e('ADD EVENT','evr_language');?>" />
     </form>    <ul id="eventsnav">
        <li><a href="admin.php?page=events">View Events</a></li>
        <li><a href="admin.php?page=attendee">View Attendees</a></li>
        <li><a href="admin.php?page=payments">Payments</a></li>
    </ul>
<table style="width:auto;" class="events_dashboard_window">
    <thead>
        <tr  style="text-align:left">
            <th><font color="green"><b>Next 5 Upcoming Events </b></font> </th><th>
     </th>
        </tr>
    </thead> 
    <tbody>
<?php 
$sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e') LIMIT 5"; 
$rows = $wpdb->get_results( $sql );
if ($rows){
    foreach ($rows as $event){
        $event_id       = $event->id;
        $event_name     = stripslashes($event->event_name);
        $event_location = stripslashes($event->event_location);
        $event_address  = $event->event_address;
        $event_city     = $event->event_city;
        $event_postal   = $event->event_postal;
        $reg_limit      = $event->reg_limit;
  		$start_time     = $event->start_time;
  		$end_time       = $event->end_time;
  		$conf_mail      = $event->conf_mail;
        $custom_mail    = $event->custom_mail;
  		$start_date     = $event->start_date;
  		$end_date       = $event->end_date;
        $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id=%d",$event_id));
        if ($number_attendees == '' || $number_attendees == 0){
            $number_attendees = '0';
            }
        if ($reg_limit == "" || $reg_limit == " "){
            $reg_limit = "Unlimited";}
        $available_spaces = $reg_limit;
        $exp_date = $end_date;
        $todays_date = date("Y-m-d");
        $today = strtotime($todays_date);
        $expiration_date = strtotime($exp_date);
        if ($expiration_date <= $today){
            $active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED','evr_language').'</span>';
            } 
            else
            {
                $active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE','evr_language').'</span>';
				} 			  
?>
        <tr>
            <td style="text-align:left; padding:2px">
                <a title="View event" href="admin.php?page=events&event_id=<?php echo $event_id?>&action=get_details"><?php echo $event_name?></a>
                <br />
                &nbsp;&nbsp;&nbsp;  <?php echo $start_date;?> @ <?php echo $start_time ?> </td><td> 
                <a href="admin.php?page=attendee&action=view&event=<?php echo $event_id;?>">Attendees</a> 
                <br />
                &nbsp;&nbsp;<?php echo $number_attendees?> / <?php echo $reg_limit?>
            </td>
        </tr>
<?php
}}
?>
    </tbody>
</table>
<?php
}
function evr_moneyFormat($number, $currencySymbol = '', $decPoint = '.', $thousandsSep = ',', $decimals = 2) {
return $currencySymbol . number_format($number, $decimals,
$decPoint, $thousandsSep);
}
function evr_DateSelector($inName, $useDate=0) 
                    { 
                    /* create array so we can name months */ 
                    $monthName = array(1=> "January", "February", "March", 
                    "April", "May", "June", "July", "August", 
                    "September", "October", "November", "December"); 
                    /* if date invalid or not supplied, use current time */ 
                    if($useDate == 0){$useDate = Time();} 
                    /* make month selector */ 
                    echo "<SELECT NAME=" . $inName . "_month\">\n"; 
                    for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
                    { 
                    echo "<OPTION VALUE=\""; 
                    echo intval($currentMonth); 
                    echo "\""; 
                    if(intval(date( "m", $useDate))==$currentMonth) 
                    { 
                    echo " SELECTED"; 
                    } 
                    echo ">" . $monthName[$currentMonth] . "\n"; 
                    } 
                    echo "</SELECT>"; 
                    /* make day selector */ 
                    echo "<SELECT NAME=" . $inName . "_day\">\n"; 
                    for($currentDay=1; $currentDay <= 31; $currentDay++) 
                    { 
                    echo "<OPTION VALUE=\"$currentDay\""; 
                    if(intval(date( "d", $useDate))==$currentDay) 
                    { 
                    echo " SELECTED"; 
                    } 
                    echo ">$currentDay\n"; 
                    } 
                    echo "</SELECT>"; 
                    /* make year selector */ 
                    echo "<SELECT NAME=" . $inName . "_year\">\n"; 
                    $startYear = date( "Y", $useDate); 
                    for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
                    { 
                    echo "<OPTION VALUE=\"$currentYear\""; 
                    if(date( "Y", $useDate)==$currentYear) 
                    { 
                    echo " SELECTED"; 
                    } 
                    echo ">$currentYear\n"; 
                    } 
                    echo "</SELECT>"; 
} 
function evr_check_form_submission(){
echo "Check POST/GET/REQUEST Variables<br>";
foreach ($_REQUEST as $key => $val)
echo "$key = $val<br>";
}
function utf8_to_html ($data)
 {
 return preg_replace("/([\\xC0-\\xF7]{1,1}[\\x80-\\xBF]+)/e", '_utf8_to_html("\\1")', $data);
 }
function _utf8_to_html ($data)
 {
 $ret = 0;
 foreach((str_split(strrev(chr((ord($data{0}) % 252 % 248 % 240 % 224 % 192) + 128) . substr($data, 1)))) as $k => $v)
 $ret += (ord($v) % 128) * pow(64, $k);
 return "&#$ret;";
 }
function evr_form_build($question, $answer = "") {
	$required = '';
	if ($question->required == "Y") {
		$required = ' class="r"';
	}
    if ($question->remark) { $title =  $question->remark;}
	switch ($question->question_type) {
		case "TEXT" :
			echo "<span class=\"fieldbox\"><input type=\"text\" $required id=\"TEXT_$question->id\"  name=\"TEXT_$question->id\" size=\"40\" title=\"$question->question\" value=\"$answer\" /></span>\n";
			break;
		case "TEXTAREA" :
			echo "<span class=\"msgbox\"><textarea id=\"TEXTAREA_$question->id\" $required name=\"TEXTAREA_$question->id\" title=\"$question->question\" cols=\"30\" rows=\"5\">$answer</textarea></span>\n";
			break;
		case "SINGLE" :
			$values = explode ( ",", $question->response );
			$answers = explode ( ",", $answer );
			foreach ( $values as $key => $value ) {
				$checked = in_array ( $value, $answers ) ? " checked=\"checked\"" : "";
				//echo "<label><input id=\"SINGLE_$question->id_ $key\ "$required name=\"SINGLE_$question->id\" title=\"$question->question\" type=\"radio\" value=\"$value\"$checked /> $value</label><br/>\n";
			// echo "<span class=\"radio\"><input id=\"SINGLE_$question->id_ $key\"$required name=\"SINGLE_$question->id\" title=\"$question->question\" type=\"radio\" value=\"$value\"$checked /> $value</span>\n";
 echo '<span class="radio"><input id="SINGLE_'.$question->id.'_'.$key.'" '.$required.' name="SINGLE_'.$question->id.'" title="'.$question->question.'" type="radio" value="'.$value.'" '.$checked.' /> '.$value.'</span>';
            }
			break;
		case "MULTIPLE" :
			$values = explode ( ",", $question->response );
			$answers = explode ( ",", $answer );
			foreach ( $values as $key => $value ) {
				$checked = in_array ( $value, $answers ) ? " checked=\"checked\"" : "";
			/*	echo "<label><input type=\"checkbox\"$required id=\"MULTIPLE_$question->id_$key\" name=\"MULTIPLE_$question->id_$key\" title=\"$question->question\" value=\"$value\"$checked /> $value</label><br/>\n"; */
			//echo "<label><input id=\"$value\"$required name=\"MULTIPLE_$question->id[]\" title=\"$question->question\" type=\"checkbox\" value=\"$value\"$checked /> $value</label><br/>\n";
			echo "<span class=\"radio\"><input id=\"$value\" $required name=\"MULTIPLE_$question->id[]\" title=\"$question->question\" type=\"checkbox\" value=\"$value\" $checked /> $value</span>\n";
            }
			break;
		case "DROPDOWN" :
			$values = explode ( ",", $question->response );
			$answers = explode ( ",", $answer );
			echo "<select name=\"DROPDOWN_$question->id\" $required id=\"DROPDOWN_$question->id\" title=\"$question->question\" />";
			echo "<option value=''>Select One </option><br/>";
			foreach ( $values as $key => $value ) {
				$checked = in_array ( $value, $answers ) ? " selected =\" selected\"" : "";
				echo "<option value=\"$value\" /> $value</option><br/>\n";
			}
			echo "</select>";
			break;
		default :
			break;
	}
}
function evr_form_build_edit ($question, $edits) {
	$required = '';
	if ($question->required == "Y") {
		$required = ' class="r"';
	}
	switch ($question->question_type) {
		case "TEXT" :
			echo "<span class=\"fieldbox\"><input type=\"text\"$required id=\"TEXT_$question->id\"  name=\"TEXT_$question->id\" size=\"40\" title=\"$question->question\" value=\"$edits\" /></span>";
			break;
		case "TEXTAREA" :
			echo "<span class=\"msgbox\"><textarea id=\"TEXTAREA_$question->id\"$required name=\"TEXTAREA_$question->id\" title=\"$question->question\" cols=\"30\" rows=\"5\">".$edits."</textarea></span>";
			break;
		case "SINGLE" :
			$values = explode ( ",", $question->response );
			$answers = explode ( ",", $edits );
			foreach ( $values as $key => $value ) {
				$checked = in_array ( $value, $answers ) ? " checked=\"checked\"" : "";
				echo "<p class=\"hanging-indent radio_rows\"><input id=\"SINGLE_$question->id_$key\"$required name=\"SINGLE_$question->id\" title=\"$question->question\" type=\"radio\" value=\"$value\"$checked /> $value  </p>";
			}
			break;
		case "MULTIPLE" :
			$values = explode ( ",", $question->response );
			$answers = explode ( ",", $edits );
			foreach ( $values as $key => $value ) {
				$checked = in_array ( $value, $answers ) ? " checked=\"checked\"" : "";
			/*	echo "<label><input type=\"checkbox\"$required id=\"MULTIPLE_$question->id_$key\" name=\"MULTIPLE_$question->id_$key\" title=\"$question->question\" value=\"$value\"$checked /> $value</label><br/>\n"; */
			echo " <p class=\"hanging-indent radio_rows\"><input id=\"$value\"$required name=\"MULTIPLE_$question->id[]\" title=\"$question->question\" type=\"checkbox\" value=\"$value\"$checked /> $value  </p>";
			}
			break;
		case "DROPDOWN" :
			$values = explode ( ",", $question->response );
			//$answers = explode ( ",", $edits );
			echo "<select name=\"DROPDOWN_$question->id\"$required id=\"DROPDOWN_$question->id\" title=\"$question->question\" />".BR;
			echo "<option value=\"$edits\">$edits</option><br/>";
			foreach ( $values as $key => $value ) {
				//$checked = in_array ( $value, $answers ) ? " selected =\" selected\"" : "";
					echo "<option value=\"$value\" /> $value</option><br/>\n";
			}
			echo "</select>";
			break;
		default :
			break;
	}
}
function evr_greaterDate($start_date,$end_date)
{
  $start = strtotime($start_date);
  $end = strtotime($end_date);
  if ($start-$end >= 0)
    return 1;
  else
   return 0;
}
function evr_registration(){
    evr_reg_box_content();
 }
function evr_reg_box_content(){
global $evr_ver, $wpdb;
?>
<div class="wrap">
<h2 style="font-family: segoe;"><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL; ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Software Registration - Event Registration for Wordpress','evr_language');?></h2>
<?php
if (get_option('evr_is_registered') != "Y"){
    if(isset($_POST["Send"])){
        $email_body = "Site: ".$_POST['blog']."<br/>"."URL: ".$_POST['url']."<br/>";
        $email_body.= "WP Ver: ".$_POST['wp_ver']."<br/>"."Email: ".$_POST['email']."<br/><br/>";
        $email_body.= "EVR Ver:".$_POST['evr_ver']."<br/>"."EVR Date: ".$_POST['evr_dt']."<br/>"."EVR Key: ".$_POST['key']."<br/><br/>";
        $email_body.= "Donated:".$_POST['donated']."<br/>"."PayPal TXN: ".$_POST['txn_id']."<br/>"."Amount Donated: ".$_POST['amnt_dntd']."<br/>";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Plugin Registration <>\r\n";
        if (wp_mail("activation@wpeventregister.com", "Plugin Registration", html_entity_decode($email_body), $headers)){
            echo "Your Registration Information Has Been Submitted.";
            $option_name = 'evr_is_registered';
            $newvalue = "Y";
            update_option($option_name, $newvalue);
            }
    } 
    else { 
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <ul>
        <li>Site Name <input name="blog" size="100" value="<?php echo get_bloginfo('name');?>"/></li>
        <li>Site URL <input name="url" size="100" value="<?php echo site_url();?>"/></li>
        <li>WP Version <input name="wp_ver" size="100" value=" <?php bloginfo('version');?>"/></li>
        <li>Contact Email <input name="email" size="100" value=" <?php echo get_bloginfo('admin_email');?>"/></li>
    </ul>
<hr/>
    <ul>
        <li>Event Registration Version <input name="evr_ver" value=" <?php echo $evr_ver;?>"/></li>
        <li>Date EVR Activated <input size="50" name="evr_dt" value=" <?php echo date(r,get_option('evr_date_installed'));?>"/></li>
        <input type="hidden" name="key" value="<?php echo get_option('plug-evr-activate');?>"/>
    </ul>
    <hr />
    <p>If you have donated, please include your donation information:</p>
    <ul>
        <li>Have you donated: <input type="radio" name="donated" value="Yes">Yes  <input type="radio" name="donated" value="No">No</li>
        <li>Paypal TXN ID <input name="txn_id" size="100"/></li>
        <li>Donation Amount: <input name="amnt_dntd" /></li>
        <input type="hidden" name="key" value="<?php echo get_option('plug-evr-activate');?>"/>
    </ul>    
<input type="submit" name="Send" value="Send Information" onclick="alert('I consent to send this information to support@wpeventregister.com')"/>   </form> 
<?php 
        }
    } 
    else {
        	echo "<div class='updated fade'><p><strong>".__('The Event Registration software is registered!')."</strong></p></div>";
        ?>
        	<h3>Support Event Registration</h3>
    				<div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful,<br /> please contribute to enable its continued development!<br />
                <br /><p>
                <!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
                </p>
    		</div>
            <?php
    }
    ?>
    </div>
    <?php
}
function evr_mod_notification($module_name)
{
  global $wpdb;
    $guid=md5(uniqid(mt_rand(), true));
    $option_name = 'plug-'.$module_name.'-activate';
    $newvalue = $guid;
    update_option($option_name, $newvalue);
}
?>