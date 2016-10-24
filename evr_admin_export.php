<?php

//verifies you came from the admin panel of the site
if ($_REQUEST['key'] != '5678'){
        echo "Failure!!";
        exit;
    }

//vefies that the event_id is really a number
(is_numeric($_REQUEST['id'])) ? $event_id = $_REQUEST['id'] : $event_id = "0";
//if event_id is not a number quit
if ($event_id == '0'){
    echo "Invalid attempt";
    exit;}

//error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

function _e($text,$lang){
    echo $text;
}


 

$info = base64_decode(($_POST['info']));
$coninfo = array();
$coninfo = unserialize($info);
    $dbuser = $coninfo["dbuser"];
    $pass   = $coninfo["dbpass"];  
    $db     = $coninfo["db"];
    $host   = $coninfo["host"]; 

$tables = base64_decode(($_POST['tables']));
$tblname = array();
$tblname = unserialize($tables);

$events_answer_tbl = $tblname["evr_answer"];
$events_question_tbl = $tblname["evr_question"];
$events_detail_tbl = $tblname["evr_event"];
$events_attendee_tbl = $tblname["evr_attendee"];
$events_payment_tbl = $tblname["evr_payment"];


$today = date("Y-m-d_Hi",time()); 
/*
 $DB = new evr_DBConfig();
 $DB -> config();
 $DB -> conn($host,$dbuser,$pass,$db);
 
*/
$mysqli = new mysqli($host, $dbuser, $pass, $db);


