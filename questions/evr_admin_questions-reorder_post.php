<?php
function evr_post_question_order(){
			global $wpdb;
			foreach($_GET['item'] as $key=>$value) { 
			     $content = array( 'sequence'=>$key);
                 $where = array ('id'=>$value);
                 $wpdb->update(get_option('evr_question'), $content, $where); 
            }  
}
?>