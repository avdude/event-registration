<?php
/**
 * @author David Fleming
 * da[RT] was here
 * @copyright 2010
 */


##Set the number of future days for upcoming events listing##
$future_days = "90";

/******************************* Display the Three Calendar in a page **************************/



function evr_mini_cal_calendar_replace($content){
			  if (preg_match('{EVR_MINI_CALENDARS}',$content))
			    {
			      	ob_start();
					//echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td>';
                         echo '<div style = "width:32%; float:left;">';
					evr_mini_cal_display_calendar(date("Y",evr_time_offset()),strtolower(date("m",evr_time_offset()))); //function with main content
					$month=strtolower(date('M',strtotime('+1 month',time())));
					$yr=date('Y',strtotime('+1 month',time()));
					echo '</div><div style = "width:2%; float:left;"></div><div style = "width:32%; float:left;">';
                         //echo '</td><td>';
					evr_mini_cal_display_calendar($month,$yr); 
					$month=strtolower(date('M',strtotime('+2 month',time())));
					$yr=date('Y',strtotime('+2 month',time()));
					echo '</div><div style = "width:2%; float:left;"></div><div style = "width:32%; float:left;">';
                         //echo '</td><td>';
					evr_mini_cal_display_calendar($month,$yr); 
					echo '</div>';
                         //echo '</td></tr></table>';
					$buffer = ob_get_contents();
					ob_end_clean();
					$content = str_replace('{EVR_MINI_CALENDARS}',$buffer,$content);
			    }
			  return $content;
		}



