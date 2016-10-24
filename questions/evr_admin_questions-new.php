<?php
function evr_questions_new(){
    $record_limit = "10";
    global $wpdb;
    $event_name = $_REQUEST['event_name'];
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    ?>
<div class="wrap">
<div class='postbox-container' style='width:90%;'>
               <div  class="postbox " >
                 
                <h3 class='hndle'><span><?php _e('ADD NEW QUESTION','evr_language');?> for <?php echo stripslashes($event_name);?></span></h3>
                 <div class="inside">
                    <div class="padding">
                            <form name="newquestion" method="post" action="admin.php?page=questions">
                            <input type="hidden" name="event_id" value="<?php echo $event_id;?>" />
                            	<table width="100%" cellspacing="2" cellpadding="5">
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Question','evr_language');?>:</th>
                            	<td><input name="question" type="text" id="question" size="100" value="" /></td>
                            	</tr>
                            	
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Type','evr_language');?>:</th>
                            	<td><select name="question_type" id="question_type">
                            				<option value="TEXT"><?php _e('Text','evr_language');?></option>
                            				<option value="TEXTAREA"><?php _e('Text Area','evr_language');?></option>
                            				<option value="SINGLE"><?php _e('Single','evr_language');?></option>
                            				<option value="MULTIPLE"><?php _e('Multiple','evr_language');?></option>
                            				<option value="DROPDOWN"><?php _e('Drop Down','evr_language');?></option>
                            	</select></td>
                            	</tr>
                            	
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Selections','evr_language')?>:</th>
                            	<td><input name="values" type="text" id="values" size="50" value="" /></td>
                            	</tr>

                                
                            	<tr valign="top">
                            	<th width="33%" scope="row"><?php _e('Required','evr_language');?>:</th>
                            	<td><input name="required" type="checkbox" id="required" /></td>
                            	</tr>
                            	<tr valign="top">
                                <th width="33%" scope="row"><?php _e('Remark','evr_language');?>:</th>
                                <td><textarea name="remark" id="remark" rows="3" cols="98"></textarea></td>
                                </tr>
                            	</table>
                        				
                            	<?php		
                            				echo "<input type='hidden' name='event_name' value='" . $event_name . "'>";
                                            echo "<input type='hidden' name='action' value='post'>";
                            				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
                            	?>	
                            	
                            	<p><input type="submit" name="Submit" value="<?php _e('ADD QUESTION','evr_language');?>" /></p>
                            </form>
                            <br />
                            <div>
                            <p><b>Text:</b>  This is generally what I would call short answer questions, typically consisting of a single sentence.  Where the registering person would type in their response.  You do not need to put anything in the Selections field for this question type.</p>
                            <p><b>Text Area:</b>  This is similar to the Text, except you are looking more for an paragraph/multiple sentence response.  You do not need to put anything in the Selections field for this question type.</p>
                            <p><b>Single:</b>  This will provide radio button answers where the registering person will select one of several options.  Yes/No, True/False are good examples of this type of question.  Basically a multiple choice question with one possible choice. When entering this question you will need to provide the list of choices that will appear.  Separate your choices by a comma:
True, False etc.    <font color="red">  Do not provide answer choices that have a comma in the response!</font></p>
                              <p><b>Multiple:</b>  This is similar to Single Type questions but gives them the option of selecting several of the choices instead of just selecting one item. Basically a multiple choice question with several  possible choices. When entering this question you will need to provide the list of choices that will appear.  Separate your choices by a comma:
Newspaper, Web, A Friend, Billboard  etc.  
 <font color="red">  Do not provide answer choices that have a comma in the response</font>!</p>
                              <p><b>Dropdown:</b> This question type is similar in nature to the Single, exept instead of providing the choices in radio buttons, it provides them in a drop down box.  This is handy if you have a lot of choices the person needs to choose from, it prevents them from taking up a lot space on your registration form.</p>

</div>
   
                    </div>
                </div>
            </div>
        </div>
    </div>

<br />
<?php
}
?>