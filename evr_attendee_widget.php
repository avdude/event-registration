<?php

/**
 * @author David Fleming
 * @copyright 2012
 */
add_shortcode('EVR_CUSTOM_ATTENDEE', 'evr_attendee_details');

function evr_attendee_details($atts){
    extract(shortcode_atts(array('event_id' => 'No ID Supplied','custom'=> '1','template'=>''), $atts));
	$id = "{$event_id}";
    $custom = "{$custom}";
    $template = "{$template}";
	ob_start();
    evr_advanced_attendee_list($id,$custom,$template);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function evr_advanced_attendee_list($event_id = '0', $custom, $record_template = '')
{
    global $wpdb,$company_options;    
    $curdate = date("Y-m-d");
    //$company_options = get_option('evr_company_settings');
	$category_query = '';
	//if (intval( $record_limit ) > 20) $record_limit = '20';
    //$sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() $category_query ORDER BY str_to_date(start_date, '%Y-%m-%e') LIMIT 0,".$record_limit;    
    if ($record_template == ''){
        $codeToReturn .= '<table>';
    }
    
    $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE event_id = " . $event_id. " ORDER BY lname";
    $attendees = $wpdb->get_results( $sql );
        if ($attendees){ 
             foreach ($attendees as $attendee){
	
				if ($record_template == '')
				{    
                  
					$codeToReturn .= '<tr><td>{LAST}, {FIRST}</td><td>  </td><td>{ATTENDEES}</td><td>{QA}</td></tr>';
				}
				else
				{
					$codeToReturn .= $record_template;
				}
                
                #List attendees
                $guest_list = '';
                $people = unserialize($attendee->attendees);
                if ($people){
                    foreach ($people as $person){
                       //$guest_list.= $person->first_name.' '.$person->last_name.'Here<br/>';
                       $guest_list .= $person['first_name'].' '.$person['last_name'];
                    }
                }
                
                #List ticket types
                $tickets = unserialize($attendee->tickets);
                if ($tickets){
                    foreach ($tickets as $ticket){
                        
                    }
                }
                
                #Retrieve custom questions and responses  
                $events_answer_tbl = get_option('evr_answer');
                $events_question_tbl = get_option('evr_question');
                $qry = "SELECT ".$events_question_tbl.".id, ".
                                                $events_question_tbl.".sequence, ".
                                                $events_question_tbl.".question, ".
                                                $events_answer_tbl.".answer ".
                                                " FROM ".$events_question_tbl.", ".$events_answer_tbl.
                                                " WHERE ".$events_question_tbl.".id = ".$events_answer_tbl.".question_id IN (".$custom.")".
                                                " AND ".$events_answer_tbl.".registration_id = ".$attendee->id.
                                                " ORDER by sequence";
                $quest_answers = $wpdb->get_results( $qry );
                $responses = "";     
                if ($quest_answers){
                        foreach ($quest_answers as $answer){
                            $responses .=  '<b>'.$answer->question.'</b><br/>    '.$answer->answer."<br/>";
                        }
                } 
                               
                
                #Begin to replace tags with data
                $codeToReturn = str_replace("\r\n", ' ', $codeToReturn);
                $codeToReturn = str_replace("{FIRST}", stripslashes($attendee->fname), $codeToReturn);
                $codeToReturn = str_replace("{LAST}", stripslashes($attendee->lname), $codeToReturn);
                $codeToReturn = str_replace("{NAME}", stripslashes($attendee->fname).' '.stripslashes($attendee->lname), $codeToReturn);
                $codeToReturn = str_replace("{ADDRESS}", stripslashes($attendee->address), $codeToReturn);
                $codeToReturn = str_replace("{CITY}", stripslashes($attendee->city), $codeToReturn);
                $codeToReturn = str_replace("{STATE}", stripslashes($attendee->state), $codeToReturn);
                $codeToReturn = str_replace("{ZIP}", stripslashes($attendee->zip), $codeToReturn);
                $codeToReturn = str_replace("{EMAIL}", stripslashes($attendee->email), $codeToReturn);
                $codeToReturn = str_replace("{PHONE}", stripslashes($attendee->phone), $codeToReturn);
                $codeToReturn = str_replace("{COUNT}", stripslashes($attendee->quantity), $codeToReturn);
                $codeToReturn = str_replace("{TYPE}", stripslashes($attendee->reg_type), $codeToReturn);
                $codeToReturn = str_replace("{DATE}", stripslashes($attendee->date), $codeToReturn);
                $codeToReturn = str_replace("{ATTENDEES}", $guest_list, $codeToReturn);
                $codeToReturn = str_replace("{QA}", $responses, $codeToReturn);
                
                               
        }
        #Close table is not a custom template
                if ($record_template == ''){
                        $codeToReturn .= '</table>';
                    }
    }
    echo $codeToReturn;
}
?>