=== Event Registration ===
Contributors: avdude, radices
Donate link: http://www.wpeventregister.com/donations
Tags:   event, events, event registration, events registration,events managment, event calendar
Requires at least: 3.7
Tested up to: 4.4.1
Stable tag: 6.02.02
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Event Registration is a WordPress plugin that allows you to manage events with an online and manual registration and payment process. 

== Description ==

This wordpress plugin is designed to run on a Wordpress website and provide registration events, classes, or parties. It is designed to be easy to navigate.
It allows you to capture the registering persons contact information and any additional infromation you request to a database and provides an association to an events database. 
It provides the ability to send the register to either a Paypal, Monster Pay, or Authorize.net online payment site for online collection of event fees.
Additionally it allows support for checks and cash payments.  
Optional Captcha field on registration form.
Detailed payment management system to track and record event payments and support for PayPal payment notification.  
Reporting features provide a export list(s) of events, attendees, payments in excel or csv.  

If you like the plugin and find it useful, please donate.  
Your donations help me keep it going and improve it.  
You can find online information at http://wpeventregister.com/

Also if you could rate the plugin that would also be helpful.

== Upgrading ==

It is strongly recommended that after upgrading you deactivate and reactivate the plugin a 2nd time to ensure complete data migragion.

If you are unsatisfied with the upgrade, simply deactivate and delete the plugin.  You can then download the prior version and mannually upload to your system.  
All your old data will still be in tact as the upgrade copies and creates new tables, and leaves the existing ones for easy rollback.

Please note that because of conflicts with other plugins (people copying my work!), I have changed many of the shortcodes and functions, so you will need to update all your shortcodes on your pages.

* {EVENTREGIS} and {EVRREGIS}  Now -> [EVR_EVENTS]
* [Event_Registration_Calendar] Now -> {EVR_CALENDAR}
* {EVENTREGPAY}  Now -> [EVR_PAYMENT]
* [EVENT_REGIS_CATEGORY event_category_id="??"]  Now -> [EVR_CATEGORY event_category_id="????"] where ???? is your custom identfier - see category listing.
* [Event_Registration_Single event_id="??"] Now -> [EVR_SINGLE event_id="??"] where ?? is the ID number of the event.
* [EVENT_ATTENDEE_LIST event_id="??"]  Now -> [EVR_ATTENDEE event_id="?"] where ?? is the ID number of the event.



If you are upgrading from a version prior to 6.0, I have made some major changes to the database and depending on your configuration, it may or may not upgrade the first attempt.  
I recommend activating and deactivating the new version several times. if you are having issues.


== Installation ==

= How to Use: = 

1. If you are upgrading from a previous version, please see upgrading directions.

2. Download and install the plugin.

