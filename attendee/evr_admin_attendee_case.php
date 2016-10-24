<?php
function evr_attendee_admin(){
 $action = (string)@$_REQUEST['action'];
    switch ($action) {
      case "view_attendee":
        evr_admin_view_attendee();
        //evr_check_form_submission();
     break;
     case "add_attendee":
        evr_admin_add_attendee();
        //evr_check_form_submission();
     break;
     case "post_attendee":
        evr_post_attendee();
        //evr_check_form_submission();
     break;
     case "edit_attendee":
        evr_admin_edit_attendee();
        //evr_check_form_submission();
     break;
     case "update_attendee":
        evr_update_attendee();
        //evr_check_form_submission();
     break;
     case "delete_attendee":
        evr_delete_attendee();
        //evr_check_form_submission();
     break;
          case "delete_all_attendees":
        evr_delete_all_attendee();
        //evr_check_form_submission();
     break;
     default:
    evr_attendee_event_listing();
     //evr_admin_add_attendee();
 }      
}
?>