function evr_mini_cal_display_calendar($c_month,$c_year){
    global $wpdb,$week_no, $company_options;
    unset($week_no);
	$_GET['month']=$c_month;
	$_GET['yr']=$c_year;
    if (get_option('evr_start_of_week') == 0){
				$name_days = array(1=>__('S','evr_language'),__('M','evr_language'),__('T','evr_language'),__('W','evr_language'),__('T','evr_language'),__('F','evr_language'),__('S','evr_language'));
    }
    else{
				$name_days = array(1=>__('M','evr_language'),__('T','evr_language'),__('W','evr_language'),__('T','evr_language'),__('F','evr_language'),__('S','evr_language'),__('S','evr_language'));
    }
    $name_months = array(1=>__('January','evr_language'),__('February','evr_language'),__('March','evr_language'),__('April','evr_language'),__('May','evr_language'),__('June','evr_language'),__('July','evr_language'),__('August','evr_language'),__('September','evr_language'),__('October','evr_language'),__('November','evr_language'),__('December','evr_language'));

    if (empty($_GET['month']) || empty($_GET['yr'])){
        $c_year = date("Y",evr_time_offset());
        $c_month = date("m",evr_time_offset());
        $c_day = date("d",evr_time_offset());
    }

    if ($_GET['yr'] <= 3000 && $_GET['yr'] >= 0 && (int)$_GET['yr'] != 0){
        if ($_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec'){

               $c_year = addslashes($_GET['yr']);
               if ($_GET['month'] == 'jan') { $t_month = 1; }
               else if ($_GET['month'] == 'feb') { $t_month = 2; }
               else if ($_GET['month'] == 'mar') { $t_month = 3; }
               else if ($_GET['month'] == 'apr') { $t_month = 4; }
               else if ($_GET['month'] == 'may') { $t_month = 5; }
               else if ($_GET['month'] == 'jun') { $t_month = 6; }
               else if ($_GET['month'] == 'jul') { $t_month = 7; }
               else if ($_GET['month'] == 'aug') { $t_month = 8; }
               else if ($_GET['month'] == 'sept') { $t_month = 9; }
               else if ($_GET['month'] == 'oct') { $t_month = 10; }
               else if ($_GET['month'] == 'nov') { $t_month = 11; }
               else if ($_GET['month'] == 'dec') { $t_month = 12; }
               $c_month = $t_month;
               $c_day = date("d",evr_time_offset());
        }
        else{
               $c_year = date("Y",evr_time_offset());
               $c_month = date("m",evr_time_offset());
               $c_day = date("d",evr_time_offset());
        }
    }
    else{
        $c_year = date("Y",evr_time_offset());
        $c_month = date("m",evr_time_offset());
        $c_day = date("d",evr_time_offset());
    }

    if (get_option('evr_start_of_week') == 0){
				$first_weekday = date("w",mktime(0,0,0,$c_month,1,$c_year));
        $first_weekday = ($first_weekday==0?1:$first_weekday+1);
    }
    else{
				$first_weekday = date("w",mktime(0,0,0,$c_month,1,$c_year));
				$first_weekday = ($first_weekday==0?7:$first_weekday);
    }

    $days_in_month = date("t", mktime (0,0,0,$c_month,1,$c_year));

    $calendar_body .= '<table class="evr_mini_cal_calendar-table mainTable"  >';
    $date_switcher="false";
    if ($date_switcher == 'true'){
        
				$calendar_body .= '<tr><td colspan="7" class="calendar-date-switcher"><form method="get" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">';
				$qsa = array();
			
                //parse_str($_SERVER['QUERY_STRING'],$qsa);
                
				foreach ($qsa as $name => $argument){
	    			if ($name != 'month' && $name != 'yr'){
								$calendar_body .= '<input type="hidden" name="'.strip_tags($name).'" value="'.strip_tags($argument).'" />';
	      		}
	  		}
					
                $calendar_body .= ''.__('Month','evr_language').': <select name="month" style="width:100px;">
            <option value="jan"'.evr_month_compare('jan').'>'.__('January','evr_language').'</option>
            <option value="feb"'.evr_month_compare('feb').'>'.__('February','evr_language').'</option>
            <option value="mar"'.evr_month_compare('mar').'>'.__('March','evr_language').'</option>
            <option value="apr"'.evr_month_compare('apr').'>'.__('April','evr_language').'</option>
            <option value="may"'.evr_month_compare('may').'>'.__('May','evr_language').'</option>
            <option value="jun"'.evr_month_compare('jun').'>'.__('June','evr_language').'</option>
            <option value="jul"'.evr_month_compare('jul').'>'.__('July','evr_language').'</option> 
            <option value="aug"'.evr_month_compare('aug').'>'.__('August','evr_language').'</option> 
            <option value="sept"'.evr_month_compare('sept').'>'.__('September','evr_language').'</option> 
            <option value="oct"'.evr_month_compare('oct').'>'.__('October','evr_language').'</option> 
            <option value="nov"'.evr_month_compare('nov').'>'.__('November','evr_language').'</option> 
            <option value="dec"'.evr_month_compare('dec').'>'.__('December','evr_language').'</option> 
            </select>
            '.__('Year','evr_language').': <select name="yr" style="width:70px;">';

				$past = 30;
				$future = 30;
				$fut = 1;
				while ($past > 0){
	    			$p .= '<option value="';
	    			$p .= date("Y",evr_time_offset())-$past;
	    			$p .= '"'.evr_year_compare(date("Y",evr_time_offset())-$past).'>';
	    			$p .= date("Y",evr_time_offset())-$past.'</option>';
	    			$past = $past - 1;
	  		}
				while ($fut < $future) {
	    			$f .= '<option value="';
	    			$f .= date("Y",evr_time_offset())+$fut;
	    			$f .= '"'.evr_year_compare(date("Y",evr_time_offset())+$fut).'>';
	    			$f .= date("Y",evr_time_offset())+$fut.'</option>';
	    			$fut = $fut + 1;
	  		} 
				$calendar_body .= $p;
				$calendar_body .= '<option value="'.date("Y",evr_time_offset()).'"'.evr_year_compare(date("Y",evr_time_offset())).'>'.date("Y",evr_time_offset()).'</option>';
				$calendar_body .= $f;
    		$calendar_body .= '</select><input type="submit" value="'.__('Go','evr_language').'" /></form></td></tr>';
  	
      }
          
   //added to make calendar match large calendar
     //$company_options = get_option('evr_company_settings'); 
     $cal_head_clr = $company_options['evr_cal_head'];
     $cal_head_txt_clr = $company_options['cal_head_txt_clr'];
     $cal_use_cat = $company_options['evr_cal_use_cat']; 
     $cal_pop_brdr_clr = $company_options['evr_cal_pop_border'];
     $cal_day_clr = $company_options['evr_cal_cur_day'];
     $cal_day_txt_clr =  $company_options['cal_day_txt_clr'];
     $date_switcher = $company_options['evr_date_select'];
     $cal_day_hdr_clr = $company_options['evr_cal_day_head'];
     $cal_day_hdr_txt_clr = $company_options['cal_day_head_txt_clr'];
     ?>
     <style>
     .s2 {background-color:white;}</style>
     <?php
     
 
if ($cal_head_clr != ""){    ?>
     <style type="text/css">
          .monthYearRow {background-color:<?php echo $cal_head_clr;?>;color: <?php echo $cal_head_txt_clr;?>;}
     </style>
<?php } 
if ($cal_day_clr != ""){?>
     <style type="text/css">
         .today { background-color:<?php echo $cal_day_clr;?>;color: <?php echo $cal_day_txt_clr;?>;}
     </style>
<?php } 
if ($cal_day_hdr_clr != ""){?>
     <style type="text/css">
     .dayNamesRow { background-color:<?php echo $cal_day_hdr_clr;?>;color: <?php echo $cal_day_hdr_txt_clr;?>;}
     </style>
<?php }       
    
       
          
          
          
      	$calendar_body .= '
                    <tr>
                   
                    <td  class="monthYearText monthYearRow" colspan="7">'.$name_months[(int)$c_month].' '.$c_year.'</td>
                   
                    </tr>';

    $calendar_body .= '<tr class="dayNamesText">';
    for ($i=1; $i<=7; $i++) {
				if (get_option('evr_start_of_week') == 0){
	    			$calendar_body .= '<td class="dayNamesRow" style="width:14%;">'.$name_days[$i].'</td>';
	  		}
				else{
	    			$calendar_body .= '<td class="dayNamesRow" style="width:14%;">'.$name_days[$i].'</td>';
	  		}
    }
    $calendar_body .= '</tr>';



    for ($i=1; $i<=$days_in_month;){
        $calendar_body .= '<tr class="rows">';
        for ($ii=1; $ii<=7; $ii++){
            # GT //$go = true;
            if ($ii==$first_weekday && $i==1){
								$go = TRUE;
	      		}
            elseif ($i > $days_in_month ) {
								$go = FALSE;
	      		}
            if ($go) {
								if (get_option('evr_start_of_week') == 0){
		    						$grabbed_events = evr_fetch_events($c_year,$c_month,$i);
		    						$no_events_class = '';
		    						if (!count($grabbed_events)){
												$no_events_class = ' s2';
		      					}
		      					else{
												$no_events_class = ' s22';
		      					}
		    						
                                    $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?' today':'day-with-datedrt').$no_events_class.'">'.$i++.'<span class="evr_mini_cal_event">' . evr_mini_cal_show_events($grabbed_events) . '</span></td>';
		  					
                              }
								else{
								    
		    						$grabbed_events = evr_fetch_events($c_year,$c_month,$i);
		    						$no_events_class = '';
	            			         if (!count($grabbed_events)){
												$no_events_class = ' s2';
		      					}
		      					else{
												$no_events_class = ' s21';
		      					}
		    						
                                    $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?' today':'day-with-datedrt').$no_events_class.'">'.$i++.'<span class="evr_mini_cal_event" >' . evr_mini_cal_show_events($grabbed_events) . '</span></td>';
		  					
                              }
                           
	     			}
            else {
								$calendar_body .= ' <td class="sOther">&nbsp;</td>';
	      		}
        }
        
        $calendar_body .= '</tr>';
    }
    
    //$show_cat = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='enable_categories'",0,0);
