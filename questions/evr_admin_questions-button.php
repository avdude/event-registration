<?php

function evr_return_question_button(){
    ?>
    <div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Question Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <button  onclick="location.href='admin.php?page=questions';"><?php _e('SELECT ANOTHER EVENT','evr_language');?></button>
        </div></div><br />
        
    <?php
}
?>