<?php

/**
 * @author David Fleming
 * @copyright 2010
 */


##Set the number of future days for upcoming events listing##
$future_days = "60";

/****Function to return a prefix which will allow the correct , placement of arguments into the query string. ***/

function evr_permalink_prefix(){
  if (is_home()) { 
    $p_link = get_bloginfo('url'); 
    if ($p_link[strlen($p_link)-1] != '/') { $p_link = $p_link.'/'; }
  } else { 
    $p_link = get_permalink(); 
  }

  if (!(strstr($p_link,'?'))) { $link_part = $p_link.'?'; } else { $link_part = $p_link.'&'; }

  return $link_part;
}

function evr_permalink($page_id){
  if (is_home()) { 
    $p_link = get_bloginfo('url'); 
    if ($p_link[strlen($p_link)-1] != '/') { $p_link = $p_link.'/'; }
  } else { 
    $p_link = get_permalink($page_id); 
  }

  if (!(strstr($p_link,'?'))) { $link_part = $p_link.'?'; } else { $link_part = $p_link.'&'; }

  return $link_part;
}
/******** Configure the "Next" link in the calendar  *************/

function evr_next_link($cur_year,$cur_month){
  $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
  $next_year = $cur_year + 1;

  if ($cur_month == 12){
      $next_links =  '<a href="' . evr_permalink_prefix() . 'month=jan&amp;yr=' . $next_year . '">'.strtoupper(__('Jan','evr_language')).' &raquo;</a>'.
                     '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                     '<a href="' . evr_permalink_prefix() . 'month=feb&amp;yr=' . $next_year . '">'.strtoupper(__('Feb','evr_language')).' &raquo;</a>';
  }
  else if ($cur_month == 11){
    $next_links = '<a href="' . evr_permalink_prefix() . 'month=dec&amp;yr=' . $cur_year . '">'.strtoupper(__('Dec','evr_language')).' &raquo;</a>'.
                  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                  '<a href="' . evr_permalink_prefix() . 'month=jan&amp;yr=' . $next_year . '">'.strtoupper(__('Jan','evr_language')).' &raquo;</a>';
    }
  else{
      $next_month = $cur_month + 1;
      $next_next_month = $cur_month + 2;
      $month = $mod_rewrite_months[$next_month];
      $month_after = $mod_rewrite_months[$next_next_month];
      
      $next_links =  '<a href="' . evr_permalink_prefix() . 'month='.$month.'&amp;yr=' . $cur_year . '">'.strtoupper($month).' &raquo;</a>'.
                     '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                     '<a href="' . evr_permalink_prefix() . 'month='.$month_after.'&amp;yr=' . $cur_year . '">'.strtoupper($month_after).' &raquo;</a>';
  }
  return $next_links;
}

/*********  Configure the "Previous" link in the calendar  **************/

function evr_prev_link($cur_year,$cur_month){
  $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
  $last_year = $cur_year - 1;

  if ($cur_month == 1){
      $prev_links =  '<a href="' . evr_permalink_prefix() . 'month=nov&amp;yr='. $last_year .'">&laquo; '.strtoupper(__('Nov','evr_language')).'</a>'.
                     '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                     '<a href="' . evr_permalink_prefix() . 'month=dec&amp;yr='. $last_year .'">&laquo; '.strtoupper(__('Dec','evr_language')).'</a>';
  }else if ($cur_month == 2){
      $prev_links = '<a href="' . evr_permalink_prefix() . 'month=dec&amp;yr='. $last_year .'">&laquo; '.strtoupper(__('Dec','evr_language')).'</a>'.
                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                    '<a href="' . evr_permalink_prefix() . 'month=jan&amp;yr=' . $cur_year . '">'.strtoupper(__('Jan','evr_language')).' &raquo;</a>';
  }else {
      $prev_month = $cur_month - 1;
      $prev_prev_month = $cur_month - 2;
      $month = $mod_rewrite_months[$prev_month];
      $prev_month = $mod_rewrite_months[$prev_prev_month];
      
      $prev_links = '<a href="' . evr_permalink_prefix() . 'month='.$prev_month.'&amp;yr=' . $cur_year . '">&laquo; '.strtoupper($prev_month).'</a>'.
                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                    '<a href="' . evr_permalink_prefix() . 'month='.$month.'&amp;yr=' . $cur_year . '">&laquo; '.strtoupper($month).'</a>';
  }
  return $prev_links;
}

