<?php
/**
 * @author David Fleming
 * @copyright 2011
 */
?>
<!-- EventPopUpStart -->
<div id="popup<?php echo $event_id;?>" style="display:none;">
    <table class="evr_evntpop">
                            <?php  if ($header_image != ""){ ?> 
                            <tr>
                                <td>
		                          <span style="float:center;"><img src="<?php echo $header_image;?>" /></span>
                				</td>
                			</tr>
                            <?php }?>
                            <tr>
                                <td>
                                <br />
		                          <span style="float:left;"><h3><?php echo $event_name;?></h3></span>
                				  <span style="float:right;"><a href="<?php echo EVR_PLUGINFULLURL."evr_ics.php";?>?event_id=<?php echo $event_id;?>">
                                  <img src="<?php echo EVR_PLUGINFULLURL;?>images/ical-logo.jpg" /></a></span>
                				</td>
                			</tr>
                            <tr><td><div class="er_pop_date"><?php 
                            echo date($evr_date_format,strtotime($start_date))." ".$start_time." through ";
                            if ($end_date != $start_date) {echo date($evr_date_format,strtotime($end_date));}
                            echo " ".$end_time;?></div></td></tr>
                            <tr>
                				<td>
                                <?php if ($image_link !=""){?><img src="<?php echo $image_link;?>" alt="Thumbnail Image" style="float:right; margin: 0 10px 0 20px;"/><?php } else { ?>
                                <img src="<?php echo EVR_PLUGINFULLURL;?>images/event_icon.png" style="float:right; margin: 0 10px 0 20px;"/>
                                <?php } ?><br />
                                <div STYLE="text-align: justify;">
                                <?php echo html_entity_decode($event_desc);?></div><br/>
                                </td>
                			</tr>
                            <tr>
            				<td>
            					<table style="border:1px solid black;">
            						<tr>
            							<td style="width:250px;vertical-align:top;border-right:1px solid black;">
            								<div class="padding"><b><u><?php _e('Event Fees','evr_language');?>:</u></b><br /><br />
                                            <?php
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
                                                        echo $item_title.'   '.$item_custom_cur.' '.$item_price.'<br />';
                                                        }
                                               }
                                           ?>
                                            <br/>
                								<hr/>
                								<b><u>Location</u></b><br/><br/>
                                                <?php echo stripslashes($event_location);?><br />
                                                <?php echo $event_address;?><br />
                                                <?php echo $event_city.", ".$event_state." ".$event_postal;?><br /></div>
                                            </td>
							                 <td style="text-align:center;">
                                             <?php if ($google_map != ""){?>
                                             <img border="0" src="http://maps.google.com/maps/api/staticmap?center=<?php echo $event_address.",".$event_city.",".$event_state;?>&zoom=14&size=360x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|<?php echo $event_address.",".$event_city;?>&sensor=false" />
                                                <?php } ?>                                      
                                            </td>
            						</tr>
            					</table>
            				</td>	
            			</tr>
                        <tr><td><br /></td></tr>
            			<tr>
                        <td style="text-align:center;">
        				<?php if ($more_info !=""){ ?>
        				    <input type="button" onClick="window.open('<?php echo $more_info;?>');" value='MORE INFO'/> 
        			     <?php	} ?>
						<input type="button" onClick="location.href='<?php echo evr_permalink($company_options['evr_page_id']);?>action=evregister&event_id=<?php echo $event_id;?>'" value='REGISTER'/> 
				</td>
			</tr>
		</table>
</div>
<!-- EventPopUpEnd -->	