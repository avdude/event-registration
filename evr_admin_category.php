<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function evr_admin_categories(){
  global $wpdb, $wp_version; 
  $settings = array(
                                		'media_buttons' => false,
                                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                                		'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                                	); 
?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Category Management','evr_language');?></h2>

  <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>">
    <input type="hidden" name="action" value="add_new_category">
    <input class="button-primary" type="submit" name="Submit" value="ADD NEW CATEGORY"/>
  </form>

<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div class='postbox-container' style='width:auto;'>
        <div id='normal-sortables' class='meta-box-sortables'>
            <div id="dashboard_right_now" class="postbox " >
                 
                <h3 class='hndle'><span><?php _e('Manage Categories','evr_language');?></span></h3>
                  <div class="inside">
                    <div class="padding">
                    <?php
                    $category_action = evr_issetor($_REQUEST['action']);
                    switch ($category_action) {
        
                            case "add_new_category" : 
                                    ?>
                                     
                                    <h3 class='hndle'><span><?php _e('Add A Category','evr_language');?></span></h3>
                                      <div class="inside">
                                        <div class="padding">
                                    
                                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                                    <input type="hidden" name="action" value="add">
                                    <label><?php _e('Category Name','evr_language');?></label> <input name="category_name" size="25"/>
                                    <label><?php _e('Unique ID For Category','evr_language');?></label> <input name="category_identifier"/>
                                    <p>Select color for Calendar Display:</p>
                                        <script type="text/javascript" charset="utf-8">
                                          jQuery(document).ready(function($){
                                                   $('.color-picker').wpColorPicker();
                                          });
                                        </script>
                                       
                                        <label for="color">Category Background Color: 
                                        <input type="text" class="color-picker"  id="cat_back" name="cat_back" value="#123456"  style="width: 195px"/>
                                        <small>Click on each field to display the color picker. Click again to close it.</small></label>
                                        
                                        <div id="picker" style="margin-bottom: 1em;"></div>
                                        
                                        <p>Category Text Color: <select style="width:70px;" name='cat_text' >
                                        <option value="#000000">Black</option>
                                        <option value="#FFFFFF">White</option></select></p><br />
                                    <?php _e('Do you want to display the category description on the events page?','evr_language');?>
                                    <input type='radio' name='display_desc' value='Y'><?php _e('Yes','evr_language');?>
                                    <input type='radio' name='display_desc' checked value='N'><?php _e('No','evr_language');?>
                                    <p><?php _e('Category Description','evr_language');?><br />
                                    <?php 
                                    if (!version_compare($wp_version, '3.3', '>=')) { 
                                        the_editor('',$id='category_desc',$media_buttons=false, $extended=false);
                                       	}
                                    else { wp_editor( '', 'category_desc', $settings );}
                                    
                                    ?>
                                   </p>
                                        
                                    <p><input class="button-primary" type="submit" name="Submit" value="<?php _e('Add Category','evr_language');?>" id="add_new_category" /></p>
                                    
                                    </form>
                                    <br/><br/></div></div>
                                    <?php
                            break;
                            
                            case "edit":
                                    global $wpdb;
                                    $id=$_REQUEST['id'];
                                    $sql = "SELECT * FROM ". get_option('evr_category') ." WHERE id =".$id;
                                    
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
                                    	}
                                    }
                                    
                                    ?>
                                    <!--Add event display-->
                                     
                                    <h3 class='hndle'><span><?php _e('Edit Category','evr_language');?>:<?php echo $category_name ?></span></h3>
                                      <div class="inside">
                                        <div class="padding">
                                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
                                    <input type="hidden" name="action" value="update"/>
                                    <label><strong><?php _e('Category Name','evr_language');?>:</strong></label> <input name="category_name" size="25" value="<?php echo $category_name;?>"/>
                                    <label><strong><?php _e('Unique Category Identifier','evr_language')?>:</strong></label> 
                                    <input name="category_identifier" value="<?php echo $category_identifier;?>"/> 
                                     <p>Select color for Calendar Display:</p>
                                        <script type="text/javascript" charset="utf-8">
                                          jQuery(document).ready(function($){
                                                   $('.color-picker').wpColorPicker();
                                          });
                                        </script>
                                                                              
                                        
                                        <label for="color">Category Background Color: 
                                        
                                        <?php
                                        if ($category_color !=""){$bkgd = $category_color;}
                                        else {$bkgd = "#123456"; }
                                        ?>
                                        <input type="text" class="color-picker" id="cat_back" name="cat_back" value="<?php echo $bkgd;?>"  style="width: 195px"/>
                                        <small>Click on each field to display the color picker. Click again to close it.</small></label>
                                        
                                        
                                        
                                        <p>Category Text Color: <select style="width:70px;" name='cat_text' >
                                        <?php
                                        if ($font_color =="#000000") { ?> <option value="#000000">Black</option><?php }
                                        if ($font_color =="#FFFFFF") { ?> <option value="#FFFFFF">White</option><?php } ?>
                                        <option value="#000000">Black</option>
                                        <option value="#FFFFFF">White</option></select></p>
                                        
                                        <?php _e('Do you want to display the event description on the events page?','evr_language');?>
                                    <input type="radio" name="display_desc" value="Y" <?php if($display_category_desc=="Y"){echo "checked";};?>/><?php _e('Yes','evr_language');?>
                                    <input type="radio" name="display_desc" value="N" <?php if($display_category_desc=="N"){echo "checked";};?>/><?php _e('No','evr_language');?>
                                    <br />
                                    <p><?php _e('Category Description','evr_language');?>:<br />
                                    
                                    <?php 
                                    if (!version_compare($wp_version, '3.3', '>=')) { 
                                        the_editor($category_desc,$id='category_desc',$media_buttons=false, $extended=false);
                                       	}
                                    else { wp_editor( $category_desc, 'category_desc', $settings );}
                                    ?>
                                    
                                    </p>   
                                    <p><input class="button-primary" type="submit" name="Submit" value="<?php _e('Add Category','evr_language');?>" id="add_new_category" /></p>
                                    
                                    </form>
                                    </div>
                                    </div>
                                    <?php  
                            break;
                            
                            case "add":
                                    global $wpdb;
                                    $category_name= htmlentities2($_REQUEST['category_name']);
                                    $category_identifier = htmlentities2($_REQUEST['category_identifier']);
                                    $category_desc= htmlentities2($_REQUEST['category_desc']); 
                                    $display_category_desc=$_REQUEST['display_desc'];
                                    $category_background = $_REQUEST['cat_back'];
                                    $category_font = $_REQUEST['cat_text'];
                                    $sql=array('category_name'=>$category_name, 'category_identifier'=>$category_identifier, 'category_desc'=>$category_desc, 
                                    'display_desc'=>$display_category_desc, 'font_color'=>$category_font, 'category_color'=>$category_background); 
                                    $sql_data = array('%s','%s','%s','%s','%s','%s');
                                    
                                    if ($wpdb->insert( get_option('evr_category'), $sql, $sql_data )){?>
                                    	<div id="message" class="updated fade"><p><strong><?php _e('The category has been added.','evr_language');?></strong></p></div>
                                    <?php }else { ?>
                                    	<div id="message" class="error"><p><strong><?php _e('The category was not saved.','evr_language');?> <?php print $wpdb->last_error; ?>.</strong></p></div>
                                    <?php
                                    }
                                    echo "<META HTTP-EQUIV='refresh' content='2;URL=admin.php?page=categories'>";
                            break;
                                    	 
                            case "update":
                                    $category_id= $_REQUEST['category_id'];
                                    $category_name= htmlentities2($_REQUEST['category_name']);
                                    $category_identifier = htmlentities2($_REQUEST['category_identifier']);
                                    $category_desc= htmlentities2($_REQUEST['category_desc']); 
                                    $display_category_desc=$_REQUEST['display_desc'];
                                    $category_background = $_REQUEST['cat_back'];
                                    $category_font = $_REQUEST['cat_text'];
                                    global $wpdb;
                                    $sql=array('category_name'=>$category_name, 'category_identifier'=>$category_identifier, 'category_desc'=>$category_desc, 
                                    'display_desc'=>$display_category_desc,'font_color'=>$category_font, 'category_color'=>$category_background); 
                                    
                                    $update_id = array('id'=> $category_id);
                                    
                                    $sql_data = array('%s','%s','%s','%s','%s','%s');
                                    
                                    if ($wpdb->update( get_option('evr_category'), $sql, $update_id, $sql_data, array( '%d' ) )){?>
                                    <div id="message" class="updated fade"><p><strong><?php _e('The category has been updated.','evr_language');?></strong></p></div>
                                    <?php }else { ?>
                                    <div id="message" class="error"><p><strong><?php _e('The category was not updated.','evr_language');?> <?php print $wpdb->last_error; ?>.</strong></p></div>
                                    <?php
                    }
                                    echo "<META HTTP-EQUIV='refresh' content='2;URL=admin.php?page=categories'>";
                            break;    
                                  
                            case "delete":    
                                    
                                    global $wpdb;
                                    $id=$_REQUEST['id'];
                                    $sql = "DELETE FROM ".get_option('evr_category')." WHERE id='$id'";
                                    $wpdb->query ( $sql );
                                    echo "<div id='message' class='updated fade'><p><strong>";
                                    _e('The category has been successfully deleted.','evr_language');
                                    echo "</strong></p></div>";
                                    echo "<META HTTP-EQUIV='refresh' content='2;URL=admin.php?page=categories'>";
                            break;
                            
                            default:
                                    ?>
                                    <h3><?php _e('Current Categories','evr_language');?></h3>
                                    <form id="form1" name="form1" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                    <table class="widefat">
                                    <thead>
                                    <tr>
                                        <th><?php _e('ID','evr_language');?></th>
                                        <th><?php _e('Name ','evr_language');?></th>
                                        <th><?php _e('Identifier','evr_language');?></th>
                                        <th><?php _e('Description','evr_language');?></th>
                                        <th><?php _e('Display Description','evr_language');?></th>
                                        <th><?php _e('Shortcode','evr_language');?></th>
                                        <th><?php _e('Action','evr_language');?></th></tr>
                                    </thead>
                                        <tfoot>
                                    <tr>
                                        <th><?php _e('ID','evr_language');?></th>
                                        <th><?php _e('Name ','evr_language');?></th>
                                        <th><?php _e('Identifier','evr_language');?></th>
                                        <th><?php _e('Description','evr_language');?></th>
                                        <th><?php _e('Display Description','evr_language');?></th>
                                        <th><?php _e('Shortcode','evr_language');?></th>
                                        <th><?php _e('Action','evr_language');?></th></tr>
                                    </tfoot>
                                    <tbody>
                                    <?php 
                                    global $wpdb;
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
                                    	                 ?>
                                    <tr><td><?php echo $category_id?></td>
                                        <td><span style="<?php echo $style;?>"><?php echo $category_name?></span></td>
                                        <td><?php echo $category_identifier?></td>
                                        <td><?php echo $category_desc?></td>
                                        <td><?php echo $display_category_desc?></td>
                                        <td style="white-space: nowrap;">[EVR_CATEGORY event_category_id="<?php echo $category_identifier?>"]</td>
                                        <td><a href="admin.php?page=categories&action=edit&id=<?php echo $category_id;?>"><?php _e('EDIT','evr_language');?></a>  |
                                        <a href="admin.php?page=categories&action=delete&id=<?php echo $category_id;?>" ONCLICK="return confirm('<?php _e('Are you sure you want to delete category?','evr_language');?>')"><?php _e('DELETE','evr_language');?></a></td></tr>
                                    <?php } 
                                    		}else { 
                                    ?>
                                      <tr><td><?php _e('No Record Found!','evr_language');?></td></tr>
                                    <?php }?>
                                    </tbody></table>
                                    <?php
                            break;
                         }
                         ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful, please contribute to enable its continued development!<br />
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div>

<?php
}
?>