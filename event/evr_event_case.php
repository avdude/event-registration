<?php
function evr_admin_events(){
    $action = evr_issetor($_REQUEST['action']);
    switch ($action) {
     case "new":
        evr_new_event();
        
     break;
     case "edit":
        evr_edit_event();
     break;
     
 
     case "copy_event":
        evr_copy_event();
        
        
     break; 
     case "delete":
        evr_delete_event();
     break;
    case "post":
        evr_post_event_to_db();
     break;
     case "update":
           evr_post_update_event();
           //evr_check_form_submission();
     break;
     case "details":
          evr_event_details(); 
     break;     
    //item/cost components 
      case "add_item":
        evr_add_item1();
      break; 
      case "edit_item":
      evr_edit_item();
      break; 
      case "post_item";
      evr_post_item_to_db();
      //evr_check_form_submission();
      break;
      case "update_item";
      evr_update_item();
      //evr_update_post_item();
      //evr_check_form_submission();
      break;
      case "delete_item";
      evr_delete_item();
      break;
     case "reorder_item":
     evr_reorder_items();
     break;
     case "post_reorder_item";
     evr_post_update_item_order();
     break;
     case "update_coupon":
     evr_post_update_coupon();
     break;
     default:
        evr_event_listing();
        }
}
?>