/******************************* Display the Calendar in a page **************************/
/*
function evr_calendar_page($content){
	if (preg_match('{EVR_CALENDAR}',$content)){
      $cal_output = evr_display_calendar();
      $content = str_replace('{EVR_CALENDAR}',$cal_output,$content);
  }
  return $content;
}
*/


function evr_calendar_replace($content){
			  if (preg_match('{EVR_CALENDAR}',$content))
			    {
			      	ob_start();
					evr_display_calendar(); //function with main content
					$buffer = ob_get_contents();
					ob_end_clean();
					$content = str_replace('{EVR_CALENDAR}',$buffer,$content);
			    }
			  return $content;
		}


// Function to provide time with WordPress offset, localy replaces time()
function evr_time_offset(){
  return (time()+(3600*(get_option('gmt_offset'))));
}

// Setup comparison functions for building the calendar later
function evr_month_compare($month){
  $current_month = strtolower(date("M", evr_time_offset()));
  if (isset($_GET['yr']) && isset($_GET['month'])){
      if ($month == $_GET['month']){
	  			return ' selected="selected"';
			}
  }
  elseif ($month == $current_month){
      return ' selected="selected"';
  }
}

function evr_year_compare($year){
  $current_year = strtolower(date("Y", evr_time_offset()));
  if (isset($_GET['yr']) && isset($_GET['month'])){
      if ($year == $_GET['yr']){
	  			return ' selected="selected"';
			}
  }
  else if ($year == $current_year){
      return ' selected="selected"';
  }
}


// Function to indicate the number of the day passed, eg. 1st or 2nd Sunday
function evr_np_of_day($date){
  $instance = 0;
  $dom = date('j',strtotime($date));
  if (($dom-7) <= 0) { $instance = 1; }
  else if (($dom-7) > 0 && ($dom-7) <= 7) { $instance = 2; }
  else if (($dom-7) > 7 && ($dom-7) <= 14) { $instance = 3; }
  else if (($dom-7) > 14 && ($dom-7) <= 21) { $instance = 4; }
  else if (($dom-7) > 21 && ($dom-7) < 28) { $instance = 5; }
  return $instance;
}

