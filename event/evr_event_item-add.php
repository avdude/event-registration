<?php
//function to add sale items
function evr_add_item1(){
//get today's date to sort records between current & expired'
$curdate = date("Y-m-d");
//initiate connection to wordpress database.
global $wpdb, $company_options;
//$company_options = get_option('evr_company_settings');
?>
<?php
$currency_format = $company_options['default_currency'];
    $curdate = date("Y-m-d");
 	$event_id = $_REQUEST ['event_id'];
    $event = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . get_option ( 'evr_event' ). " WHERE id = %d", $event_id ) );
?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div class='postbox-container' style='width:75%;'>
        <div id='normal-sortables' class='meta-box-sortables'>
            <div id="dashboard_right_now" class="postbox " >
                <h3 class='hndle'><span><?php _e('Event Items: ','evr_language');?><?php echo stripslashes($event->event_name)." at ".stripslashes($event->event_location)."  ".$event->start_date."  -  ".$event->end_date;?></span></h3>
                 <div class="inside">
                    <div class="padding">
<?php
$start = strtotime('6:00am');
$end = strtotime('11:45pm');
?>
<style>
.tooltip a{
    cursor:default!important;
    background:url(<?php echo EVR_PLUGINFULLURL;?>images/red.png) no-repeat bottom left;
    text-decoration:none;
    position:relative;top:-1px
}
.tooltip a span{
    padding:0 5px;
    visibility:hidden
}
.tooltip a:hover{
    text-decoration:none!important
}
.er_ticket_info a{
    cursor:default!important;
    background:url(<?php echo EVR_PLUGINFULLURL;?>images/info-icon.png) no-repeat bottom left;
    text-decoration:none;
    position:relative;top:-1px
}
.er_ticket_info a span{
    padding:0 5px;
    visibility:hidden
}
.er_ticket_info a:hover{
    text-decoration:none!important
}
/*------------------POPUPS------------------------*/
</style>
       <div class="postbox " style='width:98%;'>
			<h2><span><img src="<?php echo EVR_PLUGINFULLURL;?>images/icon_ticket.png" alt="tickets" style="vertical-align:middle" /> REGISTRATION/TICKET TYPES</span></h2>
			<p> Add tickets to set pricing for your event (Adult, Child, VIP, etc.) </p>
            <br /><br />
			<table class="wp-list-table widefat fixed posts">
				<thead>
					<tr>
						<th class="full first" width="50%" align="left"> <span class="cufon"> <?php _e('Name','evr_language');?> </span> </th>
						<th class="center" width="75" align="left"> <span class="cufon"> <?php _e('Price','evr_language');?> </span> </th>
						<th class="center" width="75" > <span class="cufon"> <?php _e('Start','evr_language');?> </span> </th>
                        <th class="center" width="75" > <span class="cufon"> <?php _e('End','evr_language');?> </span> </th>
						<th class="center" colspan="2" width="75" >
							<div> <span class="cufon"><?php _e('Actions','evr_language');?></span> </div>
						</th>
                        <th></th>
					</tr>
				</thead>
				<tfoot>
                  	<tr>
						<th class="full first"> <span class="cufon"> <?php _e('Name','evr_language');?> </span> </th>
						<th class="center" > <span class="cufon"> <?php _e('Price','evr_language');?> </span> </th>
						<th class="center" > <span class="cufon"> <?php _e('Start','evr_language');?> </span> </th>
                        <th class="center" > <span class="cufon"> <?php _e('End','evr_language');?> </span> </th>
						<th class="center" colspan="2">
							<div> <span class="cufon"><?php _e('Actions','evr_language');?></span> </div>
						</th>
                        <th></th>
					</tr>  
               </tfoot>
         <tbody>
    <?php
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
                                                        if ($item_custom_cur == "GBP"){$item_custom_cur = "&pound;";}
                                                        if ($item_custom_cur == "USD"){$item_custom_cur = "$";}
                                                        echo "<tr><td class='er_ticket_info' title='".$item_description."' style='WORD-BREAK:BREAK-ALL;'>";
                                                        if ($free_item == "Y"){?><img src="<?php echo EVR_PLUGINFULLURL;?>images/free_icon.png" alt="free" style="vertical-align:middle" />&nbsp;<?php }
                                                        if ($free_item == "N"){ ?><img src="<?php echo EVR_PLUGINFULLURL;?>images/dollar_icon.png" alt="free" style="vertical-align:middle" /> <?php }
                                                        echo $item_cat." | ".$item_title." <a><span>?</span></a></td><td align='left'>".$item_custom_cur." ".$item_price."</td><td align='center'>".$item_start_date."</td><td align='center'>".$item_end_date."</td>";
                                                        ?>
                                                        <td width="15" align="right">
                                                        <a  href="TB_inline?inlineId=popup<?php echo $item_id;?>;&width=640&height=914" class="thickbox" ><img src="<?php echo EVR_PLUGINFULLURL;?>images/small_gear.png" alt="Edit" /></td>
                                                        <td width="15" align="left"><a href="admin.php?page=events&action=delete_item&event_id=<?php echo $event_id;?>&item_id=<?php echo $item_id;?>&end_date=<?php echo $end_date;?>&end=<?php echo $end_date;?>"><img src="<?php echo EVR_PLUGINFULLURL;?>images/redx.png" alt="Delete" />
                                                        </td><td></td><tr>
                                                        <?php }      
                                               }
            ?>
			</tbody></table>
            <br />
            <div style="float: left;">
            <div class="padding">
            <a href="#TB_inline?width=640&height=914&inlineId=popup0" class="thickbox"><button class="button-primary"><?php _e('ADD COST/ITEM','evr_language');?></button></a>
            </div></div>
            <div style="float: right;">
            <div class="padding">
            <a class="button-primary" href="admin.php?page=events&action=reorder_item&event_id=<?php echo $event_id;?>" title="REARRANGE ITEMS"><?php _e('RE-ARRANGE ITEMS','evr_language');?></a>
            </div></div>
            <br /><br />
         </div>
          <div class="postbox" style='width:98%;' ><div class="inside">
                    <div class="padding">
         <h2><span><img src="<?php echo EVR_PLUGINFULLURL;?>images/discount_icon.png" alt="tickets" style="vertical-align:middle" /> <?php _e('COUPON CODE','evr_language');?></span></h2>
         <form name="discount" method="post" action="<?php echo "admin.php?page=events&action=update_coupon&event_id=". $event_id."&end_date=".$_REQUEST['end'];?>">
        <ul><li>
        <label class="tooltip" title="<?php _e('A coupon code is a promotional code you can tie to your event.  The code is valid for a discount off the total registration cost.">
	       Do you want to use a coupon code for this event? ','evr_language');?><a><span>?</span></a></label>
            <input type="radio" class="radio" name="use_coupon" value="Y" <?php if ($event->use_coupon == "Y") { echo "checked";}?>/> <?php _e('Yes','evr_language');?> 
            <input type="radio" class="radio" name="use_coupon" value="N" <?php if ($event->use_coupon == "N") { echo "checked";}?>/><?php _e('No','evr_language');?> 
            </li>
            <li>
            <label class="tooltip" title="<?php _e('This should be a one word code with no spaces or extra characters. Recommend ALL CAPS.','evr_language');?>">
					<?php _e('Enter the Code','evr_language');?> <a><span> ?</span></a></label> 
					<input id="coupon_code" name="coupon_code" type="text" value="<?php echo $event->coupon_code;?>"/></li>
                    <li><label class="tooltip" title="<?php _e('Enter the amount with two decimal places.  You MUST put a - sign before the value, otherwise this will add to the total
                     during calculations. i.e. -10.00  ','evr_language');?>">
					<?php _e('Discount amount for Coupon Code','evr_language');?> <a><span> ?</span></a></label>
					<input id="coupon_code_price" name="coupon_code_price" type="text" value="<?php echo $event->coupon_code_price;?>"/>
				     </li>
        </ul>
         <br /><br /><br />
    <input type="hidden" name="page" value="events"/>
    <input type="hidden" name="action" value="update_coupon"/>
    <input type="hidden" name="id" value="<?php echo $event_id;?>"/>
    <input type="hidden" name="end" value="<?php echo $end_date;?>"?>
    <button type="submit" style="font-size:110%; border-color:RED; background-color: #BBBBBB; color: #GGG; font-weight: bolder;"><?php _e('UPDATE COUPON CODE','evr_language');?></button><br /></form>
    </div>
