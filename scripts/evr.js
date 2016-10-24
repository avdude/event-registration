//Confirm Delete
 	function confirmDelete(){
 if (confirm('Are you sure want to delete?')){
      return true;
    }
    return false;
  }
  
// Used for displaying event Popup and New event popup in admin panesls
jQuery(document).ready(function($){
	 					   		   
							   		   
	//When you click on a link with class of poplight and the href starts with a # 
	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="/wp-content/plugins/event-registration/images/btn-close.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	   
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});

	
});


//Script form fancybox configuration - used for popupt help  -- need to change to wp internal thickbox in next revision.

/*        $j = jQuery.noConflict();
        jQuery(document).ready(function($j) {
        $j("a.ev_reg-fancylink").fancybox({
        		'padding':		10,
        		'autoScale'		: true,
               'imageScale':	true,
        		'zoomSpeedIn':	250, 
        		'zoomSpeedOut':	250,
        		'zoomOpacity':	true, 
        		'overlayShow':	false,
                'width'		: 680,
                'height'		: 680,
        		
        		'hideOnContentClick': false
        	});
        });
 */
  $j = jQuery.noConflict();
        jQuery(document).ready(function($j) {
        $j("a.ev_reg-fancylink").fancybox({
        		
        	});
        });
        
        
//Script form fancybox configuration - used for popupt help  -- need to change to wp internal thickbox in next revision.
        $j = jQuery.noConflict();
        jQuery(document).ready(function($j) {
        $j("a.ev_widget-fancylink").fancybox({

        	});
        });
        

        
//used for the tabbing feature in the admin page for company settins and event configuration and event edititing

jQuery(document).ready(function() {
	//Default Action
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	jQuery("ul.tabs li").click(function() {
		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".tab_content").hide(); //Hide all tab content
		var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

});       