function evr_display_calendar(){
   global $wpdb,$week_no, $company_options;
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
    
    
if ($cal_head_clr != ""){    ?>
<style type="text/css">
     .calendar-date-switcher {background-color:<?php echo $cal_head_clr;?>;color: <?php echo $cal_head_txt_clr;?>;}
</style>
    <?php } 
if ($cal_day_clr != ""){?>
<style type="text/css">
    .current-day { background-color:<?php echo $cal_day_clr;?>;color: <?php echo $cal_day_txt_clr;?>;}
</style>
    <?php } 
if ($cal_day_hdr_clr != ""){?>
<style type="text/css">

.normal-day-heading { background-color:<?php echo $cal_day_hdr_clr;?>;color: <?php echo $cal_day_hdr_txt_clr;?>;}
.weekend-heading { background-color:<?php echo $cal_day_hdr_clr;?>;color: <?php echo $cal_day_hdr_txt_clr;?>;}

</style>
    <?php }       
    
?>
<style>
.catgry h3 {
	/* class title */
	font-weight: normal;
	font-size: .90em;
	margin: 0;
	padding: 2px 2px 2px 4px;
}</style>
<?php
    unset($week_no);
    if (get_option('evr_start_of_week') == 0){
				$name_days = array(1=>__('Sun','evr_language'),__('Mon','evr_language'),__('Tue','evr_language'),__('Wed','evr_language'),__('Thu','evr_language'),__('Fri','evr_language'),__('Sat','evr_language'));
    }
    else{
				$name_days = array(1=>__('Mon','evr_language'),__('Tue','evr_language'),__('Wed','evr_language'),__('Thu','evr_language'),__('Fri','evr_language'),__('Sat','evr_language'),__('Sun','evr_language'));
    }
    $name_months = array(1=>__('January','evr_language'),__('February','evr_language'),__('March','evr_language'),__('April','evr_language'),__('May','evr_language'),__('June','evr_language'),__('July','evr_language'),__('August','evr_language'),__('September','evr_language'),__('October','evr_language'),__('November','evr_language'),__('December','evr_language'));

    if (empty($_GET['month']) || empty($_GET['yr'])){
        $c_year = date("Y",evr_time_offset());
        $c_month = date("m",evr_time_offset());
        $c_day = date("d",evr_time_offset());
    }
if ((int)@$_GET['yr'] <= 3000 && (int)@$_GET['yr'] >= 0 && (int)@$_GET['yr'] != 0){
        if ((string)@$_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec'){

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

    $calendar_body = '<table class="calendar-table" id="calendar-table" >';
    
    //$date_switcher="true";
    if ($date_switcher == 'Y'){
        
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
            '.__('Year','evr_language').': <select name="yr" style="width:90px;">';

				$past = 1;
				$future = 5;
				$fut = 1;
                $p='';
                $f='';
       
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
  
      	$calendar_body .= '
                    <tr>
                    <td colspan="2" class="calendar-prev">' . evr_prev_link($c_year,$c_month) . '</td>
                    <td colspan="3" class="calendar-month">'.$name_months[(int)$c_month].' '.$c_year.'</td>
                    <td colspan="2" class="calendar-next">'. evr_next_link($c_year,$c_month) .'</td>
                    </tr>';

    $calendar_body .= '<tr>';
    for ($i=1; $i<=7; $i++) {
				if (get_option('evr_start_of_week') == 0){
	    			$calendar_body .= '<td class="'.($i<7&&$i>1?'normal-day-heading':'weekend-heading').'">'.$name_days[$i].'</td>';
	  		}
				else{
	    			$calendar_body .= '<td class="'.($i<6?'normal-day-heading':'weekend-heading').'">'.$name_days[$i].'</td>';
	  		}
    }
    $calendar_body .= '</tr>';

    for ($i=1; $i<=$days_in_month;){
        $calendar_body .= '<tr>';
        for ($ii=1; $ii<=7; $ii++){
           // $go = true;
           
            if ($ii==$first_weekday && $i==1){
								$go = TRUE;
	      		}
            elseif ($i > $days_in_month ) {
								$go = FALSE;
	      		}
            if ($go) {
								$grabbed_events = "";
                                $grabbed_non_events = "";
                                if (get_option('evr_start_of_week') == 0){
     		    						$grabbed_events = evr_fetch_events($c_year,$c_month,$i);
                                             //added for non-events
                                        //$grabbed_non_events = evr_fetch_non_events($c_year,$c_month,$i);
     		    						$no_events_class = '';
     		    						if ((!count($grabbed_events))&&(!count($grabbed_non_events))){
     				                          $no_events_class = ' no-events';
     		      					  }
     		      					else {
     						                   $no_events_class = ' events';
     		      					}
     		    						
                                              $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?'current-day':'day-with-date').$no_events_class
                                              .'"><span '.($ii<7&&$ii>1?'':'class="weekend"').'>'.$i++.'</span><span class="event"><br />' . evr_show_events($grabbed_events);
                                              //evr_show_non_events($grabbed_non_events)
                                              $calendar_body .= '</span></td>';
		  					      }
								else {
								  $grabbed_events = evr_fetch_events($c_year,$c_month,$i);
                                          $no_events_class = '';
                                          if ((!count($grabbed_events))&&(!count($grabbed_non_events))){ $no_events_class = ' no-events'; }
                                          else { $no_events_class = ' events'; }
                                          $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?'current-day':'day-with-date').
                                            $no_events_class.'"><span '.($ii<6?'':'class="weekend"').'>'.$i++.'</span><br/><span class="event">' . evr_show_events($grabbed_events);
                                           //$calendar_body .=  evr_show_non_events($grabbed_non_events);
                                           $calendar_body .= '</span></td>';
                                          }
               } else { $calendar_body .= ' <td class="day-without-date">&nbsp;</td>'; }
        }
        $calendar_body .= '</tr>';
    }

  global $wpdb, $company_options;
  //$company_options = get_option('evr_company_settings');  
  $cal_use_cat = $company_options['evr_cal_use_cat']; 
  
    if ($cal_use_cat == 'Y'){
                $sql = "SELECT * FROM ". get_option('evr_category') ." ORDER BY id ASC";
                $rows = $wpdb->get_results( $sql );
                                    if ($rows){
                                    	foreach ($rows as $category){
                                       		$category_id= $category->id;
                                        	$category_name=stripslashes(htmlspecialchars_decode($category->category_name));
                                        	$category_identifier=stripslashes(htmlspecialchars_decode($category->category_identifier));
                                        	$category_desc=stripslashes(htmlspecialchars_decode($category->category_desc));
                                        	$display_category_desc=$category->display_desc;
                                            $category_color = $category->category_color;
                                            $font_color = $category->font_color;
                                          
              $calendar_body .= '<td colspan="1" style="background-color:'.$category_color.';font-size:0.9em; color:'.$font_color.'; ">'.$category_name.'</td>';
        }}

    }
    $calendar_body .= '</table>';
    $calendar_body .= evr_colorbox_cal_content();
   
   echo $calendar_body;
   
    return $calendar_body;
}

/************************    Display the events  *********************************/

function evr_show_events($events){
  usort($events, "evr_evr_time_cmp");
  $output = '';
  foreach($events as $event){
      $output .= evr_show_event($event).'<br />';
  }
  return $output;
}
function evr_show_non_events($events){
  usort($events, "evr_evr_time_cmp");
  $output = '';
  foreach($events as $event){
      $output .= evr_show_non_event($event).'<br />';
  }
  return $output;
}

function evr_show_event($event){
    
  global $wpdb, $company_options;
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
    /*
    
  $cal_head_clr = $company_options['cal_head_clr'];
  $cal_day_clr = $company_options['cal_day_clr'];
  $cal_use_cat = $company_options['evr_cal_use_cat']; 
  $cal_pop_brdr_clr = $company_options['cal_pop_brdr_clr'];
  */
                                    
  //$show_cat = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='enable_categories'",0,0);
    $show_cat= $cal_use_cat;
  if ($show_cat == 'Y'){
      $cat_array = unserialize($event->category_id);
      $cat_id = $cat_array[0];
      $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='".$cat_id."'";
      $cat_details = $wpdb->get_row($sql);
      if ($cat_details !=""){ 
        $style = "background: white; border: 2px solid ".stripslashes($cat_details->category_color)."; "; 
        $edge=$cat_details->category_color;
      } else { 
        $style = 'background: white; border: 2px solid '.$cal_pop_brdr_clr.';'; 
        $edge=$cal_pop_brdr_clr;
      }
      
  }
  else{
      if ($cal_pop_brdr_clr !=""){
        $style = 'background: white; border: 2px solid '.$cal_pop_brdr_clr.';'; 
        $edge=$cal_pop_brdr_clr;}
      else {
        $style = "background: white; border: 2px solid #2BB0D7;"; 
        $edge='#b8ced6';
        }
  }
  
    $num = 0;                              
    $sql2= "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id='$event->id'";
    $attendee_count  = $wpdb->get_var($sql2);
    If ($attendee_count >= 1) {$num = $attendee_count;}
    If ($num < $event->reg_limit) {$available = $event->reg_limit - $num;} else {$available = 0;}
    If ($available >= 1){$seats = $available." Seats";}
    If ($available == 0){$seats = "Event Full";}
    
    

 
  if ($event->more_info != '') { 
  		$linky = stripslashes($event->more_info); 
  }
  else { 
  		
        $linky = evr_permalink($company_options['evr_page_id'])."action=evregister&event_id=".$event->id;   
  }
    $details = '<div class = "catgry" style="border-left: solid 3px '.$edge.';">';

    $details .= '<a class="inline" href="#event_content_'.$event->id.'">';
    if (strlen(html_entity_decode($event->event_name))>30){ 
    $details .= evr_truncateWords(stripslashes(html_entity_decode($event->event_name)), 5, "...");
    } else {$details .= stripslashes(html_entity_decode($event->event_name));}
    $details .=  '</a>';
    $details .= '<br/>'.$seats;
    $details.='</div>';

  return $details;
}
#Used for colorbox popup with event details.
function evr_colorbox_cal_content(){
    global $wpdb,$evr_date_format, $company_options;
    #retrieve company and configuration settings
    //$company_options = get_option('evr_company_settings');
    $curdate = date ( "Y-m-j" );
    # Get events that end date is later than today and order by start date
    //$sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
    $sql = "SELECT * FROM " . get_option('evr_event')." WHERE str_to_date(end_date, '%Y-%m-%e') >= DATE_SUB(CURDATE(),INTERVAL 30 DAY) ORDER BY str_to_date(start_date, '%Y-%m-%e')";
    $rows = $wpdb->get_results( $sql );
    
    if ($rows){
        $listing = "";
        foreach ($rows as $event){
            $current_dt= date('Y-m-d H:i',current_time('timestamp',0));
            if ($event->close == "start"){$close_dt = $event->start_date." ".$event->start_time;}
            else if ($event->close == "end"){$close_dt = $event->end_date." ".$event->end_time;}
            else if ($event->close == ""){$close_dt = $event->start_date." ".$event->start_time;}
            $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
            $expiration_date = strtotime($stp);
            $today = strtotime($current_dt);

//echo "The current date and time is: ".$current_dt."<br/>";
//echo "Registration closes at: ". $stp."<br/>";                              



            $listing .=  '<div style="display:none;"><div id="event_content_'.$event->id.'" style="padding:10px; background:#fff;">';
            $listing .= '<div id="evr_pop_top"><span style="float:center;">';
             if ($event->header_image != ""){ 
             
                $listing .= '<img class="evr_pop_hdr_img" src="'.$event->header_image.'" />';
                } 
             $listing .='</span></div>';
             $listing .='<div id="evr_pop_title"><span style="float:left;"><h3>';
             $listing .=stripslashes(html_entity_decode($event->event_name)).'</h3></span>';
             $listing .='<span style="float:right;"><a href="'.EVR_PLUGINFULLURL.'evr_ics.php?event_id='.$event->id.'">';
             $listing .='<img src="'.EVR_PLUGINFULLURL.'images/ical-logo.jpg" /></a></span></div>';
             $listing .='<div id="evr_pop_date_row" class="evr_pop_date">';
             $listing .='<br/>'.date($evr_date_format,strtotime($event->start_date)).'  -  ';
             if ($event->end_date != $event->start_date) {
                $listing .= date($evr_date_format,strtotime($event->end_date));
                }
             $listing .= __('Time: ','evr_language').' '.$event->start_time." - ".$event->end_time;
             $listing .='</div><div class="evr_spacer"></div><div id="evr_pop_body" STYLE="text-align: justify;white-space:pre-wrap;">';
             $listing .=html_entity_decode($event->event_desc);
             $listing .='</div><div id="evr_pop_image">';
             if ($event->image_link !=""){
                $listing .='<img class="evr_pop_img" src="'.$event->image_link.'" alt="Thumbnail Image" />';
                } 
                else { 
                    $listing .= '<img class="evr_pop_img" src="'.EVR_PLUGINFULLURL.'images/event_icon.png" />';
                    } 
             $listing .='</div><div class="evr_spacer"><hr /></div><div id="evr_pop_venue"><div id="evr_pop_address"><b><u>';
             $listing .= __('Location','evr_language').'</u></b><br/><br/>';
             $listing .= stripslashes($event->event_location).'<br/>'.$event->event_address.'<br/>';
             $listing .= $event->event_city.', '.$event->event_state.' '.$event->event_postal.'<br/></div><div id="evr_pop_map">';
             if ($event->google_map == "Y"){
                $listing .='<img border="0" src="http://maps.google.com/maps/api/staticmap?center=';
                $listing .=$event->event_address.",".$event->event_city.",".$event->event_state;
                $listing .='&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|';
                $listing .=$event->event_address.",".$event->event_city.'&sensor=false" />';
                }
             $listing .='</div></div><div id="evr_pop_price"><hr /><b><u>';
             $listing .=__('Event Fees','evr_language').':</u></b><br /><br />';
             #Get Event Fees         
                        $curdate = date("Y-m-d");
             $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event->id. " ORDER BY sequence ASC";
             $rows = $wpdb->get_results( $sql );
            
             if ($rows){
                foreach ($rows as $fee){
                                $item_custom_cur = $fee->item_custom_cur;
                                if ($fee->item_custom_cur == "GBP"){$item_custom_cur = "&pound;";}
                                if ($fee->item_custom_cur == "USD"){$item_custom_cur = "$";}
                                $listing .= $item_custom_cur.' '.$fee->item_price.'   '.$fee->item_title.'<br />';
                                } 
                                }
                        
             $listing .='</div><div class="evr_spacer"></div><div id="evr_pop_foot"><p align="center">';
             
             if ($expiration_date <= $today){
                $alert = '<br/><font color="red">';
                $alert .= __('Registration is closed for this event.','evr_language');
                $alert .= '<br/>';
                $alert .= __('For more information or questions, please email: ','evr_language');
                $alert .= '</font><a href="mailto:'.$company_options['company_email'].'">'.$company_options['company_email'].'</a>';
                $listing .= $alert;
                }
                else { 

             if ($event->more_info !=""){
                $listing .='<input type="button" onClick="window.open(\''.$event->more_info.'\');" value="'.__('MORE INFO','evr_language').'"/>';
                }
             if ($event->outside_reg == "Y"){
                $listing .='<input type="button" onClick="window.open(\''.$event->external_site.'\');" value="'.
                __('External Registration','evr_language').'"/>'; 
             	}  
                else { 
                    $listing .= '<input type="button" onClick="location.href=\''.evr_permalink($company_options['evr_page_id']).
                    'action=evregister&event_id='.$event->id.'\'" value="'.
                __('REGISTER','evr_language').'"/>'; 
                    }}
             $listing .= '</p></div>';
             
             $listing .= '</div></div>';
            }
            }
            return $listing;
}



function evr_show_non_event($event){
    
  global $wpdb, $company_options;
  //$company_options = get_option('evr_company_settings');  
 /* $cal_head_clr = $company_options['cal_head_clr'];
  $cal_day_clr = $company_options['cal_day_clr'];
  $cal_use_cat = $company_options['cal_use_cat']; 
  $cal_pop_brdr_clr = $company_options['cal_pop_brdr_clr'];
  */
  
    $cal_head_clr = $company_options['evr_cal_head'];
    $cal_head_txt_clr = $company_options['cal_head_txt_clr'];
    $cal_use_cat = $company_options['evr_cal_use_cat']; 
    $cal_pop_brdr_clr = $company_options['evr_cal_pop_border'];
    $cal_day_clr = $company_options['evr_cal_cur_day'];
    $cal_day_txt_clr =  $company_options['cal_day_txt_clr'];
    $date_switcher = $company_options['evr_date_select'];
    $cal_day_hdr_clr = $company_options['evr_cal_day_head'];
    $cal_day_hdr_txt_clr = $company_options['cal_day_head_txt_clr'];
  
                                    
  //$show_cat = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='enable_categories'",0,0);
$show_cat= $cal_use_cat;
  if ($show_cat == 'Y'){
      $cat_array = unserialize($event->category_id);
      $cat_id = $cat_array[0];
      $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='".$cat_id."'";
      $cat_details = $wpdb->get_row($sql);
      if ($cat_details !=""){ $style = "background: white; border: 2px solid ".stripslashes($cat_details->category_color)."; "; $edge=$cat_details->category_color;
          } else { $style = 'background: white; border: 2px solid '.$cal_pop_brdr_clr.';'; $edge=$cal_pop_brdr_clr;
      }
      
  }
  else{
      if ($cal_pop_brdr_clr !=""){$style = 'background: white; border: 2px solid '.$cal_pop_brdr_clr.';'; $edge=$cal_pop_brdr_clr;}
      else {$style = "background: white; border: 2px solid #2BB0D7;"; $edge='#b8ced6';}
  }
  if ($event->link != '') { 
     $linky = stripslashes($event->link); 
  }
  
    $allow = '<p><ul><li><b><strong><i>';
    $tool_desc = strip_tags(stripslashes(html_entity_decode($event->event_desc)),$allow); 
  
  $details = '<div class = "catgry" style="border-left: solid 3px '.$edge.';">';
  if ($event->use_link == 'Y') { 
  $details .='<a class="tooltip" href="'.$linky.'" style="text-decoration:none"><h3>' . stripslashes(html_entity_decode($event->event_name)) .'</h3>';
  } else {
  $details .='<a class="tooltip" > <h3 >' . stripslashes(html_entity_decode($event->event_name)) .'</h3>';
  }
  $details .='<span class="help" style ="'.$style.'">';
  $details .= '<em>'.stripslashes(html_entity_decode($event->event_name)).'</em>' .evr_clean_inside_tags($tool_desc,$allow) . '</span></a>'.
   '<p class="time">'.date(get_option('time_format'), strtotime(stripslashes($event->start_time)))."-".date(get_option('time_format'), strtotime(stripslashes($event->end_time))).'</p>'.
   '</div>';

  return $details;
}


function evr_clean_inside_tags($txt,$tags){
     
    preg_match_all("/<([^>]+)>/i",$tags,$allTags,PREG_PATTERN_ORDER);
 
    foreach ($allTags[1] as $tag){
         $txt = preg_replace("/<".$tag."[^>]*>/i","<".$tag.">",$txt);
     }
 
    return $txt;
 }

function evr_fetch_events($y,$m,$d){
    global $wpdb,$tod_no,$cal_no;
    $arr_events = array();
    $date = $y . '-' . $m . '-' . $d; 

        
    $events = $wpdb->get_results("SELECT * FROM " . get_option('evr_event'). " WHERE str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e') ORDER BY id");
    if (!empty($events)){
         foreach($events as $event){
	   					array_push($arr_events, $event);
         }
    
    }
    
   
  	return $arr_events;
}

function evr_fetch_non_events($y,$m,$d){
    global $wpdb,$tod_no,$cal_no;
    $arr_non_events = array();
    $date = $y . '-' . $m . '-' . $d; 
    
   if (get_option('evr_cal_active')=="Y"){
    $cal_events = $wpdb->get_results("SELECT * FROM " . get_option('evr_cal_tbl'). " WHERE str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e') ORDER BY id");
         if (!empty($cal_events)){
              foreach($cal_events as $cal_event){
               array_push($arr_non_events, $cal_event);
              }
         }
    }
   return $arr_non_events;
}

function evr_evr_time_cmp($a, $b){
  if ($a->event_time == $b->event_time) {
    return 0;
  }
  return ($a->event_time < $b->event_time) ? -1 : 1;
}

function evr_upcoming_events(){
  global $wpdb, $future_days;
   
  $day_count = 1;
  
  while ($day_count < $future_days+1){
	 		list($y,$m,$d) = split("-",date("Y-m-d",mktime($day_count*24,0,0,date("m",evr_time_offset()),date("d",evr_time_offset()),date("Y",evr_time_offset()))));
	 		$events = evr_fetch_events($y,$m,$d);
	 		usort($events, "evr_evr_time_cmp");
	 		if (count($events) != 0) {
	 				$output .= '<li>'.date_i18n(get_option('date_format'),mktime($day_count*24,0,0,date("m",evr_time_offset()),date("d",evr_time_offset()),date("Y",evr_time_offset())));
	 				foreach($events as $event){
	   				if ($event->event_time == '00:00:00') {
	 							$time_string = ' '.__('all day','evr_language');
	   				}else {
	 						$time_string = ' '.__('Between','evr_language').' '.date(get_option('time_format'), strtotime(stripslashes($event->start_time))).' - '.date(get_option('time_format'), strtotime(stripslashes($event->end_time)));
	   				}
          	$output .= '<ul><li>'.strip_tags($event->event_name).' ('.$time_string.')';
          	$output .= '<br />'.strip_tags($event->event_desc).'</li>';
	 					$output .= '</ul>';	 				}
                    $output .= '</li>';
	 		}
	 		$day_count = $day_count+1;
	 }
   if ($output == ''){
  		$output .=''.__('No event till now!','evr_language').'</ul>';
   }
	 $visual = '<ul>';
	 $visual .= $output;
	 $visual .= '</ul>';
	 return $visual;
}


function evr_upcoming_event_list($content){
	global $wpdb; 
  $display = "true";

  if (preg_match('{EVR_UPCOMING}',$content)){
  	  if ($display == 'true'){
      		$cal_output = '<span class="page-upcoming-events"><B>Upcoming Events:</B><br />'.evr_upcoming_events().'</span>';
      		$content = str_replace('{EVR_UPCOMING}',$cal_output,$content);      	
  		}else{
  			 $content = str_replace('{EVR_UPCOMING}','',$content); 
  		}
  }
  return $content;
}

?>