</div>
</div>
</div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- PopUp Window Form for Event Cost -->
<!--POPUP START-->
<div id="popup0" style="display:none;" >
<form action="" method="POST" >
    <input type="hidden" name="page" value="events">
    <input type="hidden" name="action" value="post_item">
    <input type="hidden" name="event_id" value="<?php echo $_REQUEST['event_id'];?>"/>
    <input type="hidden" name="event_end" value="<?php echo $end_date;?>"/>
    <h3><?php _e('Add Event Cost/Item for ','evr_language'); echo stripslashes($event->event_name);?></h3>					
                <br /><ul>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Select a Item/Cost category.  Note that category REG is used for attendance count, all others are not included in attendance count.','evr_language');?>">
                        <?php _e('What type of Item/Cost is this?','evr_language');?> <a><span>?</span></a> </label>
                        <select class="title" name="item_cat">
                        <option value="REG">REG - <?php _e('Registration Attendee','evr_language');?></option>
                        <option value="MDS">MDS - <?php _e('Merchandise','evr_language');?></option>
                        <option value="DSC">DSC - <?php _e('Discount','evr_language');?></option>
                        <option value="WRK">WRK - <?php _e('Workshop','evr_language');?></option>
                        <option value="MLS">MLS - <?php _e('Meal or Food','evr_language');?></option>
                        </select>
                    </li>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Use a concise but descriptive name. Limit is 69 Characters.','evr_language');?>" ><?php _e('Name of Cost/Item','evr_language');?> <a><span>?</span></a></label>
                        <input class="title" name="item_name" maxlength="69" size="70"/>
                    </li>
                    <li>
                        <label for="cost_desc" class="er_ticket_info" title="<?php _e('Provide a description of the cost/ticket.','evr_language');?>"><?php _e('Description of Cost','evr_language');?> <a><span>?</span></a></label>
                        <input class="desc"  name="item_desc" id="cost_desc" maxlength="69" size="70" /> 
                    </li>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Provide the number of available item/cost types per registration form. If it is a REG item, available seats will impact overall amount available. Leave blank if no limit (system will default to 25).','evr_language');?>"><?php _e('Available items/cost per registration/order?','evr_language');?> <a><span>?</span></a></label>
                        <input class="title" name="item_limit"/>
                    </li>
                    <hr />
   					<h3><?php _e('VALUE/COST','evr_language');?></h3>
				    <li>
                        <label  class="er_ticket_info" title="<?php _e('Please select no for event pricing setup, select yes for free event','evr_language');?>">
        					<?php _e('Will this be a free item?','evr_language');?><a><span><img src="http://localhost/test/wp-content/plugins/EVR/images/info-icon.png"/></span></a></label>
                        <input type="radio" name="item_free" class="radio" id="free_yes" value="Y"/> <?php _e('Yes','evr_language');?> 
                        <input type="radio" name="item_free" class="radio" id="free_no" value="N"    checked  /><?php _e('No','evr_language');?>  
                    </li>
                    <li>
                        <label  class="er_ticket_info" title="<?php _e('Please select the country in which the currency format will be used','evr_language');?>"><?php _e('Custom Currency','evr_language');?><a><span>?</span></a></label>
        					<select class="select" name = "custom_cur">
                                <?php if ($currency_format !=""){echo "<option value='" . $currency_format . "'>" . $currency_format . "</option>";} ?>
                                    <option value="USD">USD</option>
                                    <option value="AUD">AUD</option>
                                    <option value="GBP">GBP</option>
                                    <option value="CAD">CAD</option>
                                    <option value="CZK">CZK</option>
                                    <option value="DKK">DKK</option>
                                    <option value="EUR">EUR</option>
                                    <option value="HKD">HKD</option>
                                    <option value="HUF">HUF</option>
                                    <option value="ILS">ILS</option>
                                    <option value="JPY">JPY</option>
                                    <option value="MXN">MXN</option>
                                    <option value="NZD">NZD</option>
                                    <option value="NOK">NOK</option>
                                    <option value="PLN">PLN</option>
                                    <option value="SGD">SGD</option>
                                    <option value="SEK">SEK</option>
                                    <option value="CHF">CHF</option></select>
                    </li>
        			<li>
                        <label  class="er_ticket_info" title="<?php _e('Please enter the amount using 2 decimal point (i.e. 10.00) for the registration cost.  Use minus symbol before for discount amounts (i.e. -5.00).','evr_language');?>">
    				    <?php _e('Item/Cost Value','evr_language');?> <a><span>?</span></a></label>
       					<input class="price" id="item_price" name="item_price" type="text" maxlength="10" value="0.00" />
                    </li>
                    <hr />
        			<?php $popup_date_end = strtotime($_REQUEST['end']);?>
					<h3><?php _e('AVAILABILITY TIMES OF COST/ITEM','evr_language');?></h3>
                    <li>
                        <label  for="item_start_date"><b>Start Date</b></label><?php evr_DateSelector( "\"item_start"); ?>
                    </li>
                    <li>
                        <label for="item_end_date"><b>End Date</b></label><?php evr_DateSelector( "\"item_end","$popup_date_end"); ?>
                    </li>
                </ul>
                <hr />
                <br />
                <input type="hidden" name="end" value="<?php echo $end_date;?>"/>
                <input class="button-primary" type="SUBMIT" value="<?php _e('ADD NEW COST/TICKET','evr_language');?>">
            </form>	 
        </div>
