<?php
//function to re arrange items in the pricing disly
function evr_reorder_items(){
    //get today's date to sort records between current & expired'
$curdate = date("Y-m-d");
//initiate connection to wordpress database.
global $wpdb, $company_options;
    ?>
<script type="text/javascript">
          jQuery(function ($)   
             {  
             $("#er_ticket_sortable").sortable({  
                    placeholder: 'ui-state-highlight',  
                   stop: function(i) {  
                       placeholder: 'ui-state-highlight'  
                       $.ajax({  
                            type: "GET",  
                            url: "admin.php?page=events&action=post_reorder_item", 
                            data: $("#er_ticket_sortable").sortable("serialize")});  
                   }  
                });  
                $("#er_ticket_sortable").disableSelection();  
             });  
        </script>
<link rel="stylesheet" type="text/css" href="<?php echo EVR_PLUGINFULLURL;?>js/jquery.ui.all.css"/>        
<style type="text/css"> 
#er_ticket_sortable { 
list-style-type: none; 
margin: 0; 
padding: 0; 
width: 90%; 
} 
#er_ticket_sortable li { 
margin: 0 3px 3px 3px; 
padding: 0.4em; 
padding-left: 1.5em; 
font-size: .8em; 
height: 30px; 
} 
#er_ticket_sortable li span { 
position: absolute; 
margin-left: -1.3em; 
 } 
 </style> 
<?php
    $events_cost_tbl = get_option ( 'evr_cost' );
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
   	$event = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . get_option ( 'evr_event' ). " WHERE id = %d", $event_id ) );
    ?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:65%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3 class='hndle'><span><?php _e('ReOrder Event Items/Cost for display: ','evr_language');?><?php echo stripslashes($event->event_name)." at ".stripslashes($event->event_location)."  ".$event->start_date."  -  ".$event->end_date;?></span></h3>
                         <div class="inside">
                            <div class="padding">        
    			                 <ul id="er_ticket_sortable">	
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
                                            ?>
                                    <li id="item_<?php echo $item_id;?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                    <font color="blue"><b><?php echo $item_cat;?> | <?php echo $item_title;?></B></font>  <?php echo $item_custom_cur." ".$item_price;?><br />
                                    <?php _e('Item Sales Begin:');?> <?php echo $item_start_date;?> - <?php _e('Item Sales End:');?> <?php echo $item_end_date;?> |</li>
                                     <?php }  }    ?>
			                     </ul>
                            </div>
                        </div>
                        <div class="inside">
                            <div class="padding">
                            <a class="button-primary" href="admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>" title="Process Change"><?php _e('Apply Changes','evr_language');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div> 
<?php
}
?>