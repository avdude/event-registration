<?php
function evr_admin_questions(){
    
    $action = (string)@$_REQUEST['action'];
    
    switch ($action){
		case "new" :
        evr_return_question_button();
        
        
        evr_questions_new();
       
        evr_questions_reorder();
        
        break;


        case "post":
         evr_post_question();
         //evr_check_form_submission();
        break;
        
        case "delete":
         evr_delete_question();
         //evr_check_form_submission();
        break;
        case "edit":
        
         evr_questions_edit();
         evr_return_question_button();
         evr_questions_reorder();
         //evr_check_form_submission();
        break;
        case "update":
        evr_post_update_question();
        break;
        
		case "reorder" :
            evr_questions_reorder();
        break;  
        
        case "post_reorder" :
            evr_post_question_order();
        break;        
        
        
        default:
        evr_questions_default();
    }
}
?>