<!-- Begin Popup for Edit Items  --->
<?php 
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
<div id="popup<?php echo $item_id;?>" style="display:none;">
<form action="admin.php?page=events" method="POST" >
    <input type="hidden" name="action" value="update_item"/>
    <input type="hidden" name="event_id" value="<?php echo $event_id;?>"/>
    <input type="hidden" name="item_id" value="<?php echo $item_id;?>"/>
    <input type="hidden" name="event_end" value="<?php echo $end_date;?>"/>
    <h3><?php _e('Edit Event Cost/Item for ','evr_language'); echo stripslashes($event->event_name);?></h3>					
                <br /><ul>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Select a Item/Cost category.  Note that category REG is used for attendance count, all others are not included in attendance count.','evr_language');?>">
                        <?php _e('What type of Item/Cost is this?','evr_language');?> <a><span>?</span></a> </label>
                        <select class="title" name="item_cat">
                        <option value="<?php echo $item_cat;?>">
                        <?php if ($item_cat == "REG"){
                            echo "REG - ";
                            _e('Registration Attendee','evr_language');
                            }
                            elseif ($item_cat == "MDS"){
                            echo "MDS - ";
                            _e('Merchandise','evr_language');
                            }
                            elseif ($item_cat == "DSC"){
                            echo "DSC - ";
                            _e('Discount','evr_language');
                            }
                            elseif ($item_cat == "WRK"){
                            echo "WRK - ";
                            _e('Workshop','evr_language');
                            }
                            elseif ($item_cat == "MLS"){
                            echo "MLS - ";
                            _e('Meal or Food','evr_language');
                            }
                        ?>
                        </option>
                        <option value="REG">REG - <?php _e('Registration Attendee','evr_language');?></option>
                        <option value="MDS">MDS - <?php _e('Merchandise','evr_language');?></option>
                        <option value="DSC">DSC - <?php _e('Discount','evr_language');?></option>
                        <option value="WRK">WRK - <?php _e('Workshop','evr_language');?></option>
                        <option value="MLS">MLS - <?php _e('Meal or Food','evr_language');?></option>
                        </select>
                    </li>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Use a concise but descriptive name.','evr_language');?>" ><?php _e('Name of Cost/Item','evr_language');?> <a><span>?</span></a></label>
                        <input class="title" name="item_name" value="<?php echo $item_title;?>" maxlength="69" size="70" />
                    </li>
                    <li>
                        <label for="cost_desc" class="er_ticket_info" title="<?php _e('Provide a description of the cost/ticket.','evr_language');?>"><?php _e('Description of Cost','evr_language');?> <a><span>?</span></a></label>
                        <input class="desc"  name="item_desc" id="cost_desc" value="<?php echo $item_description;?>" maxlength="69" size="70" /> 
                    </li>
                    <li>
                        <label class="er_ticket_info" title="<?php _e('Provide the number of available item/cost types per registration form. If it is a REG item, available seats will impact overall amount available. Leave blank if no limit.','evr_language');?>">
                        <?php _e('Available items/cost per registration/order?','evr_language');?> <a><span>?</span></a></label>
                        <input class="title" name="item_limit" value="<?php echo $item_limit;?>"/>
                    </li>
                    <hr />
   					<h3><?php _e('VALUE/COST','evr_language');?></h3>
				    <li>
                        <label  class="er_ticket_info" title="<?php _e('Please select no for event pricing setup, select yes for free event','evr_language');?>">
        					<?php _e('Will this be a free item?','evr_language');?><a><span><img src="http://localhost/test/wp-content/plugins/EVR/images/info-icon.png"/></span></a></label>
                        <input type="radio" name="item_free" class="radio" id="free_yes" value="Y"  <?php if ($free_item =="Y"){echo "checked";} ?> /><?php _e('Yes','evr_language');?> 
                        <input type="radio" name="item_free" class="radio" id="free_no" value="N" <?php if ($free_item =="N"){echo "checked";} ?> /><?php _e('No','evr_language');?> 
                    </li> 
                    <li>
                        <label  class="er_ticket_info" title="<?php _e('Please select the country in which the currency format will be used','evr_language');?>"><?php _e('Custom Currency','evr_language');?><a><span>?</span></a></label>
        					<select class="select" name = "custom_cur">
                                <?php if ($item_custom_cur !=""){echo "<option value='" . $item_custom_cur . "'>" . $item_custom_cur . "</option>";} ?>
                                    <option value="USD">USD</option>
                                    <option value="AUD">AUD</option>
                                    <option value="GBP">GBP</option>
                                    <option value="CAD">CAD</option>
                                    <option value="CZK">CZK</option>
                                    <option value="DKK">DKK</option>
                                    <option value="EUR">EUR</option>
                                    <option value="HKD">HKD</option>
                                    <option value="HUF">HUF</option>
                                    <option value="ILS">ILS</option>
                                    <option value="JPY">JPY</option>
                                    <option value="MXN">MXN</option>
                                    <option value="NZD">NZD</option>
                                    <option value="NOK">NOK</option>
                                    <option value="PLN">PLN</option>
                                    <option value="SGD">SGD</option>
                                    <option value="SEK">SEK</option>
                                    <option value="CHF">CHF</option></select>
                    </li>
        			<li>
                        <label  class="er_ticket_info" title="<?php _e('Please enter the amount using 2 decimal point (i.e. 10.00) for the registration cost.  Use minus symbol before for discount amounts (i.e. -5.00).','evr_language');?>">
    				    <?php _e('Item/Cost Value','evr_language');?> <a><span>?</span></a></label>
       					<input class="price" id="item_price" name="item_price" type="text" maxlength="10" value="<?php echo $item_price;?>" />
                    </li>
                    <hr />
        			<?php $popup_date_end = strtotime($_REQUEST['end']);?>
					<h3><?php _e('AVAILABILITY TIMES OF COST/ITEM','evr_language');?></h3>
                    <li>
                        <label  for="item_start_date"><b>Start Date</b></label><?php evr_DateSelector( "\"item_start",strtotime($item_start_date)); ?>
                    </li>
                    <li>
                        <label for="item_end_date"><b>End Date</b></label><?php evr_DateSelector( "\"item_end",strtotime($item_end_date)); ?>
                    </li>
                </ul>
                <hr />
                <br />
                <input type="hidden" name="end" value="<?php echo $end_date;?>"/>
                <input class="button-primary" type="SUBMIT" value="<?php _e('UPDATE COST/TICKET','evr_language');?>"/>
            </form>	 
</div>   
<?php }}  ?>
<!--END POPUP-->
<div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful, please contribute to enable its continued development!<br />
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php
}
?>