$show_cat=false;
    if ($show_cat == 'true'){
				//Future Add
    }
    $calendar_body .= '</table>';
     echo $calendar_body;
    return $calendar_body;
}

/************************    Display the events  *********************************/

function evr_mini_cal_show_events($events){
   //If you want to pupup event info.  
  /*   
  usort($events, "evr_evr_time_cmp");
  
  foreach($events as $event){
      $output .= evr_mini_cal_show_event($event).'<hr style="clear:both;width:100%;margin:4px 0;"/>';
  }
  return $output;
  */
}

function evr_mini_cal_show_event($event){
    
  global $wpdb, $company_options;
  //$company_options = get_option('evr_company_settings');                                    
  //$show_cat = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='enable_categories'",0,0);
$show_cat="true";
  if ($show_cat == 'true'){
      $cat_array = unserialize($event->category_id);
      $cat_id = $cat_array[0];
      $cat_details = "";
      if ($cat_id!=""){
      $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id=".$cat_id;
      $cat_details = $wpdb->get_row($sql);}
      if ($cat_details !=""){ $style = "background-color:".stripslashes($cat_details->category_color)." ; color:".stripslashes($cat_details->font_color)." ;";
      } else { $style = "background-color:#F6F79B;color:"."#000000"." ;";
      }
      
  }
  else{
      $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id=1";
      $cat_details = $wpdb->get_row($sql);
      //$style = "background-color:".stripslashes($cat_details->category_color).";color:".stripslashes($cat_details->font_color)." ;";
      $style = "background-color:#F6F79B;color:"."#000000"." ;";
  }
 
  
  $header_details .=  '<span class="event-title" style="color:'."#000000".'">'.stripslashes(html_entity_decode($event->event_name));
					   
  $header_details .=  '</span><br/>';
 /* if ($event->event_time != "00:00:00"){
      $header_details .= '<span class="time"><strong>'.__('Time','evr_language').':</strong> ' . date(get_option('time_format'), strtotime(stripslashes($event->start_time))) . ' - '. date(get_option('time_format'), strtotime(stripslashes($event->end_time))) . ', '.__('In ','evr_language').''.stripslashes($event->event_location).'</span><br />';
  }
  if ($event->event_link != '') { 
  		$linky = stripslashes($event->more_info); 
  }
  else { 
  		//$linky = '#';
        $linky = evr_permalink($company_options['evr_page_id'])."action=register&event_id=".$event->id;   
  }*/
 /* evr_mini_cal add */   

$event_id       = $event->id;
 $reg_limit      = $event->reg_limit;
   $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id=%d", $event_id));
                            
                                      				
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
	//$number_attendees  
	if($reg_limit=="Unlimited")
	   $evr_mini_cal_details =  '<div class="evr_mini_cal_add_extra_nfo unlimited_seats" style="display:block;"><i>Unilimited</i></div>';
	 elseif($number_attendees==$reg_limit)  
	 $evr_mini_cal_details=  '<div class="evr_mini_cal_add_extra_nfo evr_mini_cal_waiting_list"><i>Waiting List</i></div>';
	   else $evr_mini_cal_details=  '<div class="evr_mini_cal_add_extra_nfo evr_mini_cal_nr_of_seats"><i>'.((int)$reg_limit - (int)$number_attendees ).' Seats</i></div>';
	
	/*end evr_mini_cal */	
  $details ='<span class="calnk_evr_mini_cal">' .  '<span style="'.$style.'">' . $header_details . '</span><div class="evr_mini_cal-custom-cat evr_mini_cal_custom_cat_'.$cat_id.'"></div>'. $evr_mini_cal_details.'</span>';

  return $details;
}


?>