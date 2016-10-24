<?php
//function to post changes when items are rearranged
function evr_post_update_item_order(){
    global $wpdb;
			$events_cost_tbl = get_option ( 'evr_cost' );
            foreach($_GET['item'] as $key=>$value) {  
                $data_array = array('sequence' => $key);
                $where_array = array('id' => $value);
                $wpdb->update( get_option ( 'evr_cost' ), $data_array, $where_array );
                  
            }  
}
?>