3. Activate the plugin, then read the directions (http://wpeventregister.com/wp-content/uploads/2012/05/EventRegistrationGuide_60008a.pdf).

4. Create a new page on your site and title it Registration. On that page, in html view, put this text:   [EVR_EVENTS]  Save the page.

5. Create another page on your site and title it Calendar. On that page, in html view, put this text:  {EVR_CALENDAR}  Save the page.

6. Create another page on your side and title it Event Payments.  Make the page hidden from navigation.  On that page, in the html view, put this text:  [EVR_PAYMENT]  Save the page

7. Now you are ready to configure the plugin for operation.  To get started, once you activate the plugin, please go into the Company settings and complete the information as it is requested.  In the company information , Under page settings, the main registration page, there will be  a dropdown box, select the page you setup as Registration.
In the company information, under page settings, the events payment page, there will be a dropdown box, select the page you setup as Payment. 

8. Next go to the Category section. In the Categories section, create a few categories for your events.

9. Now you are ready to create events.  Go to the Events section and select add new event.  Complete the requested information in all tabs.  The submit button is located in the last tab.

10. Event creation and event pricing are handled as separate tasks.   You are able to create multiple pricing levels and optional pricing items with this version.  Once you have created an event, view the event listing and you will see a button to add tickets/items.  Add items such as registration fees, sales items or whatever you want to charge for or give away.  You must create an item in order to take registrations.

11. Once you have created your pricing items, you are now ready to take registrations.  Go to your registrations and click the event and try it out. 

* If you want to setup a page for a single event, use this shortcode on the page: [EVR_SINGLE event_id="??"] where ?? is the ID number of the event (you can only use one shortcode per page!)
* If you want to setup a page for a particular category of events, use this shortcode on the page: [EVR_CATEGORY event_category_id="????"] where ???? is your custom identfier - see category listing (you can only use one shortcode per page!)




== Support ==


How to Use:
Must Do! Create a new page on your site and title it Registration.
On that page, in html view, put this text:   [EVR_EVENTS]
Save the page.

Create another page on your site and title it Calendar
On that page, in html view, put this text:  {EVR_CALENDAR}

Must Do! Create another page on your side and title it Event Payments
Make the page hidden from navigation
On that page, in the html view, put this text:  [EVR_PAYMENT]

Now you are ready to configure the plugin for operation.
To get started, once you activate the plugin, please go into the Company settings and complete the information. 
In the company information , Under page settings, the main registration page, there will be  a dropdown box, select the page you setup as Registration.
In the company information, under page settings, the events payment page, there will be a dropdown box, select the page you setup as Payment.
Create a few categories for your events,  Then create a few events.  Make sure you add items to your events. Event creation and event pricing are handled as separate tasks.  You are able to create multiple pricing levels and optional pricing items with this version.


== Change Log ==

= Version 6.02.02
* Fixed currency bug in registration confirmation page
* Fixed date bug in custom event listing template
* Fixed bug in payment confirmation posting page security key

= Version 6.02.01
* Updated compatability notes

= Version 6.02.00
* Fixed bug where state was not displaying on event popup in event listing page

= Version 6.01.09
* Fixed bug in Attendee Edit where custom question answers were not saving.
* Changed color picker from Farbtastic to wp-color-picker
* Changed public style to resolve conflict with themeforest themes
* Added ability to add custom question responses to attendee confirmation emails. Use tag [custom].
* Resolved WP_Widget class error from deprecated function
* Resolved manual payment posting error
* Resolved PayPal IPN posting error
* Fixed bug where coordinator email was not defaulting to system default
* Fixed bug in adding and editing events where options did not always render
* Fixed bugs in editing/adding attendees
* Fixed bugs in editing/adding payments

= Version 6.01.08 =
* Verfied compatability with WordPress version 4.1.1
* Fixed bug in evr_public_form.php where php tag was not complete cause failure to activate on some servers.
* Fixed bug in company settings where two submit buttons on each tab
* Fixed bug in General Settings tab where help information not rendering properly
* Remove "Test" from email headers in Payment Reminder
* Fixed small issues in process confirmation script
* Removed duplicate line of code evr_public-show_event_list.php
* Resolved security hole in evr_public-show_event_list.php
* Made company options a global variable for evr_public-show_event_list.php
* Modify question table to use installer/updater function
* Updated plugin dashboard to use tabs; included quick start steps.
* Turn off error reporting causing other plugin and theme errors to be displayed


= Version 6.01.07 =
* Fixed bug in default payment on return page for payments

= Version 6.01.06 =
* Added support for location module
* Fixed bug in Event Category Display Page Content
* Fixed bug in Edit Event - Description - stripslashes 
* Fixed bug in Edit Event - Venue  Name - stripslashes
* Fixed bug in Edit Event Venue - location not displaying
* Fixed bug in Edit Event Venue - Location Module not displaying locations correctly
* Fixed bug in Edit Event Venue - Google Maps yes/no radio button selection
* Fixed bug in Edit Event - Event Name not displaying in title
* Fixed bug in Edit Event - Coordinator Module - Mail Stripslahes from mail message
* Fixed bug in Edit Event - Confirmation Mail - stripslashes from mail message

= Version 6.01.05 =
* Changed stable version in readme

= Version 6.01.04 =
* Layout Change: Custom Questions Order - fixed spacing issue to allow readability
* Bug Fix related to custom question answer table only posting 1 repsone
* Bug Fix related to paging event listing on admin pages
* Add Feature to Settings Misc. Tab to control number of events and number of attendees when paging on the admin side


= Version 6.01.03 =
* Bug Fix related to currency format with PayPal

= Version 6.01.02 = 
* Minor Bug Fix

= Version 6.00.01 =
* Too many to list in detail, but a few are:
* Improved admin Settings interface
* Improved add-on module activation (more to come!!!)
* Various bug fixes.

= Version 6.00.31 =
* Resolved issue where fees would be deleted if event was attempted to be deleted but had attendees
* Changed files for compatibility with WordPress 3.5 prepare
* evr_admin_attendee_listing.php
* evr_event_delete.php
* evr_event_listing.php
* evr_install.php
* evr_ipn.php
* evr_support_functions.php
* evr_three_cal.php
* evr_admin_payments-case.php
* evr_admin_payments-event_list.php

= Version 6.00.30 =
* fixe bug preventing posting of payments by paypal

= Version 6.00.29 =
* hover text on custom questions appearing on other questions - Resolved
* Version not showing correct on WordPress - Resolved

= Version 6.00.28 =
* Company field not displaying on registration form - Resolved
* Only 1 event displaying in event list - Resolved
* Calendar days not correct with dates - Resolved

= Version 6.00.27 =
* Compliance Update

= Version 6.00.26 =
* Resolved issue where editor windows overflowed tabs in Coordinator Module
* Removed admin registration alert
* Removed donation nag
* Resolved privacy issues
* Removed upgrade email notification
* Resolved event expiration issue in public side
* Resolved incorrect Expiration notice on admin event list
* Resolved whole number issue to allow cents on event fees
* Resolved fee popup layout issue
* Resolved image disiplacement on event popups and calendar
* Resolved Usort error on calendar
* Resolved dbdelta issue on data table upgrades
* Added support to location module
* Added remark field for hover help on questions
* Resolved fee description hover text issue on reg form
* Modified to hide text by default on ref form is "Show description on reg form" is set to no on event setup
* Resolved payment area to accurately reflect amount due and payment totals for events
* Added "Send payment reminder to all people with a balance" to the payment area

= Version 6.00.25 =
* Modified code on registration form to better display html

= Version 6.00.24 =
* Bug Fixes on registration form
* Bug fixes on confirmation, and coordinator email fields


= Version 6.00.23 =
* Added file needed for event list popup with colorbox

= Version 6.00.22 =
* Bug fix for "Warning: Missing argument 1 for evr_show_event_list()"
* Changed popup window style for event list and calendar pages

= Version 6.00.21 =
* Resolved issue where event descriptions not rendering line returns correctly

= Version 6.00.20 =
* Modified Attendee List feature for shortcode on page
* change html text to correct format for translation of plugin on many pages
* Resolved issue where attendees post twice with some themes
* Fixed htmlchanger issue
* Fixed issue with Sandbox mode for paypal
* Added field to support special characters with Paypal submission
* Added validation for Attendee Name on Confirmation page
* Simplified registration form code
* Fixed activation key issue

= Version 6.00.19 =
* Fixed slashes issue on Company default mail
* Added ability to expire events on end date instead of start date
* Fixed php tag error in evr_three_cal file.
* Fixed issue on export payment list to excel not showing payments
* Fixed issue with questions
* Fixed listing style options for categories
* Fixed calendar color coding issue
* Modified event names on calendar to truncate long names to prevent overrun of calendar.
* Resolved issue with waitlist registration
* Added help file popup to new widget.
* Changed admin popups for helps to be white background and grey screen area


= Version 6.00.18 =
* Fixed ADD FEE no content issue
* Fixed slashes in Company Name on Email
* Add patch for Coordinator Email to support custom questions	

= Version 6.00.17 =
* Fixed WordPress compliance issues
* Modified how events expire on the public side: Show on list until the end of the event, close registration form at event start time.
* Added options no not use popup - added accordian style list capability
* Changed backend popups to use thickbox
* Changed create/edit event screens to page format instead of popup format for better screen fit
* Other minor bug fixes

= Version 6.00.16 =
* Fixed bug on events not showing expired in admin panel
* Fixed bug in Category description not showing html properly

= Version 6.00.15 =
* Modified Registration form to deactivate submit button unitl item is selected
* Modified Registration form to provide visual prompt to select at lease one item
* Moved javascript from registration form to external files to avoid conflicts
* Modified confirmation page to deactivate confirmation button and provide visual prompt if no items were selected on registration form
* Modified the install script to fix error in tax field on upgrade
* Modified the install script to add fields to attendee table for company information
* Modified registration processing script to add company details to database
* Modified the calendar links to open on same page instead new page
* Modified the calendar tooltip window to striptags to display properly and avoid conflicts in description

= Version 6.00.14 =
* Reinstated Widget - Event Listing
* Resolved Authorize.Net Payment button issues
* Changed icons in Admin listings
* Resolved issue where registering 0 people
* Change admin pages to better fit various screen sizes (more to come!)
* Added more control from admin panel for event calendar style and colors
* Added sales tax capability - optional feature
* Added tax calculation if enabled to reg form.
* Resolved bug in coordinator fields
* Added copy questions when copying events
* Changed check address to optional display address line 2 - no more blank line
* Resolved attendee count/waitlist bug
* Spelling Error corrections
* Added forum feed to plugin dashboard, other dashboard changes
* Added Event Shortcode to event listing in admin panel
* Modified send mail to resolve html display issues.
* Added ability to customize waitlist message in company settings
* Resolved bug in export attendee info 
* Payment email notification layout and field changes

= Version 6.00.13 =
* various bug fixes

= Version 6.00.12 =
* Resolved issue with Coupon Code not deducting
* Resolved table format issue when upgrading EventRegistration caused by Event Expresso.
* Changed excel and CSV reporting function to ensure more universal site compatibility
* Added fields to event table for coordinator emails
* Modified upgrade of attendee to conditionalize adding columns.
* Changed WYSIWG Editor to support multiple instances on a page - events, company
* Repaired Admin style - container width for tabs 
* Added enqueue farbtastic for colorpicker
* Added text editor to category description
* Replaced background color picker for categories with larger variety
* Resolved issue prevent categories from deletion
* Modified registration form styles
* Added enqueue thickbox in public header
* Resolved strange error wher & symbol appeared randomly throughout plugin.
* Resolved wrapping issue on extra questions radio and checkbox
* Resolved global variable missing in  categgory shortcode replacement listing
* Resolved issue with adding name boxes alignment
* Resolved issue with confirmation pushing sidebar down page
* Resolved category listing display to follow same format as all event listing.
* Changed google map size, widen price list on event popup

= Version 6.00.10 =
* Fixed Issue created by 6.00.09 - all attendees go to waitlist
* Corrected spelling issue of Attendee Management

= Version 6.00.09 = 
* Resolved issue where company contact email was not working the code replace of the email text for payment recieved notice via IPN
* Resolved issue where available items was not showing properly per configuration
* Added feature to bypass event listing popup from the company settings
* changed event popup to center header image in css
* Resolved htm tag error in start of popup div
* Modified styling for popup to use CSS for layout instead of tables
* Changed public page popup from fancy box to wordpress native thickbox.
* Changed popup styling from table to CSS
* Resolved issue where item reorder was not saving new order.
* Resolved bug in Event Popup where google map always showed.  Set to conditional if Y for event to show.
* Modified google map feature to default to Yes on new event creation.  User must change to No to disable.
* Bbug fix for reg confirmation page for Waitlist, proper wording and set count to 1.
* Resolved WAITLIST screen and email confirmation bug - wrong variable for if then
* Temp fix for radio and checkbox alignment with extra questions on radio form
* Moved style from inline of event signup form to public style sheet
* Modified attendee table upgrade script for column issues.

= Version 6.00.08 = 
* Fixed systemwide misspelling of received!
* Added "Delete All Attendees for An Event" button in attendee event listing page
* Removed advertising from plugin
* fixed issue with graphic name: no.png

= Version 6.00.07 = 
* Changed DB Installation and Upgrade to simplfly and condition upgrading
* Added sponsor section
* Moved all admin scripts and style to enqueue.
* Resolved depricated options issue
* Added system alerts

= Version 6.00.06 = 
* changed DB uninstall feature from a hidden deactivate feature to a menu choice.
* Added more information and links to the splash page
* Changed logo
* Added donation links throughout

= Version 6.00.05.01 = 
* Fixed help window on Company-->Page Settings to display proper shortcode

= Version 6.00.05 = 
* added product branding
* resolved issue jquery tabs

= Version 6.00.04.01 = 
* jquery on from wordpress default all the time
* resolved issue with image folder I vs. i


= Version 6.00.04 = 
* Fixed issues with AutoIncrement new events and new attendee records
* changed textbox edit bar in add event and company settings

= Version 6.00.03 = 
* Fixed security hole in registration from data posting

= Version 6.00.02 = 
* changed jquery to use wordpress builtin jquery

= Version 6.00.01 = 
* Minor Jquery fixes

= Version 6.00.00 = 
* Add jquery popup feature to plugin

= Version 5.98 = 
* Created function to convert Event Registration data tables from version 5.43/earlier to version 5.99/6.00 format and import old data

= Version 5.97 = 
* Created language file and added ,'evr_language' to all echo'd text

= Version 5.96 = 
* Fixed mail send issue on payment IPN
* corrected typo in database name in ipn validation page
* Added help for payment confirmation email in Company Settings
* Repaired code issue where drag and drop on questions and event fees was broken
* Repaired code issue where popup for new event didnt tab - now works properly
* Added ability to edit Event Pay Button in Company Settings
* Added fname field to return payment url to reduce hackability


= Version 5.95 = 
* updated IPN link in registration paypal section to post properly.
* updated IPN link in Return to Pay for paypal
* added require file for ipn data posting file
* made changes to attendee table - added 3 columns
* Fixed IPN script to add information to payment table
* Fixed IPN script to post payment amount correctly
* Changed function name form_build and form_build_edit to evr_form_build and evr_form edit to prevent conflict with earlier versions
* Added Reg ID to attendee list display in admin panel
* fixed code bug in [id] replacement


= Version 5.94 = 
* Added Global variable $evr_date_format
* Changed date format for UK of d/m/Y i.e. 24/03/2011 for March 24, 2011 for event listings
* added [id] tag in custom email format for sending mail to attendee
* Added version number to Menu Head on sidebar
* changed moneyFormat function to evr_moneyFormat to prevent confilict with earlier versions of Event Registration
* Updated the js folder with the CSS and images for reordering items

= Version 5.93 = 
* Removed unused menu items
* Fixed Attendee Export to Excel and CSV
* Fixed Event Name display in Admin Payment Panel
* Added Export to Excel in Payment Panel
* Fixed issue where extra questions would not reorder - now drag and drop order
* Fixed issue where event ticket/prices would not reorder - now drag and drop order
* Fixed popup close button not visible on event popups
* Replaced all jquery calls to use internal wordpress Jquery instead of external jquery
* Added custom function to fix permalink issue with ? or & automatically called
* Fixed permalink for page links on regform to use permalink instead of page id.
* Fixed permalink for page links fon calendar to use permalink instead of page id.
* Fixed bug in using Single Page shortcode where event id would erase.


= Version 5.92 = 
* completed function for return payment page/shortcode [EVR_PAYMENT]
* fixed Notify/Cancel URL fields in Company Settings
* Clairified shortcode/Filter on Company settings for pages
* Changed button label on Items to Fees/Items
* fixed ics generator to support events with symbols in name.
* Fixed tooltip not working in Company tab

= Version 5.91 = 
* Added £ symbol to currency display when GBP is selected as currency
* Added $ symbol to currency display when USD is selected as currency
* fixed / escaping characters issue in event creation and editing where it was adding extra /
* added submit button disabled when submit pressed to prevent multiple submissions on same form
* changed display layout of popup window on events listing - customer side
* fixed close icon not displaying on popup window - customer side
* fixed spacing issue on event listing page for all events
* changed "Registration Form Here" to event title on registration form.


= Version 5.90 = 

--Revamped all the code to change functions names to prevent conflict with those who used my code and was too lazy to rewrite it.


. . . See changelog.txt for more changes