/*
$sql  = "SELECT event_name, waiver FROM " . $events_detail_tbl . " WHERE id='$event_id'";

$result = mysqli->query($sql);
list($event_name, $waiver) = mysql_fetch_array($result, MYSQL_NUM);
*/
//generate report switch
switch ($_REQUEST['action']) {
	case "excel";
            //strings used for excel layout
            $st = "";
			$et = "\t";
			$s = $et . $st;
	        //Base array for cell column headings
			
            if ($waiver == "Y"){
                $basic_header = array('Reg ID', 'Reg Date','Type','Agreed to Waiver','Last Name', 'First Name', 'Attendees', 'Email', 'Address', 
					'City', 'State', 'Zip', 'Phone','Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal','Num People', 'Payment','Tickets');
                    }
                    else {
                       $basic_header = array('Reg ID', 'Reg Date','Type','Last Name', 'First Name', 'Attendees', 'Email', 'Address', 
					'City', 'State', 'Zip', 'Phone','Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal','Num People', 'Payment','Tickets'); 
                    }
                    
                    
            //check for extra questions        
			$question_sequence = array();
			$qry = "select question, sequence from ".$events_question_tbl." where event_id = '$event_id' order by sequence" ;
            if ($result = $mysqli->query($qry)){}
            //get additional question for header array & question order for answers
            while ($questions = mysqli_fetch_array($results)){
             	array_push($basic_header, $questions['question']);
				array_push($question_sequence, $questions['sequence']);
 			}
          //create filename for excel export  
         $file = urlencode(stripslashes($event_name));
         $filename = $file."-Attendees_". $today . ".xls";
		//start file header information
		  header("Content-Disposition: attachment; filename=\"$filename\"");
		  header("Content-Type: application/vnd.ms-excel");
		  header("Pragma: no-cache"); 
		  header("Expires: 0"); 
        //echo colulmn heading to report output
        echo implode($s, $basic_header) . $et . "\r\n";
        //get information to complete table    
        $results = $mysqli->query("SELECT * from $events_attendee_tbl where event_id = '$event_id'");
        while($participant = mysqli_fetch_array($results)) {
					echo $participant ["id"]
					. $s . $participant ["date"]
                    . $s . $participant ["reg_type"];
                    if ($waiver =="Y"){echo $s.$participant['waiver_agree'];}
                    echo $s . $participant ["lname"]
					. $s . $participant ["fname"];
                  //list all attendee names                   
                   $attendee_array = unserialize($participant["attendees"]);
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
                    . $s . $participant["email"]
					. $s . $participant["address"]
					. $s . $participant["city"]
					. $s . $participant["state"]
					. $s . $participant["zip"]
					. $s . $participant["phone"]
                         . $s . $participant["company"]
                         . $s . $participant["co_address"]
                         . $s . $participant["co_city"]
                         . $s . $participant["co_state"]
                         . $s . $participant["co_zip"]
                         . $s . $participant["quantity"]
					. $s . $participant["payment"]
                    . $s;
                    //Add ticke order information
                    $ticket_order = unserialize($participant["tickets"]);
                    $row_count = count($ticket_order);
                    echo "||";
                    for ($row = 0; $row < $row_count; $row++) {
                    echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."||";
                    } 
                    //Add Answers if Extron Quesitons
                        $qry = "SELECT ".$events_question_tbl.".id, ".
                                $events_question_tbl.".sequence, ".
                                $events_question_tbl.".question, ".
                                $events_answer_tbl.".answer ".
                                " FROM ".$events_question_tbl.", ".$events_answer_tbl.
                                " WHERE ".$events_question_tbl.".id = ".$events_answer_tbl.".question_id ".
                                " AND ".$events_answer_tbl.".registration_id = ".$participant["id"].
                                " ORDER by sequence";
                       $results2 = $mysqli->query($qry);
                        while ($answer = mysqli_fetch_array($results2)){ echo $s . $answer["answer"];}
        echo $et . "\r\n";
        } 
            
		echo "End of Record!";
	break;
    
    case "csv";
            //strings used for excel layout
            $st = "";
			$et = ",";
            $s = $et . $st;
            //Base array for cell column headings
			$basic_header = array('Reg ID', 'Reg Date','Type','Last Name', 'First Name', 'Attendees', 'Email', 'Address', 
					'City', 'State', 'Zip', 'Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal','Phone','Num People', 'Payment','Tickets');
            //check for extra questions        
			$question_sequence = array();
			$qry = "select question, sequence from ".$events_question_tbl." where event_id = '$event_id' order by sequence" ;
            $results = mysql_query($qry);
            //get additional question for header array & question order for answers
            while ($questions = mysql_fetch_array($results)){
             	array_push($basic_header, $questions['question']);
				array_push($question_sequence, $questions['sequence']);
 			}
          //create filename for excel export  
         $file = urlencode(stripslashes($event_name));
         $filename = $file."-Attendees_". $today . ".xls";
		//start file header information
        header("Content-type: application/x-msdownload"); 
		header("Content-Disposition: attachment; filename=".$file."_".$today.".csv"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 	
		//echo colulmn heading to report output
        echo implode($s, $basic_header) . "\r\n";
        //get information to complete table    
        $results = mysql_query("SELECT * from $events_attendee_tbl where event_id = '$event_id'");
        while($participant = mysql_fetch_array($results)) {
					echo $participant ["id"]
					. $s . $participant ["date"]
                    . $s . $participant ["reg_type"]
                    . $s . $participant ["lname"]
					. $s . $participant ["fname"];
                  //list all attendee names                   
                   $attendee_array = unserialize($participant["attendees"]);
                    if ( count($attendee_array)>"0"){
                                $attendee_names='"';
                                $i = 0;
                                 do {
                                    $attendee_names .= $attendee_array[$i]["first_name"]." ".$attendee_array[$i]['last_name'].", ";
                                 ++$i;
                                 } while ($i < count($attendee_array));
                                 $attendee_names .='"';
                            }
                    //gather remaining attendee info 
					echo   $s . $attendee_names
                    . $s . $participant["email"]
					. $s . $participant["address"]
					. $s . $participant["city"]
					. $s . $participant["state"]
					. $s . $participant["zip"]
					. $s . $participant["phone"]
                         . $s . $participant["company"]
                         . $s . $participant["co_address"]
                         . $s . $participant["co_city"]
                         . $s . $participant["co_state"]
                         . $s . $participant["co_zip"]
                         . $s . $participant["quantity"]
					. $s . $participant["payment"]
                    . $s;
                    //Add ticke order information
                    $ticket_order = unserialize($participant["tickets"]);
                    $row_count = count($ticket_order);
                    echo "||";
                    for ($row = 0; $row < $row_count; $row++) {
                    echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."||";
                    } 
                    //Add Answers if Extron Quesitons
                        $qry = "SELECT ".$events_question_tbl.".id, ".
                                $events_question_tbl.".sequence, ".
                                $events_question_tbl.".question, ".
                                $events_answer_tbl.".answer ".
                                " FROM ".$events_question_tbl.", ".$events_answer_tbl.
                                " WHERE ".$events_question_tbl.".id = ".$events_answer_tbl.".question_id ".
                                " AND ".$events_answer_tbl.".registration_id = ".$participant["id"].
                                " ORDER by sequence";
                       $results2 = mysql_query($qry);
                        while ($answer = mysql_fetch_array($results2)){ echo $s . $answer["answer"];}
        echo $et . "\r\n";
        } 
            
		echo "End of Record!";
        		print $csv_output;
	break;
    
    
    	case "payment";
			$st = "";
			$et = "\t";
			$s = $et . $st;
			$file = urlencode(stripslashes($event_name));
			$filename = $file."-Payments_". $today . ".xls";
           
           
           $basic_header = array('Participant ID', 'Name (Last, First)', 'Email', 'Registration Type','# Attendees', 'Order Total', 'Balance Due', 'Order Details','Payment Details' );
			
		
		  header("Content-Disposition: attachment; filename=\"$filename\"");
		  header("Content-Type: application/vnd.ms-excel");
		  header("Pragma: no-cache"); 
		  header("Expires: 0"); 

			//echo header
			echo implode($s, $basic_header) . $et . "\r\n";
            $results = mysql_query("SELECT * from $events_attendee_tbl where event_id = '$event_id' ORDER BY lname DESC");
            while($participant = mysql_fetch_array($results)) {
                   
    			     echo $participant ["id"]
			       . $s . $participant ["lname"].", " . $participant ["fname"]
                    . $s . $participant["email"]
                    . $s . $participant ["reg_type"]
                    . $s . $participant["quantity"]
                    . $s . $participant["payment"];
                    
                    //get balance owed
                    $sql2= "SELECT SUM(mc_gross) FROM $events_payment_tbl WHERE payer_id='".$participant ["id"]."'";
                    $result2 = mysql_query($sql2);
                   	while($row = mysql_fetch_array($result2)){
					   $total_paid =  $row['SUM(mc_gross)'];
                       $balance = "0";
                       if ($participant["payment"] >"0"){$balance = ($participant["payment"] - $total_paid);
                         }  
                         
                    echo $s . $balance .$s;     
                    //Get ticket details    
                    $ticket_order = unserialize($participant["tickets"]);
                    $row_count = count($ticket_order);
                    echo "||";
                    for ($row = 0; $row < $row_count; $row++) {
                        echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."||";
                        }  
                     echo $s; 
                     echo "||";  
                     //Get payment details        
                    $sql = "SELECT * from $events_payment_tbl WHERE payer_id ='".$participant["id"]."'";
        			$result = mysql_query($sql);
                    while ($payment = mysql_fetch_assoc ($result)){
                            echo  $payment["mc_currency"]." ".$payment["mc_gross"]." ".$payment["txn_type"]." ".$payment["txn_id"]." (".$payment["payment_date"].")"."||";
                                }}
                        
            					echo $et . "\r\n"; 
                    
                }
             
			exit;


	
	default:
	_e('This Is Not A Valid Selection!','evr_language');
   exit;
}

 	$DB -> close();
 
 
class evr_DBConfig {
 
     var $host;
     var $user;
     var $pass;
     var $db;
     var $db_link;
     var $conn = false;
     var $persistant = false;
     
    public $error = false;
 
    public function config(){ // class config
         $this->error = true;
         $this->persistant = false;
     }
     
    function conn($host,$user,$pass,$db){ // connection function
         $this->host = $host;
         $this->user = $user;
         $this->pass = $pass;
         $this->db = $db;
         
        // Establish the connection.
         if ($this->persistant)
             $this->db_link = mysql_pconnect($this->host, $this->user, $this->pass, true);
         else 
            $this->db_link = mysql_connect($this->host, $this->user, $this->pass, true);
 
        if (!$this->db_link) {
             if ($this->error) {
                 $this->error($type=1);
             }
             return false;
         }
         else {
         if (empty($db)) {
             if ($this->error) {
                 $this->error($type=2);
             }
         }
         else {
             $db = mysql_select_db($this->db, $this->db_link); // select db
             if (!$db) {
                 if ($this->error) {
                     $this->error($type=2);
                 }
             return false;
             }
             $this -> conn = true;
         }
             return $this->db_link;
         }
     }
 
    function close() { // close connection
         if ($this -> conn){ // check connection
             if ($this->persistant) {
                 $this -> conn = false;
             }
             else {
                 mysql_close($this->db_link);
                 $this -> conn = false;
             }
         }
         else {
             if ($this->error) {
                 return $this->error($type=4);
             }
         }
     }
     
    public function error($type=''){ //Choose error type
         if (empty($type)) {
             return false;
         }
         else {
             if ($type==1)
                 echo "<strong>Database could not connect</strong> ";
             else if ($type==2)
                 echo "<strong>mysql error</strong> " . mysql_error();
             else if ($type==3)
                 echo "<strong>error </strong>, Proses has been stopped";
             else
                 echo "<strong>error </strong>, no connection !!!";
         }
     }
 }
?>