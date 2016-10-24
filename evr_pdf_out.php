<?php

/**
 * @author David Fleming
 * @copyright 2011
 */

//require files
    require_once("../../../wp-config.php");
    require('fpdf.php');
    global $wpdb, $company_options; 
       
    class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P', $unit='mm', $size='A4')
{
    // Call parent constructor
    $this->FPDF($orientation,$unit,$size);
    // Initialization
    $this->B = 0;
    $this->I = 0;
    $this->U = 0;
    $this->HREF = '';
}

function Header()
{
    // Logo
    $this->Image('images/pdffoot1.png',10,6,60);
    $this->Ln(20);
}

function WriteHTML($html)
{
    // HTML parser
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            // Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            // Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                // Extract attributes
                $a2 = explode(' ',$e);
                $tag = strtoupper(array_shift($a2));
                $attr = array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])] = $a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    // Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF = $attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    // Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF = '';
}

function SetStyle($tag, $enable)
{
    // Modify style and select corresponding font
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
        if($this->$s>0)
            $style .= $s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    // Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}
   
       
       
       
       
       
       $reg_form = unserialize(urldecode($_POST["reg_form"]));
       $attendee_array = unserialize($_POST['attendee_list']);
       echo $_POST['attendee_list'];
       break;

                            $event = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". get_option('evr_event') ." WHERE id = %d",$reg_form['event_id']));

                            
                            
$item_order = unserialize($reg_form['tickets']);
        
 $invoice = '<br><br><br><b><u>Registration Details:</u></b>'.
 '<br><br><b>Event Name/Cost:</b> '.$event->event_name.
 '<br><br><b>Attendee Name:</b> '.$reg_form['fname'].' '.$reg_form['lname'].
 '<br><br><b>Email Address:</b> '.$reg_form['email'].
 '<br><br><b>Number of Attendees:</b> '.$reg_form['num_people'].
 '<br><br><b><u>Order Details:</u></b><br><br>';
  $row_count = count($item_order);
    for ($row = 0; $row < $row_count; $row++) {
    if ($item_order[$row]['ItemQty'] >= "1"){ $invoice .= $item_order[$row]['ItemQty']." ".$item_order[$row]['ItemCat']."-".$item_order[$row]['ItemName']." ".$item_order[$row]['ItemCurrency'] . " " . $item_order[$row]['ItemCost']."<br>";}
    }
    $invoice .='     <br><br><b>Total:</b> '.$item_order[0]['ItemCurrency'].' '.$reg_form['payment'];
    $invoice .='<br><br><b><u>Attendee List:</b></u><br><br>';
     
        foreach($attendee_array as $ma) {
            $invoice .= $ma["first_name"].' '.$ma["last_name"].'<br/>';}
     
     //$company_options = get_option('evr_company_settings');
        
    $company = stripslashes($company_options['company']);
    $co_address = stripslashes($company_options['company_street1']).', '.stripslashes($company_options['company_street2']).', '
    .stripslashes($company_options['company_city']).', '.$company_options['company_state'].', '.$company_options['company_postal'];
    
    $page_title = "Registration Summary: ".stripslashes($event->event_name);
        
     /*       $pdf->SetFont( 'Arial', 'B', 20 );
            $pdf->Write( 12, $company );
            $pdf->Ln( 12 );
            $pdf->SetFont( 'Arial', 'B', 18 );
            $pdf->Write( 12, $page_title );
            $pdf->Ln( 6 );

        
            $name = $reg_form['fname']." ".$reg_form['lname'];
            
            $pdf->Ln( 16 );
            $pdf->SetFont( 'Arial', '', 12 );
            $pdf->Write( 6, $name );
            $pdf->Ln( 5 );
            $pdf->Write( 6, $invoice); 
*/
$html = $invoice;
$pdf = new PDF();



$pdf->AddPage();
 $pdf->SetFont( 'Arial', 'B', 16 );
            $pdf->Write( 12, $company );
            $pdf->Ln( 12 );
            $pdf->SetFont( 'Arial', 'B', 14 );
            $pdf->Write( 12, $page_title );
            $pdf->Ln( 6 );
//$pdf->SetLink($link);
//$pdf->Image('logo.png',10,12,30,0,'','http://www.fpdf.org');
$pdf->SetFont('Arial','',20);
$pdf->SetLeftMargin(45);
$pdf->SetFontSize(12);
$pdf->WriteHTML($html);

            
            //open on screen
            //$pdf->Output();
    
            //force file download
            $download = str_replace(" ","_",$event->event_name);
            $download = str_replace("\"","_",$event->event_name);
            $filedownload = $download.".pdf";
            $pdf->Output($filedownload, "D" );

?>