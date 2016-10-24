<?php

function evr_questions_edit(){
    global $wpdb;
    $event_name = $_REQUEST['event_name'];
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $question_id = $_REQUEST['question_id'];
    ?>
<div class="wrap">
<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div class='postbox-container' style='width:65%;'>
        <div id='normal-sortables' class='meta-box-sortables'>
            <div id="dashboard_right_now" class="postbox " >
                 
                <h3 class='hndle'><span><?php _e('EDIT QUESTION','evr_language');?></span></h3>
                 <div class="inside">
                    <div class="padding">
                    <?php
                    $questions = $wpdb->get_results ( "SELECT * from ".get_option('evr_question')." where id = $question_id" );
            			     if ($questions) {
            			         foreach ( $questions as $question ) { ?>
                                 <form name="newquestion" method="post" action="admin.php?page=questions">
                            <input type="hidden" name="event_id" value="<?php echo $event_id;?>" />
                            	<table width="100%" cellspacing="2" cellpadding="5">
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Question','evr_language');?>:</th>
                            	<td><input name="question" type="text" id="question" size="100" value="<?php echo $question->question ;?>" /></td>
                            	</tr>
                            	
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Type','evr_language');?>:</th>
                            	<td><select name="question_type" id="question_type">
                                <option value="<?php echo $question->question_type ;?>"><?php echo $question->question_type ;?></option>
                            				<option value="TEXT"><?php _e('Text','evr_language');?></option>
                            				<option value="TEXTAREA"><?php _e('Text Area','evr_language');?></option>
                            				<option value="SINGLE"><?php _e('Single','evr_language');?></option>
                            				<option value="MULTIPLE"><?php _e('Multiple','evr_language');?></option>
                            				<option value="DROPDOWN"><?php _e('Drop Down','evr_language');?></option>
                            	</select></td>
                            	</tr>
                            	
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Selections','evr_language')?>:</th>
                            	<td><input name="values" type="text" id="values" size="50" value="<?php echo $question->response ;?>" /></td>
                            	</tr>
                            	
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Required','evr_language');?>:</th>
                            	<td><input name="required" type="checkbox" id="required" <?php if($question->required =="Y" ){echo "checked";}?>/></td>
                            	</tr>
                            	<tr valign="top">
                                <th width="33%" scope="row"><?php _e('Remark','evr_language');?>:</th>
                                <td><textarea name="remark" id="remark" rows="3" cols="98"><?php if($question->remark){echo $question->remark;}?></textarea></td>
                                </tr>
                            	</table>
                                <input type="hidden" name="sequence" value="<?php echo $question->sequence;?>"/>
                                <input type="hidden" name="question_id" value="<?php echo $question->id;?>"/>
                                
                            				
                            	<?php		
                            				echo "<input type='hidden' name='event_name' value='" . $event_name . "'>";
                                            echo "<input type='hidden' name='action' value='update'>";
                            				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
                            	?>	
                            	
                            	<p><input type="submit" name="Submit" value="<?php _e('UPDATE QUESTION','evr_language');?>" /></p>
                            </form>
                            
                            
                            <?php
                            }}
                           else {
                            ?>
                            <font color="red"><?php _e('Please select a question to edit!','evr_language');?></font>
                            <?php } ?>
                    
                            
   
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