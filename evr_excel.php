<?php
function evr_excel($event_id){
global $wpdb;
$today = date('j F Y'); 
$events_answer_tbl = get_option('evr_answer');
$events_question_tbl = get_option('evr_question');
$events_detail_tbl = get_option('evr_event');
$events_attendee_tbl = get_option('evr_attendee');
$events_payment_tbl = get_option('evr_payment');
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 300); //300 seconds = 5 minutes
     if ( isset($_POST['report']) && check_admin_referer( 'reporting', 'report_nonce' )) {
        $action = $_REQUEST['action'];
        $event_id = $_REQUEST['id'];
        $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$event_id));
        switch ((string)@$_REQUEST['action']) {
	           case "attendee":
                //create filename for excel export  
                 $file = urlencode(stripslashes($event->event_name));
                 $filename = $file."-Attendees_". $today . ".xls";
                 //strings used for excel layout
            $st = "";
			$et = "\t";
			$s = $et . $st;
	        //Base array for cell column headings
            if ($event->waiver == "Y"){
                $basic_header = array('Reg ID', 'Reg Date','Type','Agreed to Waiver','Last Name', 'First Name', 'Attendees', 'Email', 'Address', 
					'City', 'State', 'Zip', 'Phone','Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal','Num People', 'Payment','Tickets');
                    }
                    else {
                       $basic_header = array('Reg ID', 'Reg Date','Type','Last Name', 'First Name', 'Attendees', 'Email', 'Address', 
					'City', 'State', 'Zip', 'Phone','Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal','Num People', 'Payment','Tickets'); 
                    }
            $question_sequence = array();
			$qry = "select question, sequence from ".$events_question_tbl." where event_id = '$event_id' order by sequence" ;
            $questions = $wpdb->get_results( $qry );
            if ($questions){
            	foreach ($questions as $question){
            		array_push($basic_header, $question->question);
				    array_push($question_sequence, $question->sequence);	
            	}
            }
        		//start file header information
                 /* $filename = sanitize_file_name(get_bloginfo('name') ) . '.' . $ext;
                  if ( $ext == 'xls' ) {
                    header("Content-type: application/vnd.ms-excel;");
                  } elseif( $ext == 'xlsx') {
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, charset=utf-8;");
                  }
                  */
                  header("Content-Disposition: attachment; filename=" . $filename);
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, charset=utf-8;");
        		  header("Content-Type: application/vnd.ms-excel");
        		  header("Pragma: no-cache"); 
        		  header("Expires: 0"); 
                  echo implode($s, $basic_header) . $et . "\r\n";
                  $rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_attendee') ." WHERE event_id = $event_id" );
                    if ($rows){
                        foreach ($rows as $participant){
                    	                echo $participant->id
                                        . $s . $participant->date
                                        . $s . $participant->reg_type;
                                        if ($event->waiver =="Y"){echo $s.$participant->waiver_agree;}
                                        echo $s . $participant->lname
                                        . $s . $participant->fname;
                                        //list all attendee names                   
                                        $attendee_array = unserialize($participant->attendees);
                                        if ( count($attendee_array)>"0"){
                                                    $attendee_names="";
                                                    $i = 0;
                                                     do {
                                                        $attendee_names .= $attendee_array[$i]["first_name"]." ".$attendee_array[$i]['last_name'].", ";
                                                     ++$i;
                                                     } while ($i < count($attendee_array));
                                                }
                                        //gather remaining attendee info 
                                        echo   $s . $attendee_names
                                        . $s . $participant->email
                                        . $s . $participant->address
                                        . $s . $participant->city
                                        . $s . $participant->state
                                        . $s . $participant->zip
                                        . $s . $participant->phone
                                             . $s . $participant->company
                                             . $s . $participant->co_address
                                             . $s . $participant->co_city
                                             . $s . $participant->co_state
                                             . $s . $participant->co_zip
                                             . $s . $participant->quantity
                                        . $s . $participant->payment
                                        . $s;
                                        //Add ticke order information
                                        $ticket_order = unserialize($participant->tickets);
                                        $row_count = count($ticket_order);
                                        //echo "|* ";
                                        for ($row = 0; $row < $row_count; $row++) {
                                        echo "|* ".$ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']." *|";
                                        } 
                                        //Add Answers if Extron Quesitons
                                            $qry = "SELECT ".$events_question_tbl.".id, ".
                                                    $events_question_tbl.".sequence, ".
                                                    $events_question_tbl.".question, ".
                                                    $events_answer_tbl.".answer ".
                                                    " FROM ".$events_question_tbl.", ".$events_answer_tbl.
                                                    " WHERE ".$events_question_tbl.".id = ".$events_answer_tbl.".question_id ".
                                                    " AND ".$events_answer_tbl.".registration_id = ".$participant->id.
                                                    " ORDER by sequence";
                                            $answers = $wpdb->get_results( $qry );
                                            if ($answers){
                                            	foreach ($answers as $answer){
                                            		echo $s . $answer->answer;
                                            	}
                                            }
                                        echo $et . "\r\n";
                    	}
                    }
                  exit;
               break;
               case "payment";
                			$st = "";
                			$et = "\t";
                			$s = $et . $st;
                			$file = urlencode(stripslashes($event->event_name));
                			$filename = $file."-Payments_". $today . ".xls";
                           $basic_header = array('Participant ID', 'Name (Last, First)', 'Email', 'Registration Type','# Attendees', 'Order Total', 'Balance Due', 'Order Details','Payment Details' );
                		  header("Content-Disposition: attachment; filename=\"$filename\"");
                		  header("Content-Type: application/vnd.ms-excel");
                		  header("Pragma: no-cache"); 
                		  header("Expires: 0"); 
                			//echo header
                			echo implode($s, $basic_header) . $et . "\r\n";
                            $participants = $wpdb->get_results( "SELECT * from $events_attendee_tbl where event_id = '$event_id' ORDER BY lname DESC" );
                            if ($participants){
                            	foreach ($participants as $participant){
                            		 echo $participant->id
                			       . $s . $participant->lname.", " . $participant->fname
                                    . $s . $participant->email
                                    . $s . $participant->reg_type
                                    . $s . $participant->quantity
                                    . $s . $participant->payment;
                                    //get balance owed
                                    $total_paid = $wpdb->get_var($wpdb->prepare("SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE payer_id=%d",$participant->id));    
                                    //$balance = "0";
                                    if( $total_paid != null){
                                    if ($participant->payment >"0"){$balance = ($participant->payment - $total_paid);}  
                                     }
                                     else {$balance = $participant->payment;}
                                      echo $s . $balance .$s;     
                                    //Get ticket details    
                                    $ticket_order = unserialize($participant->tickets);
                                    $row_count = count($ticket_order);
                                    echo "||";
                                    for ($row = 0; $row < $row_count; $row++) {
                                        echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."||";
                                        }  
                                     echo $s; 
                                     echo "||";  
                                     //Get payment details        
                                    $sql = "SELECT * from $events_payment_tbl WHERE payer_id ='$participant->id'";
                        			$payments = $wpdb->get_results( $sql );
                                    if ($payments){
                                        foreach ($payments as $payment){
                                           echo  $payment->mc_currency." ".$payment->mc_gross." ".$payment->txn_type." ".$payment->txn_id." (".$payment->payment_date.")"."||";
                                        }
                                    }
                                    echo $et . "\r\n"; 
                                    }
                                }
                			exit;
            break;
                default:
            	_e('This Is Not A Valid Selection!','evr_language');
                exit;
               }
}
else { 
    $report_url = admin_url( 'admin.php?page=excel&noheader=true', '' ); ?>
<div class="wrap">
<h2 style="font-family: segoe;"><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL; ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h1><?php _e('Reports','evr_language');?></h1>
<br />
<h2>You must select an event before requesting the report!</h2>
<br />
    <form name="export" action="<?php echo $report_url;?>" method="post" onsubmit="return validate_form();">
    <SELECT name="id">
    <OPTION value="">Choose an Event . . .</OPTION>
    <?php
    $rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                          if ($rows){
                            foreach ($rows as $event){
                                        $event_id       = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                                        echo '<OPTION VALUE="'.$event_id.'">'.$event_name.'</OPTION>';
                                        }
                            }
    ?>
    </SELECT>
    <input type="hidden" name="action" value="attendee"/>
    <?php wp_nonce_field( 'reporting','report_nonce' ); ?>
    <button type="Submit" class="button-primary" name="report" ><i class='fa fa-file-excel-o'></i> Download Attendee Details in Excel Spreadsheet</button>
    </form><br /><br />
    <form name="export" action="<?php echo $report_url;?>" method="post" onsubmit="return validate_form();">
    <SELECT name="id">
    <OPTION value="">Choose an Event . . .</OPTION>
    <?php
    $rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                          if ($rows){
                            foreach ($rows as $event){
                                        $event_id       = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                                        echo '<OPTION VALUE="'.$event_id.'">'.$event_name.'</OPTION>';
                                        }
                            }
    ?>
    </SELECT>
    <?php wp_nonce_field( 'reporting','report_nonce' ); ?>
    <input type="hidden" name="action" value="payment"/>
    <button type="Submit" class='button-primary' name="report" ><i class='fa fa-file-excel-o'></i> Download Payment Records in Excel Spreadsheet</button>
    </form>
    </div> <?php
  } }
?>