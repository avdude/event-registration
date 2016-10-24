<?php

function evr_questions_reorder(){
       global $wpdb;
$event_id = $_REQUEST['event_id'];
$event_name = $_REQUEST['event_name'];
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
                            url: "admin.php?page=questions&action=post_reorder",  
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
 
} 
#er_ticket_sortable li span { 
position: absolute; 
margin-left: -1.3em; 
 } 
 </style> 
<br />
<div class="wrap">
          	<div class='postbox-container' style='width:90%;'>
                
                    <div class="postbox " >
                        <h3 class='hndle'><span><?php _e('ReOrder Event Questions for display order: ','evr_language');?><?php echo stripslashes($event_name);?></span></h3>
                         <div class="inside">
                            <div class="padding">        
    			                 <ul id="er_ticket_sortable">	
                                    <?php
                                    
                        			$events_question_tbl = get_option ( 'evr_question' );
                                      $questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where event_id = $event_id order by sequence ASC" );
                                        if ($questions) {
                                				foreach ( $questions as $question ) {
                                				$question_name = $question->question."(".$question->question_type.")";
                                                ?>
                                                  
                                				<li id='<?php echo "item_".$question->id;?>' class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>
                                                <?php _e('Drag Line Up or Down to ReArrange.','evr_language');?>  | <button  onclick="location.href='admin.php?page=questions&action=edit&event_id=<?php echo $event_id;?>&question_id=<?php echo $question->id;?>';"><?php _e('EDIT');?></button>
  | <button  onclick="location.href='admin.php?page=questions&action=delete&event_id=<?php echo $event_id;?>&question_id=<?php echo $question->id;?>';"><?php _e('DELETE','evr_language');?></button>
 
                                                    <?php if ($question->required == "Y") {
                                                    ?>
                                                       ||  <strong><font color="red" size = "1"><?php _e('REQUIRED','evr_language');?></font></strong>  
                                         <?php
                        					           }
                                                       ?>
                                           ||  <font color='blue' size = '1'> <?php _e('TYPE','evr_language');?>:</font> <?php echo $question->question_type;?>
                                           <br />  <font color='blue' size = '1'><?php _e('QUESTION','evr_language');?>:</font> <?php echo $question->question;?>
                                           <br />  <font color='blue' size = '1'><?php _e('RESPONSES','evr_language');?>:</font> <?php echo $question->response;?>
                                        
                                       
                                        </li>                      
                                              <?php  }
                                        }
                                        ?>
			                     </ul>
                            </div>
                        </div>
                        <div class="inside">
                            <div class="padding">
                            
                            <a class="button-primary" href="admin.php?page=questions&action=new&event_id=<?php echo $event_id;?>" title="Process Change"><?php _e('Apply Changes','evr_language');?></a>
                            
                            </div>
                        
                    </div>
                </div>
            </div>
        
</div> 
<?php
}
?>