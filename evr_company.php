<?php
function evr_admin_company(){
  /**
     * Creates HTML for the Administration page to set options for this plugin.
     **/
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'event-registration'));
        }
        ?>
        <div>
        	<a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>img/evr_icon.png" alt="Event Registration for Wordpress" /></a>
        </div>
        <?php
         if (isset($_POST['update'])){$update = $_POST['update'];}
        else {$update = '';}
        switch ($update) {
        case "general" : 
    	    if ($_POST['company_name'] !=""){
                foreach($_POST as $key=>$val ) {
                    add_evr_option($key, $val);
                } 
               $start_of_week                        = $_POST['start_of_week'];
               update_option( 'evr_start_of_week', $start_of_week);
               $dwolla_enabled                    = $_POST['enable_dwolla'];
               update_option('evr_dwolla',$dwolla_enabled);
        	echo '<div id="message" class="updated fade"><p><strong>';
            _e('Configuration settings saved to options','evr_language');
               echo '</p></strong></div>';}
               else { ?>
               <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print $wpdb->last_error; ?>.</strong></p>
               <?php } ?>
                <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
        <?php 
        break;
        case "calendar" : 
            	    if ($_POST['update'] =="calendar"){
                foreach($_POST as $key=>$val ) {
                    add_evr_option($key, $val);
                } 
               //$start_of_week                        = $_POST['start_of_week'];
               //update_option( 'evr_start_of_week', $start_of_week);
               //$dwolla_enabled                    = $_POST['enable_dwolla'];
               //update_option('evr_dwolla',$dwolla_enabled);
        	   echo '<div id="message" class="updated fade"><p><strong>';
               _e('Configuration settings saved to options','evr_language');
               echo '</p></strong></div>';}
            else { ?>
               <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print $wpdb->last_error; ?>.</strong></p>
               <?php } ?>
                <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
        <?php 
        break;
        case "payment" : 
    	    if ($_POST['update'] =="payment"){
                foreach($_POST as $key=>$val ) {
                    add_evr_option($key, $val);
                } 
               //$start_of_week                        = $_POST['start_of_week'];
               //update_option( 'evr_start_of_week', $start_of_week);
               //$dwolla_enabled                    = $_POST['enable_dwolla'];
               //update_option('evr_dwolla',$dwolla_enabled);
        	   echo '<div id="message" class="updated fade"><p><strong>';
               _e('Configuration settings saved to options','evr_language');
               echo '</p></strong></div>';}
            else { ?>
               <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print $wpdb->last_error; ?>.</strong></p>
               <?php } ?>
                <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
        <?php 
        break;
        case "mail" : 
    	    if ($_POST['update'] =="mail"){
                foreach($_POST as $key=>$val ) {
                    add_evr_option($key, $val);
                } 
        	   echo '<div id="message" class="updated fade"><p><strong>';
               _e('Configuration settings saved to options','evr_language');
               echo '</p></strong></div>';}
            else { ?>
               <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print $wpdb->last_error; ?>.</strong></p>
               <?php } ?>
                <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
        <?php 
        break;
        case "misc" : 
    	    if ($_POST['update'] =="misc"){
                foreach($_POST as $key=>$val ) {
                    add_evr_option($key, $val);
                } 
        	   echo '<div id="message" class="updated fade"><p><strong>';
               _e('Configuration settings saved to options','evr_language');
               echo '</p></strong></div>';}
            else { ?>
               <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print $wpdb->last_error; ?>.</strong></p>
               <?php } ?>
                <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
        <?php 
        break;
        
        default:
            evr_createCompanyArray();
       ?> 
        <script type="text/javascript">
        	jQuery(function() {
        		jQuery("#plugin_config_tabs").tabs();
        	});
        </script>
        <!--Used for Payment Tab-->
        <?php global $company_options;?>
                    <script type='text/javascript'> 
                    var vendor = "<?php echo $company_options['payment_vendor'];?>";
                        jQuery(document).ready(function () {
                        jQuery('.block').hide();
                        jQuery('#'+vendor).show();
                        jQuery('#payment_vendor').change(function () {
                            jQuery('.block').hide();
                            jQuery('#'+jQuery(this).val()).show();
                           // alert('Value change to ' + jQuery(this).attr('value'));
                        });
                    });
        </script>
        <script type="text/javascript">
jQuery(document).ready(function() {
 // hides the slickbox as soon as the DOM is ready
  jQuery('#toggle-search').hide();
 // toggles the slickbox on clicking the noted link
  jQuery('a#slick-slidetoggle').click(function() {
	jQuery('#toggle-search').slideToggle(400);
	return false;
  });
});
</script>
        <div class="plugin_config">
        	<div id="plugin_config_tabs">
        		<ul>
        			<li><a href="#plugin_config-1">General Settings</a></li>
        			<li><a href="#plugin_config-2">Calendar Settings</a></li>
        			<li><a href="#plugin_config-3">Payment Settings</a></li>
                    <li><a href="#plugin_config-4">Email Settings</a></li>
                    <li><a href="#plugin_config-5">Misc. Settings</a></li>
        		</ul>
        		<div id="plugin_config-1">
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                        <div class="error"><p><?php _e('You must click the Save Changes button at the bottom of this tab for changes on this tab to take effect.','');?></p></div>
                        <?php evr_adminGeneral();?>
                        <input type="hidden" name="update" value="general"/>
                        <br />
                        <input  type="submit" name="update_button" value="<?php _e('Save Changes','evr_language'); ?>" id="update_button" class="button button-primary"/>
                    </form>
        		</div>
        		<div id="plugin_config-2">
        			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                        <div class="error"><p><?php _e('You must click the Save Changes button at the bottom of this tab for changes on this tab to take effect.','');?></p></div>
                        <?php evr_adminCalendar(); ?>
                        <input type="hidden" name="update" value="calendar"/>
                        <br />
                        <input  type="submit" name="update_button" value="<?php _e('Save Changes','evr_language'); ?>" id="update_button" class="button button-primary"/>
                    </form>
        		</div>
        		<div id="plugin_config-3">
        			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                        <div class="error"><p><?php _e('You must click the Save Changes button at the bottom of this tab for changes on this tab to take effect.','');?></p></div>
                        <?php evr_adminPayments(); ?>
                        <input type="hidden" name="update" value="payment"/>
                        <br />
                        <input  type="submit" name="update_button" value="<?php _e('Save Changes','evr_language'); ?>" id="update_button" class="button button-primary"/>
                    </form>
        		</div>
        		<div id="plugin_config-4">
        			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                        <div class="error"><p><?php _e('You must click the Save Changes button at the bottom of this tab for changes on this tab to take effect.','');?></p></div>
                        <?php evr_adminMail();?>
                        <input type="hidden" name="update" value="mail"/>
                        <br />
                        <input  type="submit" name="update_button" value="<?php _e('Save Changes','evr_language'); ?>" id="update_button" class="button button-primary"/>
                    </form>
        		</div>
        		<div id="plugin_config-5">
        			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                        <div class="error"><p><?php _e('You must click the Save Changes button at the bottom of this tab for changes on this tab to take effect.','');?></p></div>
                        <?php evr_adminMisc();?>
                        <input type="hidden" name="update" value="misc"/>
                        <br />
                        <input  type="submit" name="update_button" value="<?php _e('Save Changes','evr_language'); ?>" id="update_button" class="button button-primary"/>
                    </form>
        		</div>
        	</div>
        </div>
        <?php
        }
        evr_adminHelp();
}
?>