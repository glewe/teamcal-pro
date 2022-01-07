<?php
/**
 * english.tcpro.php
 *
 * English language file
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

global $LANG;
unset($LANG);

/**
 * Includes
 */
require_once ($CONF['app_root'] . "models/config_model.php");
$LC = new Config_model;

/**
 * Common
 */
$LANG['monthnames'] = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
$LANG['weekdays']   = array(1=>"Mo","Tu","We","Th","Fr","Sa","Su");
$LANG['weekdays_long']   = array(1=>"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
$LANG['date_picker']   = 'Date Picker...';
$LANG['date_picker_tt'] = "Click here to open the date picker dialog. The date will be shown as YYYY-MM-DD in the field on the left.";
$LANG['color_picker'] = 'Color Picker...';
$LANG['color_picker_tt'] = "Click here to open the color picker dialog. The colors will be shown as hexadecimal values in the field on the left.";

/**
 * Menu
 */
$LANG['mnu_teamcal'] = 'TeamCal';
$LANG['mnu_teamcal_login'] = 'Login';
$LANG['mnu_teamcal_register'] = 'Register';
$LANG['mnu_teamcal_logout'] = 'Logout';
$LANG['mnu_view'] = 'View';
$LANG['mnu_view_homepage'] = 'Home page';
$LANG['mnu_view_calendar'] = 'Calendar';
$LANG['mnu_view_yearcalendar'] = 'Year Calendar';
$LANG['mnu_view_announcement'] = 'Announcements';
$LANG['mnu_view_statistics'] = 'Statistics';
$LANG['mnu_view_statistics_g'] = 'Global Statistics';
$LANG['mnu_view_statistics_r'] = 'Remainder Current Year';
$LANG['mnu_tools'] = 'Tools';
$LANG['mnu_tools_profile'] = 'User Profile';
$LANG['mnu_tools_message'] = 'Message';
$LANG['mnu_tools_webmeasure'] = 'webMeasure';
$LANG['mnu_tools_admin'] = 'Administration';
$LANG['mnu_tools_admin_config'] = 'TeamCal Configuration';
$LANG['mnu_tools_admin_perm'] = "Permissions";
$LANG['mnu_tools_admin_users'] = 'Users';
$LANG['mnu_tools_admin_groups'] = 'Groups';
$LANG['mnu_tools_admin_usergroups'] = 'Group Memberships';
$LANG['mnu_tools_admin_absences'] = 'Absence Type List';
$LANG['mnu_tools_admin_absences_edit'] = 'Absence Type Edit';
$LANG['mnu_tools_admin_regions'] = 'Regions';
$LANG['mnu_tools_admin_holidays'] = 'Holiday Types';
$LANG['mnu_tools_admin_declination'] = 'Declination Management';
$LANG['mnu_tools_admin_database'] = 'Database Maintenance';
$LANG['mnu_tools_admin_systemlog'] = 'System Log';
$LANG['mnu_tools_admin_env'] = 'Environment';
$LANG['mnu_tools_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_help'] = 'Help';
$LANG['mnu_help_legend'] = 'Legend';
$LANG['mnu_help_help'] = 'User Manual';
$LANG['mnu_help_about'] = 'About TeamCal';

/**
 * Filter
 */
$LANG['nav_groupfilter'] = 'Group:';
$LANG['nav_language'] = 'Language:';
$LANG['nav_start_with'] = 'Start:';
$LANG['drop_group_all'] = 'All';
$LANG['drop_group_allbygroup'] = 'All (by group)';
$LANG['drop_show_1_months'] = 'Show 1 month';
$LANG['drop_show_2_months'] = 'Show 2 months';
$LANG['drop_show_3_months'] = 'Show 3 months';
$LANG['drop_show_6_months'] = 'Show 6 months';
$LANG['drop_show_12_months'] = 'Show 12 months';

/**
 * Buttons
 */
$LANG['btn_activate'] = "Activate";
$LANG['btn_add'] = 'Add';
$LANG['btn_apply'] = 'Apply';
$LANG['btn_assign'] = 'Assign';
$LANG['btn_assign_all'] = 'Assign All';
$LANG['btn_backup'] = 'Backup';
$LANG['btn_cancel'] = 'Cancel';
$LANG['btn_clear'] = 'Clear';
$LANG['btn_close'] = 'Close';
$LANG['btn_confirm'] = 'Confirm';
$LANG['btn_create'] = 'Create';
$LANG['btn_delete'] = 'Delete';
$LANG['btn_delete_records'] = 'Delete records';
$LANG['btn_done'] = 'Done';
$LANG['btn_edit'] = 'Edit';
$LANG['btn_edit_profile'] = 'Edit Profile';
$LANG['btn_export'] = 'Export';
$LANG['btn_help'] = 'Help';
$LANG['btn_icon'] = 'Icon...';
$LANG['btn_import'] = 'Import';
$LANG['btn_import_ical'] = 'Import iCal';
$LANG['btn_install'] = 'Install';
$LANG['btn_login'] = 'Login';
$LANG['btn_logout'] = 'Logout';
$LANG['btn_merge'] = 'Merge';
$LANG['btn_next'] = 'Next';
$LANG['btn_prev'] = 'Prev';
$LANG['btn_refresh'] = 'Refresh';
$LANG['btn_remove'] = 'Remove';
$LANG['btn_reset'] = 'Reset';
$LANG['btn_reset_password'] = 'Reset Password';
$LANG['btn_restore'] = 'Restore';
$LANG['btn_save'] = 'Save';
$LANG['btn_search'] = 'Search';
$LANG['btn_select'] = "Select";
$LANG['btn_send'] = 'Send';
$LANG['btn_submit'] = 'Submit';
$LANG['btn_switch'] = 'Switch';
$LANG['btn_update'] = 'Update';
$LANG['btn_upload'] = 'Upload';

/**
 * Calendar Display
 */
$LANG['cal_caption_weeknumber'] = 'Week';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_img_alt_edit_month'] = 'Edit holidays for this month...';
$LANG['cal_img_alt_edit_cal'] = 'Edit calendar for this person...';
$LANG['cal_birthday'] = 'Birthday';
$LANG['cal_age'] = 'Age';
$LANG['sum_summary'] = 'Summary';
$LANG['sum_present'] = 'Present';
$LANG['sum_absent'] = 'Absent';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_absence_summary'] = 'Absence Summary';
$LANG['sum_business_day_count'] = 'business days';
$LANG['remainder'] = 'Remainder';
$LANG['exp_summary'] = 'Expand Summary section...';
$LANG['col_summary'] = 'Collapse Summary section...';
$LANG['exp_remainder'] = 'Expand Remainder section...';
$LANG['col_remainder'] = 'Collapse Remainder section...';

/**
 * Calendar Edit Dialog
 */
$LANG['member_edit'] = 'Calendar of ';
$LANG['cal_clear_confirm'] = "Are you sure you want to clear all check marks?\\r\\n";
$LANG['cal_range_within'] = "Please enter a range within the year and month you are editing!";
$LANG['cal_range_start'] = "Please enter a starting range date earlier than the ending range date.";
$LANG['cal_range_title'] = "Date Range Input (within this month)";
$LANG['cal_range_type'] = "Absence Type:";
$LANG['cal_range_from'] = "From:";
$LANG['cal_range_to'] = "To:";
$LANG['cal_recurring_title'] = "Recurring Input (within this month)";
$LANG['cal_reason_title'] = "Comment/Reason (will be put in notification mails)";
$LANG['cal_reason_dummy'] = "Enter comment/reason for your request here...";

/**
 * Edit Groups Dialog
 */
$LANG['edit_groups'] = 'Edit Groups';
$LANG['column_shortname'] = 'Shortname';
$LANG['column_description'] = 'Description';
$LANG['column_hide'] = 'Hide';
$LANG['column_action'] = 'Action';
$LANG['eg_delete_confirm'] = 'Are you sure you want to delete this group?';

/**
 * Edit Absence Dialog
 */
$LANG['edit_absence'] = "Edit Absence Types";
$LANG['ea_column_name'] = "Name";
$LANG['ea_column_symbol'] = "Symbol";
$LANG['ea_column_color'] = "Text";
$LANG['ea_column_bgcolor'] = "Backg.";
$LANG['ea_column_allowance'] = "#";
$LANG['ea_column_factor'] = "*";
$LANG['ea_column_showremain'] = "R";
$LANG['ea_column_approval'] = "A";
$LANG['ea_column_icon'] = "Icon";
$LANG['ea_column_groups'] = "Groups";
$LANG['ea_column_action'] = "Action";
$LANG['ea_color_help'] = "Please enter color values in hex format, e.g. 000000. ";
$LANG['ea_color_help'] .= "The first two digits represent the color Red, the second ";
$LANG['ea_color_help'] .= "two digits represent the color Green, the last two digits ";
$LANG['ea_color_help'] .= "represent the color Blue. Combinations will mix the colors accordingly.<br><br>\r\n";
$LANG['ea_color_help'] .= "Examples: 000000 = Black, FFFFFF = White, FF0000 = Red, 00FF00 = Green, 0000FF = Blue, 888888 = Gray.<br>";
$LANG['ea_delete_confirm'] = "Are you sure you want to delete this absence type?\\r\\n";
$LANG['ea_delete_confirm'] .= "All occurrences of a deleted absence type will be\\r\\n";
$LANG['ea_delete_confirm'] .= "replaced with the absence type \'present\' for ALL users.";
$LANG['ea_groups_all'] = "All";
$LANG['ea_groups_selection'] = "Selection";
$LANG['ea_tt_upload'] = "Click here to open the upload dialog to upload image files. Absence icons will always be displayed in 16*16 pixel size. After uploading an image you need to refresh this page so it is listed in the drop down lists.";
$LANG['ea_tt_icon'] = "This is the icon of this absence type. It will be displayed in the calendar instead of its character symbol.";
$LANG['ea_tt_groups'] = "Click here to open the Assignment dialog where you can assign this absence type to certain groups only.";

/**
 * Edit Day Types Dialog
 */
$LANG['edit_daytypes'] = "Edit Day Types";
$LANG['ed_column_name'] = "Name";
$LANG['ed_column_symbol'] = "Symbol";
$LANG['ed_column_color'] = "Text";
$LANG['ed_column_bgcolor'] = "Backg.";
$LANG['ed_column_businessday'] = "Bus.Day";
$LANG['ed_column_action'] = "Action";
$LANG['ed_color_help'] = "Please enter color values in hex format, e.g. 000000. ";
$LANG['ed_color_help'] .= "The first two digits represent the color Red, the second ";
$LANG['ed_color_help'] .= "two digits represent the color Green, the last two digits ";
$LANG['ed_color_help'] .= "represent the color Blue. Combinations will mix the colors accordingly.<br>\r\n";
$LANG['ed_color_help'] .= "Examples: 000000 = Black, FFFFFF = White, FF0000 = Red, 00FF00 = Green, 0000FF = Blue, 888888 = Gray.<br>";
$LANG['ed_delete_confirm'] = "Are you sure you want to delete this day type?\\r\\n";

/**
 * Legend Dialog
 */
$LANG['teamcal_legend'] = 'TeamCal Pro Legend';
$LANG['col_month_header'] = 'Month Header';
$LANG['col_day_holidays'] = 'Holidays';
$LANG['col_day_absences'] = 'Absence types';
$LANG['dom_prefix'] = 'Day of month:';
$LANG['dow_prefix'] = 'Day of week:';
$LANG['dow_daynote'] = 'Day of week: with daynote';
$LANG['btn_edit_month'] = 'Button: Edit Month Template';
$LANG['btn_edit_member'] = 'Button: Edit Team Member Calendar';
$LANG['legend_today'] = 'Marking for Today';

/**
 * Month Dialog
 */
$LANG['month_edit'] = 'Template for ';
$LANG['month_daynote'] = 'Daynote';
$LANG['month_daynote_tooltip'] = 'Edit note of the day...';

/**
 * Daynote Dialog
 */
$LANG['daynote_edit_title'] = 'Edit Daynote for ';
$LANG['daynote_edit_title_for'] = 'for';
$LANG['daynote_edit_msg_caption'] = 'Note of the day:';
$LANG['daynote_edit_msg_hint'] = '(You may use formatting HTML tags like &lt;b&gt;.)';
$LANG['daynote_edit_msg'] = '<strong>Note of the day</strong><br>...your note here...';
$LANG['daynote_edit_event_created'] = '[CONFIRMATION]\n\nYour daynote has been created.';
$LANG['daynote_edit_event_saved'] = '[CONFIRMATION]\n\nYour daynote has been saved.';
$LANG['daynote_edit_event_deleted'] = '[CONFIRMATION]\n\nYour daynote has been deleted.';

/**
 * Icon Upload Dialog
 */
$LANG['upload_title'] = 'TeamCal Pro File Upload';
$LANG['upload_maxsize'] = 'Maximum filesize';
$LANG['upload_extensions'] = 'Allowed extensions';
$LANG['upload_file'] = 'Select file...';

/**
 * Notification Messages
 */
$LANG['notification_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Notification";
$LANG['notification_subject_approval'] = $CONF['app_name']." ".$CONF['app_version']." - Approval required";

$LANG['notification_greeting']  = "<hr>This message was automatically created by ".$CONF['app_name']." ".$CONF['app_version']." at:<br />".$CONF['app_url']."<hr>";
$LANG['notification_greeting'] .= "Hello TeamCal Pro User,<br />";

$LANG['notification_usr_msg'] = "your TeamCal Pro user profile is configured to notify you when a new user is added or an existing user profile is changed.<br /><br />";
$LANG['notification_usr_add_msg'] = "The following user has been added:<br /><br />";
$LANG['notification_usr_chg_msg'] = "Changes have been applied to the user profile of:<br /><br />";
$LANG['notification_usr_del_msg'] = "The following user has been deleted:<br /><br />";

$LANG['notification_usr_cal'] = "your TeamCal Pro user profile is configured to notify you when a user calendar has been changed.<br /><br />";
$LANG['notification_usr_cal_msg'] = "The calendar of the following user has been changed:<br /><br />";

$LANG['notification_grp_msg'] = "your TeamCal Pro user profile is configured to notify you when a new group is added or an existing group is changed.<br /><br />";
$LANG['notification_grp_add_msg'] = "The following group has been added:<br /><br />";
$LANG['notification_grp_chg_msg'] = "Changes have been applied to the group:<br /><br />";
$LANG['notification_grp_del_msg'] = "The following group has been deleted:<br /><br />";

$LANG['notification_abs_msg'] = "your TeamCal Pro user profile is configured to notify you when a new absence type is added or an exsiting one is changed.<br /><br />";
$LANG['notification_abs_add_msg'] = "The following absence type has been added:<br /><br />";
$LANG['notification_abs_chg_msg'] = "Changes have been applied to the absence type:<br /><br />";
$LANG['notification_abs_del_msg'] = "The following absence type has been deleted:<br /><br />";

$LANG['notification_hol_msg'] = "your TeamCal Pro user profile is configured to notify you when a new holiday was added or an existing one was changed.<br /><br />";
$LANG['notification_hol_add_msg'] = "The following holiday has been added:<br /><br />";
$LANG['notification_hol_chg_msg'] = "Changes have been applied to the holiday:<br /><br />";
$LANG['notification_hol_del_msg'] = "The following holiday has been deleted:<br /><br />";

$LANG['notification_month_msg'] = "your TeamCal Pro user profile is configured to notify you about changes to month templates.<br /><br />Changes were applied to the month template of:<br /><br />";

$LANG['notification_signature']  = "You can change your notification settings in your user profile in TeamCal Pro.<br /><br />";
$LANG['notification_signature'] .= "Best regards,<br />";
$LANG['notification_signature'] .= "Your TeamCal Pro Administration.<br />";

$LANG['notification_decl_msg']    = "a calendar change has been declined.<br /><br />";
$LANG['notification_decl_msg_2']  = "The following problems were found in your request:<br /><br />";
$LANG['notification_decl_user']   = "Requesting User: ";
$LANG['notification_decl_reason'] = "Reason given: ";
$LANG['notification_decl_sign']   = "<br /><br />If you are the requesting user you may contact your group manager so he can confirm and enter the absence.<br />";
$LANG['notification_decl_sign']  .= "If you are the group manager of the affected group you may contact the requesting user for clarification.<br /><br />";
$LANG['notification_decl_sign']  .= "Best regards,<br />";
$LANG['notification_decl_sign']  .= "Your TeamCal Pro Administration.<br />";

/**
 * Login Dialog
 */
$LANG['login_login'] = 'TeamCal Pro Login';
$LANG['login_username'] = 'Username:';
$LANG['login_password'] = 'Password:';
$LANG['login_error_0'] = 'Login successful';
$LANG['login_error_1'] = 'Username or password missing';
$LANG['login_error_2'] = 'Username unknown';
$LANG['login_error_3'] = 'This account is locked or not approved. Please contact your administrator.';
$LANG['login_error_4a'] = 'Password incorrect. This was bad attempt number ';
$LANG['login_error_4b'] = ' . After ';
$LANG['login_error_4c'] = ' bad attempts your account will be locked for ';
$LANG['login_error_4d'] = ' seconds.';
$LANG['login_error_6a'] = 'This account is on hold due to too many bad login attempts. The grace period ends in ';
$LANG['login_error_6b'] = ' seconds.';
$LANG['login_error_7'] = 'Password incorrect';
$LANG['login_error_8'] = 'Account not verified. You should have received an e-mail with a verification link.';
$LANG['login_error_91'] = 'LDAP error: Password missing';
$LANG['login_error_92'] = 'LDAP error: Authentication failed';
$LANG['login_error_93'] = 'LDAP error: Unable to connect to LDAP server';
$LANG['login_error_94'] = 'LDAP error: Start TLS failed';
$LANG['login_error_95'] = 'LDAP error: Username not found';
$LANG['login_error_96'] = 'LDAP error: Search bind failed';

/**
 * Status Bar
 */
$LANG['status_logged_in'] = "You are logged in as ";
$LANG['status_logged_out'] = "You are not logged in. Viewing and editing might be restricted.";
$LANG['status_ut_user'] = "Regular User";
$LANG['status_ut_manager'] = "Manager of group: ";
$LANG['status_ut_director'] = "Director";
$LANG['status_ut_assistant'] = "Assistant";
$LANG['status_ut_admin'] = "Administrator";

/**
 * User Profile Dialog
 */
$LANG['view_profile_title'] = 'View User Profile';
$LANG['edit_profile_title'] = 'Edit User Profile';
$LANG['add_profile_title'] = 'Add User Profile';
$LANG['tab_personal_data'] = 'Personal&nbsp;Data';
$LANG['tab_membership'] = 'Membership';
$LANG['tab_membership_group'] = 'Group';
$LANG['tab_membership_member'] = 'Member';
$LANG['tab_membership_manager'] = 'Manager';
$LANG['tab_options'] = 'Options';
$LANG['tab_privileges'] = 'Account';
$LANG['tab_absences'] = 'Absences';
$LANG['show_profile_uname'] = 'Username:';
$LANG['show_profile_password'] = 'New&nbsp;Password:';
$LANG['show_profile_verify_password'] = 'Verify&nbsp;Password:';
$LANG['show_profile_name'] = 'Name:';
$LANG['show_profile_lname'] = 'Last Name:';
$LANG['show_profile_fname'] = 'First Name:';
$LANG['show_profile_usertitle'] = 'Title:';
$LANG['show_profile_position'] = 'Position:';
$LANG['show_profile_idnumber'] = 'ID-Number:';
$LANG['show_profile_email'] = 'e-mail:';
$LANG['show_profile_birthday'] = "Birthday:";
$LANG['show_profile_birthday_format'] = "(Format: YYYY-MM-DD)";
$LANG['show_profile_gender'] = "Gender:";
$LANG['show_profile_male'] = "Male";
$LANG['show_profile_female'] = "Female";
$LANG['show_profile_phone'] = 'Phone:';
$LANG['show_profile_mobile'] = 'Mobile:';
$LANG['show_profile_group'] = 'Group:';
$LANG['show_profile_sendmail'] = 'Send an e-mail to this user:';
$LANG['show_profile_subject'] = 'Subject:';
$LANG['show_profile_message'] = 'Message:';
$LANG['show_profile_from'] = 'TeamCal Pro - Show Profile Message';
$LANG['show_profile_msgsent'] = 'Your message was sent.';
$LANG['user_delete_confirm'] = "Are you sure you want to delete the selected user(s)?\\r\\n";
$LANG['frame_user_type'] = "User&nbsp;Type";
$LANG['ut_caption'] = "Grant privileges by assigning a special user type";
$LANG['ut_user'] = "Regular User (Member and/or Manager)";
$LANG['ut_admin'] = "Administrator";
$LANG['ut_director'] = "Director";
$LANG['frame_personal_details'] = "Personal Details";
$LANG['frame_user_groupmember'] = "Group&nbsp;Membership";
$LANG['frame_user_status'] = "User&nbsp;Status";
$LANG['us_caption'] = "Set user status";
$LANG['us_locked'] = "Account locked";
$LANG['us_logloc'] = "Login On Hold";
$LANG['us_hidden'] = "Hide user in calendar";
$LANG['frame_mail_notification'] = "e-mail&nbsp;Notification";
$LANG['show_absence'] = "Absence&nbsp;Counts";
$LANG['show_absence_from'] = "Show absence counts from";
$LANG['show_absence_to'] = "to";
$LANG['show_absence_type'] = "Absence";
$LANG['show_absence_lastyear'] = "Last Year";
$LANG['show_absence_allowance'] = "Allowance";
$LANG['show_absence_taken'] = "Taken";
$LANG['show_absence_factor'] = "Factor";
$LANG['show_absence_remainder'] = "Remainder";
$LANG['frame_uo'] = "Options";
$LANG['uo_caption'] = "Select miscellaneous options";
$LANG['uo_owngroupsonly'] = "Show own groups only";
$LANG['uo_showbirthday'] = "Show birthday in calendar";
$LANG['uo_ignoreage'] = "Ignore year of birth (age)";
$LANG['uo_notifybirthday'] = "Notify user about other birthdays";
$LANG['uo_language'] = "Default Language";
$LANG['uo_defgroup'] = "Default Group Filter";
$LANG['error_password_mismatch'] = "Your new password and verify password do not match.";
$LANG['error_user_exists'] = "That username is already taken. Please choose another one.";
$LANG['error_user_nospecialchars'] = "No special characters are allowed in usernames.\\nPlease choose another one.";
$LANG['profile_updated'] = "The profile has been updated.";
$LANG['user_add_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Your Account";
$LANG['user_add_greeting']  = "------------------------------------------------------------\n";
$LANG['user_add_greeting'] .= "This message was automatically created by:\n";
$LANG['user_add_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['user_add_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['user_add_greeting'] .= "Welcome to TeamCal Pro!\n";
$LANG['user_add_greeting'] .= "Congratulations, you have been added and registered as a user in TeamCal Pro.\n\n";
$LANG['user_add_info_1']    = "How to get to TeamCal Pro:\n";
$LANG['user_add_info_1']   .= $CONF['app_url']."\n\n";
$LANG['user_add_info_1']   .= "Click on the [Login] button and use your account credentials:\n";
$LANG['user_add_info_1']   .= "Your username: ";
$LANG['user_add_info_2']    = "\nYour password: ";
$LANG['user_add_info_3']    = "\n\nYour TeamCal Pro Administration\n";
$LANG['tab_avatar']    = "Avatar";
$LANG['ava_title']    = "Avatar";
$LANG['ava_upload']    = "Upload an image from your hard drive. Allowed are images of the type JPG, GIF and PNG, not bigger than 250 KB with a maximum size of ".$LC->readConfig("avatarWidth")."*".$LC->readConfig("avatarHeight")." pixels.";
$LANG['ava_wrongtype_1']    = "Wrong file type: ";
$LANG['ava_wrongtype_2']    = "Allowed are the file types ";
$LANG['ava_write_error']    = "An error occurred while writing the avatar file to its destination.";
$LANG['ava_upload_error']    = "An unspecified error occurred during upload. Maybe you want to double-check the size and type of the file you just uploaded.";
$LANG['ava_upload_error_1']    = "The file is too big. It exceeds the upload_max_filesize directive in php.ini.";
$LANG['ava_upload_error_2a']    = "The file is too big. The upload file size is limited to ";
$LANG['ava_upload_error_2b']    = " bytes.";
$LANG['ava_upload_error_3']    = "The uploaded file was only partially uploaded.";
$LANG['ava_upload_error_4']    = "No file was uploaded.";
$LANG['notify_caption'] = 'I want to be notified by e-mail when:';
$LANG['notify_team'] = 'A user is added or changed';
$LANG['notify_groups'] = 'A group is added or changed';
$LANG['notify_month'] = 'A month template is changed';
$LANG['notify_absence'] = 'An absence type is added or changed';
$LANG['notify_holiday'] = 'A holiday type is added or changed';
$LANG['notify_usercal'] = 'A user calendar is changed';
$LANG['notify_ofgroup'] = 'of group';

/**
 * Admin Pages
 */
$LANG['admin_user_user'] = 'User';
$LANG['admin_user_attributes'] = 'Attributes';
$LANG['admin_user_lastlogin'] = 'Last Login';
$LANG['admin_user_action'] = 'Action';
$LANG['admin_user_title'] = 'Manage Users';
$LANG['tt_user_logloc'] = "This user reached the maximum amount of failed logins. His account is currently on hold for a grace period.";
$LANG['tt_user_locked'] = "This user is locked or not approved. The administrator can unlock this user by editing his profile.";
$LANG['tt_user_hidden'] = "This user is hidden from the calendar. The administrator can unhide this user by editing his profile.";
$LANG['tt_user_verify'] = "This user has not yet verified his account.";
$LANG['admin_group_title'] = 'Manage Groups';
$LANG['admin_absence_title'] = 'Manage Absence Types';
$LANG['admin_holiday_title'] = 'Manage Holiday Types';
$LANG['admin_help_title'] = 'Help';
$LANG['admin_create_new_user'] = 'Create a new user...';
$LANG['admin_import_user'] = 'Import user(s) from CSV file...';
$LANG['admin_create_new_group'] = 'Create a new group...';
$LANG['admin_create_new_absence'] = 'Create a new absence type...';
$LANG['admin_create_new_holiday'] = 'Create a new holiday type...';
$LANG['admin_column_user'] = 'Lastname, Firstname (username)';
$LANG['icon_user'] = 'Regular User';
$LANG['icon_manager'] = 'Group Manager';
$LANG['icon_director'] = 'Director';
$LANG['icon_admin'] = 'Administrator';

/**
 * Configuration Page
 */
$LANG['admin_config_register_globals'] =
'TeamCal has found that your PHP environment variable \'register_globals\' is set to \'on\'. ' .
'It is highly recommended to switch this setting off since it represents a security weakness.\\n\\n' .
'If you are managing your webserver yourself edit your PHP.INI file and look for the setting ' .
'\'register_globals=On\'. Change this line to \'register_globals=Off\'.\\n\\n' .
'If you have no access to the PHP.INI file create a file named \'.htaccess\' in your TeamCal ' .
'directory containing the line \'php_value register_globals 0\'.';
$LANG['admin_config_register_globals_on'] = 'register_globals=On';
$LANG['admin_config_title'] = 'TeamCal Configuration';
$LANG['admin_config_general'] = 'General Options';
$LANG['admin_config_appsubtitle'] = 'Application Sub Title';
$LANG['admin_config_appsubtitle_comment'] = 'Will be displayed right above the TeamCal menu.';
$LANG['admin_config_appfootercpy'] = 'Application Footer Copyright';
$LANG['admin_config_appfootercpy_comment'] = 'Will be displayed in the footer right above the "Powered by..." statement.';
$LANG['admin_config_display'] = 'Calendar Display';
$LANG['admin_config_showmonths'] = 'Amount of Months';
$LANG['admin_config_showmonths_comment'] = 'Specify here how many months you want to display in the calendar view by default.';
$LANG['admin_config_showmonths_1'] = '1 month';
$LANG['admin_config_showmonths_2'] = '2 months';
$LANG['admin_config_showmonths_3'] = '3 months';
$LANG['admin_config_showmonths_6'] = '6 months';
$LANG['admin_config_showmonths_12'] = '12 months';
$LANG['admin_config_weeknumbers'] = 'Show Week Numbers';
$LANG['admin_config_weeknumbers_comment'] = 'Checking this option will add a line to the calendar display showing the week of the year number.';
$LANG['admin_config_remainder'] = 'Include Remainder';
$LANG['admin_config_remainder_comment'] =
'Checking this option will add an expandable column to the calendar display '.
'showing each users\'s remainder per absence type. Note: You need to configure the '.
'absence types that you want to be included in the remainder column.';
$LANG['admin_config_remainder_total'] = 'Include Remainder Allowance';
$LANG['admin_config_remainder_total_comment'] =
'Checking this option will add the total allowance per absence type to the expandable remainder '.
'display. The value is separated by a slash.';
$LANG['admin_config_show_remainder'] = 'Show Remainder';
$LANG['admin_config_show_remainder_comment'] =
'Checking this option will show/expand the remainder section by default';
$LANG['admin_config_summary'] = 'Include Summary';
$LANG['admin_config_summary_comment'] =
'Checking this option will add an expandable summary section at the bottom of each month, showing '.
'the sums of all absences.';
$LANG['admin_config_show_summary'] = 'Show Summary';
$LANG['admin_config_show_summary_comment'] =
'Checking this option will show/expand the summary section by default';
$LANG['admin_config_repeatheadercount'] = 'Repeat Header Count';
$LANG['admin_config_repeatheadercount_comment'] =
'Specifies the amount of user lines in the calendar before the month header '.
'is repeated for better readability.';
$LANG['admin_config_todaybordercolor'] = 'Today Border Color';
$LANG['admin_config_todaybordercolor_comment'] =
'Specifies the color in hexadecimal of the left and right border of the today column.';
$LANG['admin_config_todaybordersize'] = 'Today Border Size';
$LANG['admin_config_todaybordersize_comment'] =
'Specifies the size (thickness) in pixel of the left an right border of the today column.';
$LANG['admin_config_usericonsavatars'] = 'User Icons and Avatars';
$LANG['admin_config_usericons'] = 'Show User Icons';
$LANG['admin_config_usericons_comment'] =
'Checking this option will show user icons to the left of the users\' names indicating their role and gender.';
$LANG['admin_config_avatars'] = 'Show Avatars';
$LANG['admin_config_avatars_comment'] =
'Checking this option will show a user avatar pop-up when moving the mouse over the user icon.'.
'Note: This feature only works when "'.$LANG['admin_config_usericons'].'" is switched on.';
$LANG['admin_config_avatarwidth'] = 'Avatar Max Width';
$LANG['admin_config_avatarwidth_comment'] =
'Specifies the maximum width in pixel of the avatar image. Avatar images with a larger width will be resized to this width while adjusting the height proportionally.';
$LANG['admin_config_avatarheight'] = 'Avatar Max Height';
$LANG['admin_config_avatarheight_comment'] =
'Specifies the maximum height in pixel of the avatar image. Avatar images with a larger height will be resized to this height while adjusting the width proportionally.';
$LANG['admin_config_debughide'] = 'Debug Hide Info';
$LANG['admin_config_debughide_comment'] =
'If you want to hide the database info on the environment page, set this switch. However, the db_password will never be displayed in clear text, always as ******.';
$LANG['admin_config_timezone'] = 'Time Zone';
$LANG['admin_config_timezone_comment'] =
'If your web server resides in a different time zone than your TeamCal users you can adjust the user time zone here.';
$LANG['admin_config_login'] = 'Login Options';
$LANG['admin_config_pwd_length'] = 'Password Length';
$LANG['admin_config_pwd_length_comment'] = 'Minimal length of password.';
$LANG['admin_config_pwd_strength'] = 'Password Strength';
$LANG['admin_config_pwd_strength_comment'] =
'The password strength defines how picky you want to be with the password check.'.
'</span><ul style="list-style: square; margin-left: 0px;">'.
'<li><span class="function">Minimum</span><br><span class="config-comment">Anything is allowed if the password is at least of minimum length '.
'and a new password is not equal to the old.</span></li>'.
'<li><span class="function">Low</span><br><span class="config-comment">The password must be at least of minimum length '.
'and cannot contain the username forward or backward.</span></li>'.
'<li><span class="function">Medium</span><br><span class="config-comment">Same as "Low" but it must also contain numbers.</span></li>'.
'<li><span class="function">High</span><br><span class="config-comment">Same as "Medium" but it must also be mixed case plus punctuation.</span></li>'.
'</ul><span class="config-comment">';
$LANG['admin_config_cookie_lifetime'] = 'Cookie Lifetime';
$LANG['admin_config_cookie_lifetime_comment'] =
'Upon successful login a cookie is stored on the local hard drive of the user. ' .
'This cookie has a certain lifetime after which it becomes invalid. A new login is necessary. '.
'This lifetime can be specified here in seconds (0-999999).';
$LANG['admin_config_bad_logins'] = 'Bad Logins';
$LANG['admin_config_bad_logins_comment'] =
'Number of bad login attempts that will cause the user status to be set to \'LOCKED\'. The user has to wait as long ' .
'as the grace period specifies before he can login again. If you set this value to 0 the bad login feature is disabled.';
$LANG['admin_config_grace_period'] = 'Grace Period';
$LANG['admin_config_grace_period_comment'] =
'The amount of time in seconds that a user has to wait after too many bad logins before he can try again.';
$LANG['admin_config_mailfrom'] = 'Mail From';
$LANG['admin_config_mailfrom_comment'] =
'Specify a name to be shown as sender of notification e-mails.';
$LANG['admin_config_mailreply'] = 'Mail Reply-To';
$LANG['admin_config_mailreply_comment'] =
'Specify an e-mail address to reply to for notification e-mails. This field must contain a valid e-mail address. If that is not the case '.
'a dummy e-mail address "noreply@teamcalpro.com" will be saved.';
$LANG['admin_config_registration'] = 'User Registration';
$LANG['admin_config_allow_registration'] = 'Allow User Self-Registration';
$LANG['admin_config_allow_registration_comment'] =
'Allow users to self-register their account. A menu entry will be available in the TeamCal menu.';
$LANG['admin_config_email_confirmation'] = 'Require e-mail Confirmation';
$LANG['admin_config_email_confirmation_comment'] =
'Upon registration the user will receive an e-mail to the address he specified containing a confirmation link.'.
' He needs to follow that link to validate his information.';
$LANG['admin_config_admin_approval'] = 'Require Admin Approval';
$LANG['admin_config_admin_approval_comment'] =
'The administrator will receive an e-mail about each user self-registration. He manually needs to confirm the account.';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_title'] = 'Database Maintenance';
$LANG['admin_dbmaint_import_caption'] = 'Import Absences:';
$LANG['admin_dbmaint_import_original'] = 'Import original absence types';
$LANG['admin_dbmaint_import_convert'] = 'Import and convert all absence types to "not present"';
$LANG['admin_dbmaint_import_button'] = 'Import';
$LANG['admin_dbmaint_cleanup_caption'] = 'Clean up old templates and daynotes';
$LANG['admin_dbmaint_cleanup_year'] = 'Year:';
$LANG['admin_dbmaint_cleanup_month'] = 'Month:';
$LANG['admin_dbmaint_cleanup_hint'] = '(older than and including this month)';
$LANG['admin_dbmaint_cleanup_chkUsers'] = 'Delete user related templates and daynotes';
$LANG['admin_dbmaint_cleanup_chkMonths'] = 'Delete month templates and general daynotes';
$LANG['admin_dbmaint_cleanup_confirm'] = 'Please type in "CLEANUP" to confirm this action:';
$LANG['admin_dbmaint_del_caption'] = 'Delete database records';
$LANG['admin_dbmaint_del_chkUsers'] = 'Delete all users, their absence templates and daynotes (except "admin")';
$LANG['admin_dbmaint_del_chkGroups'] = 'Delete all groups';
$LANG['admin_dbmaint_del_chkHolidays'] = 'Delete all holidays (except "weekend" and "business day")';
$LANG['admin_dbmaint_del_chkAbsence'] = 'Delete all absence types';
$LANG['admin_dbmaint_del_chkDaynotes'] = 'Delete all general daynotes';
$LANG['admin_dbmaint_del_chkAnnouncements'] = 'Delete all announcements';
$LANG['admin_dbmaint_del_chkOrphAnnouncements'] = 'Delete orphaned announcements';
$LANG['admin_dbmaint_del_chkLog'] = 'Clear system log';
$LANG['admin_dbmaint_del_confirm'] = 'Please type in "DELETE" to confirm this action:';

/**
 * Environment Page
 */
$LANG['env_title'] = 'TeamCal Pro Environment Display';
$LANG['env_config'] = 'tc_config Variables';
$LANG['env_language'] = 'tc_language Variables';

/**
 * Error Messages
 */
$LANG['err_title'] = 'TeamCal Pro Error Message';
$LANG['err_not_authorized_short'] = 'Not Authorized';
$LANG['err_not_authorized_long'] = 'You are not authorized to view this page or perform this action.';
$LANG['err_allowance_not_numeric'] = 'Please use numeric values for allowance values.';

/**
 * Database Export (Database Maintenance Page)
 */
$LANG['exp_title'] = 'TeamCal Pro Data Export';
$LANG['exp_table'] = 'Table to export:';
$LANG['exp_table_absence'] = 'Absence Types';
$LANG['exp_table_group'] = 'Groups';
$LANG['exp_table_holiday'] = 'Holidays';
$LANG['exp_table_log'] = 'Log Entries';
$LANG['exp_table_month'] = 'Month Templates';
$LANG['exp_table_template'] = 'User Templates';
$LANG['exp_table_user'] = 'User Profiles';
$LANG['exp_format'] = 'Export format:';
$LANG['exp_format_xml'] = 'XML (Extensible Markup Language)';
$LANG['exp_format_csv'] = 'CSV (Comma Seperated Values)';

/**
 * Announcement Page
 */
$LANG['ann_title'] = 'Announcements for ';
$LANG['ann_delete_confirm_1'] = 'Are you sure you want to confirm and remove announcement [';
$LANG['ann_delete_confirm_2'] = '] from your list?';
$LANG['ann_id'] = 'Announcement ID';
$LANG['ann_bday_title'] = 'Birthday Notification for ';

/**
 * Statistics Page
 */
$LANG['stat_title'] = 'Statistics';
$LANG['stat_period_month'] = 'Current Month';
$LANG['stat_period_quarter'] = 'Current Quarter';
$LANG['stat_period_half'] = 'Current Half';
$LANG['stat_period_year'] = 'Current Year';
$LANG['stat_results_total_per_type'] = 'Absence types taken in period:&nbsp;&nbsp;';
$LANG['stat_results_remainders'] = 'Remainders per type for&nbsp;';
$LANG['stat_results_all_groups'] = 'Total (groups):';
$LANG['stat_results_all_members'] = 'Total (persons):';
$LANG['stat_results_group'] = 'Group ';
$LANG['stat_graph_total_absence_title'] = 'Absences per Group';
$LANG['stat_graph_total_presence_title'] = 'Presences per Group';
$LANG['stat_graph_total_type_title'] = 'Absences per Type';
$LANG['stat_graph_total_remainder_title'] = 'Remainders per Type for ';

/**
 * Absence-Group Assignment Dialog
 */
$LANG['abs_group_title'] = 'Absence Type/Group Assignment';
$LANG['abs_group_frame_title'] = 'Absence type assigments';
$LANG['abs_group_hint'] = 'Select the groups for which the following absence type is valid: ';

/**
 * User Import Dialog
 */
$LANG['uimp_title'] = 'TeamCal Pro User Import';
$LANG['uimp_import'] = 'Select a CSV (comma separated values) file from your local hard drive. Make sure you read the help for details of the content.';
$LANG['uimp_source'] = 'Source CSV file to import:';
$LANG['uimp_header'] = 'File includes header row:';
$LANG['uimp_separator'] = 'Field separator:';
$LANG['uimp_enclose'] = 'Text enclose character:';
$LANG['uimp_escape'] = 'Escape character:';
$LANG['uimp_error_file'] = 'Please specify a filename!';
$LANG['uimp_lockuser'] = 'Lock user:';
$LANG['uimp_hideuser'] = 'Hide user in calendar:';
$LANG['uimp_defgroup'] = 'Default Group:';
$LANG['uimp_deflang'] = 'Default Language:';
$LANG['uimp_error'] = '<span style="color: #FF0000;">Error</span>';
$LANG['uimp_err_col_1'] =
'The CSV file holds less or more than 11 columns in at least one line!<br><br>'.
'The line started with "';
$LANG['uimp_err_col_2'] = '" and contains ';
$LANG['uimp_err_col_3'] =
' columns.<br>The CSV file needs to carry the following columns:<br>'.
'"username";"firstname";"lastname";"title";"position";"phone";"mobile";"e-mail";"idnumber";"birthday";"showbirthday"<br><br>'.
'Please correct this error and try again.<br>Note: Make sure there is no trailing empty line at the end of the file.<br>&nbsp;';
$LANG['uimp_success'] = '<span style="color: #009900;">Success</span>';
$LANG['uimp_success_1'] = ' lines imported.';
$LANG['uimp_success_2'] = ' lines skipped.';

/**
 * User Registration Dialog
 */
$LANG['register_title'] = 'User Registration';
$LANG['register_frame'] = 'Registration Details';
$LANG['register_lastname'] = 'Last name';
$LANG['register_firstname'] = 'First name';
$LANG['register_username'] = 'Username';
$LANG['register_email'] = 'E-mail Address';
$LANG['register_password'] = 'Password';
$LANG['register_password2'] = 'Repeat Password';
$LANG['register_group'] = 'User Group';
$LANG['register_code'] = 'Security Code';
$LANG['register_result'] = 'Result';
$LANG['register_error_code'] = 'You typed in an incorrect security code.';
$LANG['register_error_incomplete'] = 'You need to at least provide '.$LANG['register_lastname'].
', '.$LANG['register_username'].', '.$LANG['register_email'].', '.$LANG['register_password'].' and '.$LANG['register_code'].'.';
$LANG['register_error_username'] = 'That username is already taken. Please choose another.';
$LANG['register_error_username_format'] = 'Only alphanumeric characters are allowed in usernames.';
$LANG['register_success'] = 'Your registration was successful. ';
$LANG['register_success_ok'] = 'You can now close this dialog and login with the username and password you provided.';
$LANG['register_success_verify'] = ' An e-mail has been sent to you with a confirmation link that you need to follow to verify your account. ';
$LANG['register_success_approval'] = ' Also, the administrator needs to approve your registration.';

$LANG['register_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Your Registration";
$LANG['register_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['register_mail_greeting'] .= "This message was automatically created by:\n";
$LANG['register_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['register_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['register_mail_greeting'] .= "Welcome to TeamCal Pro!\n";
$LANG['register_mail_greeting'] .= "Congratulations, you have successfully registered your account in TeamCal Pro.\n\n";
$LANG['register_mail_verify_1']  = "You need to verify your account by following this hyperlink:\n";
$LANG['register_mail_verify_2a'] = "After you have verified your account you can login to TeamCal Pro.\n\n";
$LANG['register_mail_verify_2b'] = "After you have verified your account and the administrator has approved it you can login to TeamCal Pro.\n\n";
$LANG['register_mail_verify_3']  = "Select [Login] from the TeamCal menu and use your account credentials:\n";
$LANG['register_mail_verify_3'] .= "Your username: [USERNAME]\n";
$LANG['register_mail_verify_3'] .= "Your password: [PASSWORD]\n\n";
$LANG['register_mail_verify_3'] .= "\n\nYour TeamCal Pro Administration\n";

$LANG['register_admin_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - New User Registration";
$LANG['register_admin_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['register_admin_mail_greeting'] .= "This message was automatically created by:\n";
$LANG['register_admin_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['register_admin_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['register_admin_mail_greeting'] .= "Hello Administrator,\n\n";
$LANG['register_admin_mail_message']   = "A new user has registered:\n";
$LANG['register_admin_mail_message']  .= "Fullname: [FIRSTNAME] [LASTNAME]\n";
$LANG['register_admin_mail_message']  .= "Username: [USERNAME]\n\n";
$LANG['register_admin_mail_message_1'] = "The user needs to verify his account.\n";
$LANG['register_admin_mail_message_2'] = "Administrator approval is required after successful verification. You will be notified about the verification in a separate mail.";
$LANG['register_admin_mail_message_3'] = "\n\nYour TeamCal Pro Administration\n";

$LANG['verify_title'] = 'User Verification';
$LANG['verify_result'] = 'User Verification Result';
$LANG['verify_err_link'] = 'The link you have used is incorrect or incomplete. Make sure you are using the complete link in your e-mail. Sometimes the link is separated by a line break in the Mail. Copy and paste the complete string into your browser\'s URL box and try again.';
$LANG['verify_err_user'] = 'The username does not exist.';
$LANG['verify_err_code'] = 'The verification code does not exist.';
$LANG['verify_err_match'] = 'The verification code does not match.';
$LANG['verify_info_success'] = 'Your user account has been verified. ';
$LANG['verify_info_login'] = 'You can now login with the credentials you provided during registration.';
$LANG['verify_info_approval'] = 'However, the administrator needs to approve your account and unlock it before you can login with the credentials you provided during registration. He was notified by e-mail.';

$LANG['verify_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Your Approval Needed";
$LANG['verify_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['verify_mail_greeting'] .= "This message was automatically created by:\n";
$LANG['verify_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['verify_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['verify_mail_greeting'] .= "Hello Administrator,\n\n";
$LANG['verify_mail_message']   = "the user [USERNAME] successfully verified his account.\n";
$LANG['verify_mail_message']  .= "Your approval is needed to unlock and unhide his account.\n";
$LANG['verify_mail_message']  .= "Please edit this user's profile according to your approval decision.\n\n";
$LANG['verify_mail_message']  .= "\n\nYour TeamCal Pro Administration\n";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.0.000
 */

/**
 * Common
 */
$LANG['result'] = "Result";

/**
 * Filter
 */
$LANG['nav_user'] = 'User:';
$LANG['nav_year'] = 'Year:';

/**
 * User Profile Dialog
 */
$LANG['tab_other'] = 'Other';
$LANG['other_title']    = "Other Information";
$LANG['other_customFree']    = "Comment";
$LANG['other_customPopup']    = "Popup Info";
$LANG['uo_deftheme']    = "Default Theme";

/**
 * Admin Pages
 */
$LANG['user_search'] = 'Search:';

/**
 * Configuration Page
 */
$LANG['admin_config_emailnotifications'] = 'E-mail Notifications';
$LANG['admin_config_emailnotifications_comment'] =
'Enable/Disable E-mail notifications. If you uncheck this option no automated notifications E-mails are sent. ' .
'However, this does not apply to self-registration mails and to manually sent mails via the Message Center and '.
'the View Profile dialog.';
$LANG['admin_config_userCustom'] = 'User Custom Fields';
$LANG['admin_config_userCustom1'] = 'User Custom Field 1 Caption';
$LANG['admin_config_userCustom1_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['admin_config_userCustom2'] = 'User Custom Field 2 Caption';
$LANG['admin_config_userCustom2_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['admin_config_userCustom3'] = 'User Custom Field 3 Caption';
$LANG['admin_config_userCustom3_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['admin_config_userCustom4'] = 'User Custom Field 4 Caption';
$LANG['admin_config_userCustom4_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['admin_config_userCustom5'] = 'User Custom Field 5 Caption';
$LANG['admin_config_userCustom5_comment'] = 'Enter the caption of this custom user field. The caption will be shown in the profile dialog.';
$LANG['admin_config_theme'] = 'Theme';
$LANG['admin_config_theme_comment'] = 'Select a theme to change the looks of TeamCal Pro. You can create your own ' .
'skin by making a copy of the \'tcpro\' directory in the \'themes\' folder and adjust the style sheet and images to your liking. Your new '.
'directory will automatically be listed here.';
$LANG['admin_config_usertheme'] = 'User Theme';
$LANG['admin_config_usertheme_comment'] = 'Check whether you want each user to be able to select his individual TeamCal Pro theme.';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_rest_caption'] = 'Restore Database';
$LANG['admin_dbmaint_rest_comment'] = 'Select a file of a previous database download to import back into the database. ' .
'Make sure it was downloaded with this version of TeamCal Pro.<br><span class="erraction">Backup or export your current database first! All data will be overwritten!</span>';
$LANG['admin_dbmaint_msg_001'] = "No valid SQL statement found in file.";
$LANG['admin_dbmaint_msg_002'] = "Database restore successful. The file was also uploaded to your 'sql' folder.";
$LANG['admin_dbmaint_msg_003'] = "The database restore file could not be uploaded.";
$LANG['admin_dbmaint_msg_004'] = "No file name for database restore specified.";
$LANG['admin_dbmaint_exp_caption'] = 'Export Database';
$LANG['exp_table_all'] = 'All';
$LANG['exp_format'] = 'Export Format:';
$LANG['exp_format_csv'] = 'CSV (Comma Seperated Values)';
$LANG['exp_format_sql'] = 'SQL (Structured Query Language)';
$LANG['exp_format_xml'] = 'XML (Extensible Markup Language)';
$LANG['exp_output'] = 'Export Output:';
$LANG['exp_output_browser'] = 'Browser';
$LANG['exp_output_file'] = 'File Download';

/**
 * Show Year Page
 */
$LANG['showyear_title_1'] = 'Year calendar';
$LANG['showyear_title_2'] = 'for:';
$LANG['showyear_tt_day'] = 'Day Information';
$LANG['showyear_tt_user'] = 'User Information';
$LANG['showyear_weeknumber'] = 'Calendar week';

/**
 * Error Messages
 */
$LANG['err_instfile_title'] = "Security Warning";
$LANG['err_instfile'] = "You seem to have installed TeamCal Pro already. However, the installation.php file " .
"still exists. For security reasons you should delete it as soon as possible.";

/**
 * PHPInfo Page
 */
$LANG['php_title'] = 'PHP Environment';

/**
 * Group Assignment Page
 */
$LANG['uassign_title'] = 'Group Assignments';
$LANG['uassign_usertype'] = 'User Type';
$LANG['uassign_tt_member'] = 'Regular User (Member or Manager)';
$LANG['uassign_tt_director'] = 'Director';
$LANG['uassign_tt_admin'] = 'Administrator';
$LANG['uassign_tt_gnotmember'] = 'Not member of this group';
$LANG['uassign_tt_gmember'] = 'Member of this group';
$LANG['uassign_tt_gmanager'] = 'Manager of this group';

/**
 * Absence Icon Page
 */
$LANG['absicon_title'] = 'Absence Icon for ';
$LANG['absicon_none'] = 'None';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.0.001
 */

/**
 * Loglevel Management Page
 */
$LANG['admin_loglevel_loglevel'] = 'Log Loglevel changes';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.000
 */

/**
 * Calendar Display
 */
$LANG['totals'] = 'This Month';

/**
 * Configuration Page
 */
$LANG['admin_config_totals'] = 'Include Totals';
$LANG['admin_config_totals_comment'] =
'Checking this option will add a "totals this month" section in the remainder column '.
'showing each user\'s totals per absence type for the month displayed. Note: You need to configure the '.
'absence types that you want to be included in the totals column.';

/**
 * Edit Absence Dialog
 */
$LANG['ea_column_showtotals'] = "T";
$LANG['ea_column_presence'] = "P";

/**
 * User Profile Dialog
 */
$LANG['ut_template'] = "Template User";

/**
 * Admin Pages
 */
$LANG['icon_template'] = 'Template User (Absences are copied to all users of the same group)';
$LANG['template_user'] = '[Template User]';

/**
 * Configuration Page
 */
$LANG['admin_config_repeatheadersafter'] = 'Group Assignment Page: Repeat Headers After';
$LANG['admin_config_repeatheadersafter_comment'] = 'Sets the amount of user rows after which the header row is repeated for better readability.';
$LANG['admin_config_repeatusernamesafter'] = 'Group Assignment Page: Repeat Usernames After';
$LANG['admin_config_repeatusernamesafter_comment'] = 'Sets the amount of group columns after which the usernames column is repeated for better readability.';
$LANG['admin_config_optionsbar'] = 'Options Bar';
$LANG['admin_config_optionsbar_language'] = 'Show Language Selection';
$LANG['admin_config_optionsbar_group'] = 'Show Group Selection';
$LANG['admin_config_optionsbar_today'] = 'Show Today Selection';
$LANG['admin_config_optionsbar_start'] = 'Show Start Selection';
$LANG['admin_config_pastdaycolor'] = 'Past Day Color';
$LANG['admin_config_pastdaycolor_comment'] = 'Sets a background color that is used for every day in the current month that lies in the past. '
.'Delete this value if you don\'t want to color the past days.';

/**
 * Filter
 */
$LANG['nav_absencefilter'] = 'Today:';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.004
 */

/**
 * Printout
 */
$LANG['print_title'] = 'TeamCal Pro Printout';

/**
 * Configuration Page
 */
$LANG['admin_config_hide_managers'] = 'Hide Managers in All-by-Group and Group Display';
$LANG['admin_config_hide_managers_comment'] = 'Checking this option will hide all managers in the All-by-Group and Group display except in those groups where they are just members.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.005
 */

/**
 * Declination Management Page
 */
$LANG['admin_decl_before_today'] = 'Decline absence requests before today (not including today)';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.006
 */

/**
 * Configuration Page
 */
$LANG['admin_config_firstdayofweek'] = 'First Day of Week';
$LANG['admin_config_firstdayofweek_comment'] = 'Set this to Monday or Sunday. This setting will be reflected in the week number display.';
$LANG['admin_config_firstdayofweek_1'] = 'Monday';
$LANG['admin_config_firstdayofweek_7'] = 'Sunday';
$LANG['admin_config_defgroupfilter'] = 'Default Group Filter';
$LANG['admin_config_defgroupfilter_comment'] = ' Select the default group filter for the calendar display. Each user can still change his individual default filter in his profile.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.2.000
 */

/**
 * Month Dialog
 */
$LANG['month_region'] = 'Region';

/**
 * Error Messages
 */
$LANG['err_input_region_add'] = 'You have to add at least a shortname in order to add a new region.';

/**
 * Admin Pages
 */
$LANG['admin_region_title'] = 'Manage Regions';

/**
 * Configuration Page
 */
$LANG['admin_config_defregion'] = 'Default Region for Base Calendar';
$LANG['admin_config_defregion_comment'] = 'Select the default region for the base calendar to be used. Each user can still change his individual default region in his profile.';
$LANG['admin_config_optionsbar_comment'] =
'The Options Bar is displayed right underneath the menu bar. It contains the language selection drop down, '.
'group selection drop down and other filtering options. Use these settings to switch the separate items on or off.'.
'</span><ul style="list-style: square; margin-left: 0px;">'.
'<li><span class="function">Group Selection</span><br><span class="config-comment">Displays the group filter selection drop down.</span></li>'.
'<li><span class="function">Region Selection</span><br><span class="config-comment">Displays the region selection drop down.</span></li>'.
'<li><span class="function">Today Selection</span><br><span class="config-comment">Displays the today\'s absence selection drop down.</span></li>'.
'<li><span class="function">Start Selection</span><br><span class="config-comment">Displays the start with year, month and how many months to display selection drop downs.</span></li>'.
'</ul><span class="config-comment">';
$LANG['admin_config_optionsbar_region'] = 'Show Region Selection';
$LANG['admin_config_hide_daynotes'] = 'Hide Personal Daynotes';
$LANG['admin_config_hide_daynotes_comment'] = 'Switching this on will hide personal daynotes from regular users. Only Managers, Directors and Administrators can edit and see them. That way they can be used for managing purposes only. This switch does not affect birthday notes.';

/**
 * User Profile Dialog
 */
$LANG['uo_defregion'] = 'Default Region';

/**
 * Filter
 */
$LANG['nav_regionfilter'] = 'Region:';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_del_chkRegions'] = 'Delete all regions and their templates (except "default")';

/**
 * Database Export (Database Maintenance Page)
 */
$LANG['exp_table_region'] = 'Regions';

/**
 * Edit Absence Dialog
 */
$LANG['ea_column_manager_only'] = "M";
$LANG['ea_column_hide_in_profile'] = "H";
$LANG['ea_column_allowance_mouseover'] = "Allowance";
$LANG['ea_column_factor_mouseover'] = "Factor";
$LANG['ea_column_showremain_mouseover'] = "Show Remainder";
$LANG['ea_column_showtotals_mouseover'] = "Show Totals";
$LANG['ea_column_approval_mouseover'] = "Approval Required";
$LANG['ea_column_presence_mouseover'] = "Counts as Present";
$LANG['ea_column_manager_only_mouseover'] = "Management Only";
$LANG['ea_column_hide_in_profile_mouseover'] = "Hide in user profile";
$LANG['ea_column_groups_mouseover'] = "Absence to Group Assignments";

/**
 * Edit Groups Dialog
 */
$LANG['column_min_present'] = 'Min';
$LANG['column_max_absent'] = 'Max';

/**
 * Notification Messages
 */
$LANG['notification_decl_minpresent'] = "Minimum number of present members reached: ";
$LANG['notification_decl_minpresent1'] = " members of ";
$LANG['notification_decl_minpresent2'] = " must be present at all times. With your absence this number would be lower.";
$LANG['notification_decl_maxabsent'] = "Maximum number of absent members reached: ";
$LANG['notification_decl_maxabsent1'] = " members of ";
$LANG['notification_decl_maxabsent2'] = " can be absent at the same time. With your absence this number would be exceeded.";

/**
 * Error Messages
 */
$LANG['err_decline_minpresent1'] = 'The administrator has set a minimum of present members\\nof ';
$LANG['err_decline_minpresent2'] = ' members for your group: ';
$LANG['err_decline_maxabsent1'] = 'The administrator has set a maximum of absent members\\nof ';
$LANG['err_decline_maxabsent2'] = ' members for your group: ';
$LANG['err_decline_period_1'] = 'The administrator has defined a declination period. No absences are allowed starting from (including) ';
$LANG['err_decline_period_2'] = ' and ending at (including) ';

/**
 * Declination Management Page
 */
$LANG['admin_decl_period'] = 'Decline absences in the following period.';
$LANG['admin_decl_period_start'] = 'Decline absences between (including):&nbsp;';
$LANG['admin_decl_period_end'] = '&nbsp;and (including):&nbsp;';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.2.003
 */

/**
 * Statistics Page
 */
$LANG['stat_all'] = 'All';
$LANG['stat_group'] = 'Group';
$LANG['stat_absence'] = 'Absence Type';
$LANG['stat_results_total_absence_user'] = 'Total absence per user in period:&nbsp;&nbsp;';
$LANG['stat_results_total_absence_group'] = 'Total absence per group in period:&nbsp;&nbsp;';
$LANG['stat_results_total_presence_user'] = 'Total presence per user in period:&nbsp;&nbsp;';
$LANG['stat_results_total_presence_group'] = 'Total presence per group in period:&nbsp;&nbsp;';
$LANG['stat_choose_period'] = 'Standard Period';
$LANG['stat_choose_custom_period'] = 'Custom Period';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.001
 */

/**
 * Statistics Page
 */
$LANG['stat_u_title'] = 'Remainder Statistics Current Year';
$LANG['stat_u_total'] = 'Total:';
$LANG['stat_u_taken'] = 'Taken';
$LANG['stat_u_sel_group_user'] = 'Select Group or User';
$LANG['stat_u_sel_group'] = 'Select Group:';
$LANG['stat_u_sel_user'] = 'Select User:';
$LANG['stat_graph_u_remainder_title_1'] = 'Remainder in period (';
$LANG['stat_graph_u_remainder_title_2'] = ') for user: ';
$LANG['stat_u_type'] = 'Type';
$LANG['stat_u_total_remainder'] = 'Remainder';

/**
 * Edit Absence Dialog
 */
$LANG['ea_column_confidential'] = "C";
$LANG['ea_column_confidential_mouseover'] = "Absence type is confidential";

/**
 * Calendar Edit Dialog
 */
$LANG['cal_recurring_workdays'] = "Mo-Fr";
$LANG['cal_recurring_weekend'] = "Sa-Su";

/**
 * Configuration Page
 */
$LANG['admin_config_defperiod'] = 'Default Allowance Period';
$LANG['admin_config_defperiod_comment'] = 'Select the start date and end date of the default allowance period. Usually this is the ' .
      'current year, from January 1st to December 31st. However, you might use a different period to count your allowances against. If you choose a ' .
      'different period than the calendar year, remember that the terms "current year" and "previous year" then refer to your custom period. ' .
      'The From-date must be smaller than the To-date.';
$LANG['admin_config_defperiod_from'] = 'From';
$LANG['admin_config_defperiod_to'] = 'To';

/**
 * Statistics Page
 */
$LANG['stat_period_period'] = 'Current Default Period';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.004
 */

/**
 * Configuration Page
 */
$LANG['admin_config_mark_confidential'] = 'Mark Confidential Absences';
$LANG['admin_config_mark_confidential_comment'] = 'Regular users cannot see confidential absences of others. However, with this option ' .
      'checked they will be marked with an "X" in the calendar to show that the person is not present. The type of absence will not be shown.';
$LANG['admin_config_homepage'] = 'Start Page';
$LANG['admin_config_homepage_comment'] = 'Select the initial display after login or logout. This can either be the welcome page or the calendar display.';
$LANG['admin_config_homepage_welcome'] = 'Welcome Page';
$LANG['admin_config_homepage_calendar'] = 'Calendar';
$LANG['admin_config_welcome'] = 'Welcome Page Text';
$LANG['admin_config_welcome_comment'] = 'Enter a title and a text for the welcome message on the welcome page. These fields allow the usage of the '.
'HTML tags < i > and < b >. Line breaks will be translated into < br > tags automatically. All other HTML tags will be stripped.';

/**
 * Userlist Page
 */
$LANG['user_pwd_reset_confirm'] = "Are you sure you want to reset the password of the selected user(s)?\\r\\n";

/**
 * Notification Messages
 */
$LANG['notification_usr_pwd_subject'] = $CONF['app_name']." ".$CONF['app_version']." - Your New Password";
$LANG['notification_usr_pwd_reset'] = "Your password has been reset by the administrator.\r\n\r\nYour new password is: ";
$LANG['notification_sign'] = "Your TeamCal Pro Administration.\n";

/**
 * User Profile Dialog
 */
$LANG['profile_added'] = 'The user was added successfully.';

/**
 * Welcome Page
 */
$LANG['welcome_title'] = 'Welcome to TeamCal Pro';

/**
 * Message Dialog
 */
$LANG['message_type_announcement_welcome'] = 'Welcome Page';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.007
 */

/**
 * User Registration Dialog
 */
 $LANG['register_error_email'] = 'You have to provide a valid e-mail address to complete the registration.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.010
 */

/**
 * Configuration Page
 */
$LANG['admin_config_usersperpage'] = 'Number of users per page';
$LANG['admin_config_usersperpage_comment'] = 'If you maintain a large amount of users in TeamCal Pro you might want to use paging in the calendar display. ' .
      'Indicate how much users you want to display on each page. A value of 0 will disable paging. In case you choose paging, there will be paging buttons ' .
      'at the bottom of each page.';
$LANG['admin_config_mail_options'] = 'Email Options';
$LANG['admin_config_mail_smtp'] = 'Use external SMTP server';
$LANG['admin_config_mail_smtp_comment'] = 'Use an external SMTP server instead of the PHP mail() function to send out E-mails. '.
'This feature requires the PEAR Mail package to be installed on your server. Many hosters install this package by default. '.
'It is also necessary for SMTP to work, that your Tcpro server can connect to the selected SMTP server via the usual SMTP ports 25, 465 or 587, '.
'using plain SMTP or TLS/SSL protocol, depending on your settings. Some hosters have this communication closed down by firewall rules. '.
'You will get a connection error then.';
$LANG['admin_config_mail_smtp_host'] = 'SMTP Host';
$LANG['admin_config_mail_smtp_host_comment'] = 'Specify the SMTP host name.';
$LANG['admin_config_mail_smtp_port'] = 'SMTP Port';
$LANG['admin_config_mail_smtp_port_comment'] = 'Specify the SMTP host port.';
$LANG['admin_config_mail_smtp_username'] = 'SMTP Username';
$LANG['admin_config_mail_smtp_username_comment'] = 'Specify the SMTP username.';
$LANG['admin_config_mail_smtp_password'] = 'SMTP Password';
$LANG['admin_config_mail_smtp_password_comment'] = 'Specify the SMTP password.';
$LANG['admin_config_satbusi'] = 'Saturday is a Business Day';
$LANG['admin_config_satbusi_comment'] = 'Check this option if you want Saturday to be displayed and counted as a business day.';
$LANG['admin_config_sunbusi'] = 'Sunday is a Business Day';
$LANG['admin_config_sunbusi_comment'] = 'Check this option if you want Sunday to be displayed and counted as a business day.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.000
 */

/**
 * Regions Page
 */
$LANG['tt_region'] = 'Region';
$LANG['tt_add_region'] = 'Add new region';
$LANG['tt_add_ical'] = 'Import iCal file as new region';
$LANG['region_caption_add'] = 'Add Regions';
$LANG['region_caption_existing'] = 'Edit Regions';
$LANG['err_input_no_filename'] = 'No file name was submitted.';
$LANG['region_ical_description'] = 'Select an iCal file with whole day events (e.g. school holidays) from a local drive. Then select the holiday type to be used for all events in that file. Then click on ['.$LANG['btn_import_ical'].']';
$LANG['reg_delete_confirm'] = 'Are you sure you want to delete this region?';
$LANG['region_caption_merge'] = 'Merge Regions';
$LANG['column_source_region'] = 'Source region';
$LANG['column_target_region'] = 'Target region';
$LANG['column_overwrite'] = 'Overwrite';
$LANG['err_input_same_region'] = 'You can\'t merge a reason with itself.';
$LANG['err_input_region_exists'] = 'A region with that shortname already exists. Delete the existing region first or choose a different shortname.';

/**
 * Edit month page
 */
$LANG['tt_page_bwd'] = "Page backward one month...";
$LANG['tt_page_fwd'] = "Page forward one month...";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.001
 */

/**
 * Userlist Page
 */
$LANG['notification_usr_pwd_reset_user'] = "Your username is: ";
$LANG['notification_usr_pwd_reset_pwd'] = "Your new password is: ";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.002
 */

/**
 * Edit Calendar Page
 */
$LANG['cal_only_business'] = "Mark only business days";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.003
 */

/**
 * Error Messages
 */
$LANG['err_decl_title'] = "[INPUT VALIDATION]\\n\\nPlease contact your manager.\\n";
$LANG['err_decl_subtitle'] = "Not all of your absence requests could be accepted for the following reasons:\\n\\n";
$LANG['err_decl_day'] = "Day ";
$LANG['err_decl_group_threshold'] = ": Group absence threshold reached for your group(s): ";
$LANG['err_decl_total_threshold'] = ": Total absence threshold reached.";
$LANG['err_decl_min_present'] = ": Minimum presence threshold reached for group(s): ";
$LANG['err_decl_max_absent'] = ": Maximum absence threshold reached for group(s); ";
$LANG['err_decl_before'] = ": Absence changes are not allowed before ";
$LANG['err_decl_period'] = ": Absence changes are not allowed between ";
$LANG['err_decl_and'] = " and ";
$LANG['err_decl_abs'] = ": The absence type '";
$LANG['err_decl_old_abs'] = ": The existing absence type '";
$LANG['err_decl_new_abs'] = ": The requested absence type '";
$LANG['err_decl_approval'] = "' requires approval and cannot be changed or set.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.000
 */

/**
 * Permission Scheme Page
 */
$LANG['perm_select_confirm'] = "Are you sure you want to select this permission scheme?\\nAll changes to the current scheme that have not been applied will be lost.";
$LANG['perm_activate_confirm'] = "Are you sure you want to activate\\nthis permission scheme?";
$LANG['perm_reset_confirm'] = "Are you sure you want to reset the current permission scheme?\\nAll values will be set to their default.";
$LANG['perm_delete_confirm'] = "Are you sure you want to delete the current permission scheme?\\nThe Default scheme will be loaded and activated.";
$LANG['perm_title'] = "Permission Settings for scheme: ";
$LANG['perm_sel_scheme'] = "Select scheme";
$LANG['perm_create_scheme'] = "Create scheme";
$LANG['perm_col_perm_admin'] = "Administrative Permissions";
$LANG['perm_col_perm_cal'] = "Global Calendar Permissions";
$LANG['perm_col_perm_user'] = "User Related Permissions";
$LANG['perm_col_perm_view'] = "Viewing Permissions";
$LANG['perm_col_admin'] = "Administrator";
$LANG['perm_col_admin_tt'] = "Administrators can perform this action.";
$LANG['perm_col_director'] = "Director";
$LANG['perm_col_director_tt'] = "Directors can perform this action.";
$LANG['perm_col_manager'] = "Manager";
$LANG['perm_col_manager_tt'] = "Managers can perform this action.";
$LANG['perm_col_user'] = "User";
$LANG['perm_col_user_tt'] = "Users can perform this action.";
$LANG['perm_col_public'] = "Public";
$LANG['perm_col_public_tt'] = "The public (not logged in) can perform this action.";
$LANG['perm_perm_editConfig_title'] = "Edit TeamCal Pro Configuration";
$LANG['perm_perm_editConfig_desc'] = "Allows to edit the TeamCal Pro configuration.";
$LANG['perm_perm_editPermissionScheme_title'] = "Edit Permission Scheme";
$LANG['perm_perm_editPermissionScheme_desc'] = "Allows to edit the permission scheme. This permission is allways allowed for admins.";
$LANG['perm_perm_manageUsers_title'] = "Manage Users";
$LANG['perm_perm_manageUsers_desc'] = "Allows to administer user accounts, including add, edit, delete and import and editing user type and status for existing users.";
$LANG['perm_perm_manageGroups_title'] = "Manage Groups";
$LANG['perm_perm_manageGroups_desc'] = "Allows to manage user groups.";
$LANG['perm_perm_manageGroupMemberships_title'] = "Manage Group Memberships";
$LANG['perm_perm_manageGroupMemberships_desc'] = "Allows to edit the group memberships of all users.";
$LANG['perm_perm_editAbsenceTypes_title'] = "Edit Absence Types";
$LANG['perm_perm_editAbsenceTypes_desc'] = "Allows to edit the absence types.";
$LANG['perm_perm_editRegions_title'] = "Edit Regions";
$LANG['perm_perm_editRegions_desc'] = "Allows to edit regions.";
$LANG['perm_perm_editHolidays_title'] = "Edit Holidays";
$LANG['perm_perm_editHolidays_desc'] = "Allows to edit holidays.";
$LANG['perm_perm_editDeclination_title'] = "Edit Declination Settings";
$LANG['perm_perm_editDeclination_desc'] = "Allows to edit the declination settings.";
$LANG['perm_perm_manageDatabase_title'] = "Manage Database";
$LANG['perm_perm_manageDatabase_desc'] = "Allows to manage the database.";
$LANG['perm_perm_viewSystemLog_title'] = "View System Log";
$LANG['perm_perm_viewSystemLog_desc'] = "Allows to view the system log.";
$LANG['perm_perm_editSystemLog_title'] = "Edit System Log";
$LANG['perm_perm_editSystemLog_desc'] = "Allows to change the log settings on the system log page.";
$LANG['perm_perm_viewEnvironment_title'] = "View Environment Variables";
$LANG['perm_perm_viewEnvironment_desc'] = "Allows to view the TeamCal Pro environment variables and PHP info.";
$LANG['perm_perm_viewStatistics_title'] = "View Statistics";
$LANG['perm_perm_viewStatistics_desc'] = "Allows to view the global and remainder statistics";
$LANG['perm_perm_editGlobalCalendar_title'] = "Edit Global Calendar";
$LANG['perm_perm_editGlobalCalendar_desc'] = "Allows to edit the global calendar for all regions, e.g. to set holidays.";
$LANG['perm_perm_editGlobalDaynotes_title'] = "Edit Global Daynotes";
$LANG['perm_perm_editGlobalDaynotes_desc'] = "Allows to edit the global daynotes from the global calendar editor.";
$LANG['perm_perm_useMessageCenter_title'] = "Use Message Center";
$LANG['perm_perm_useMessageCenter_desc'] = "Allows to use the message tool.";
$LANG['perm_perm_viewCalendar_title'] = "Open Calendar";
$LANG['perm_perm_viewCalendar_desc'] = "Allows to view the calendar in general. If this is not permitted, no calendars can be displayed. Can be used to allow the public to view the home page but not the calendar.";
$LANG['perm_perm_viewYearCalendar_title'] = "View Year Calendar";
$LANG['perm_perm_viewYearCalendar_desc'] = "Allows to view the year calendar.";
$LANG['perm_perm_viewAnnouncements_title'] = "View Announcement";
$LANG['perm_perm_viewAnnouncements_desc'] = "Allows to view the announcements page. Note, that the announcements page only shows announcements for the logged in user.";
$LANG['perm_perm_viewAllGroups_title'] = "View All Groups";
$LANG['perm_perm_viewAllGroups_desc'] = "Allows to view all groups in calendars and filters. If unchecked, only own groups can be seen (member of or manager of).";
$LANG['perm_perm_viewAllUserCalendars_title'] = "View All User Calendars";
$LANG['perm_perm_viewAllUserCalendars_desc'] = "Allows to view all user calendars. Note, a user can always view his own calendar.";
$LANG['perm_perm_viewGroupUserCalendars_title'] = "View Group User Calendars";
$LANG['perm_perm_viewGroupUserCalendars_desc'] = "Allows to view the user calendars of own group users (member or manager of). Note, a user can always view his own calendar.";
$LANG['perm_perm_viewUserProfiles_title'] = "View User Profiles";
$LANG['perm_perm_viewUserProfiles_desc'] = "Allows to view user profiles showing basic info like name, phone number etc. Viewing user popups is also dependent on this permission.";
$LANG['perm_perm_editAllUserAllowances_title'] = "Edit All User Allowances";
$LANG['perm_perm_editAllUserAllowances_desc'] = "Allows to edit the absence allowances of all users. With this permission granted the allowances can be edited in the absence tab of the user profile dialogs.";
$LANG['perm_perm_editGroupUserAllowances_title'] = "Edit Group User Allowances";
$LANG['perm_perm_editGroupUserAllowances_desc'] = "Allows to edit the absence allowances of own group users (member or manager of). With this permission granted the allowances can be edited in the absence tab of the user profile dialogs.";
$LANG['perm_perm_viewUserAbsenceCounts_title'] = "View User Absence Counts";
$LANG['perm_perm_viewUserAbsenceCounts_desc'] = "Allows to view the absence counts of a user.";
$LANG['perm_perm_editAllUserProfiles_title'] = "Edit All User Profiles";
$LANG['perm_perm_editAllUserProfiles_desc'] = "Allows to edit the profile of all users. Note, a user can always edit his own profile.";
$LANG['perm_perm_editGroupUserProfiles_title'] = "Edit Group User Profiles";
$LANG['perm_perm_editGroupUserProfiles_desc'] = "Allows to edit the profile of own group users (member or manager of). Note, a user can always edit his own profile but never of his own manager.";
$LANG['perm_perm_editAllUserCalendars_title'] = "Edit All User Calendars";
$LANG['perm_perm_editAllUserCalendars_desc'] = "Allows to edit the calendars of all users.";
$LANG['perm_perm_editGroupUserCalendars_title'] = "Edit Group User Calendars";
$LANG['perm_perm_editGroupUserCalendars_desc'] = "Allows to edit the calendars of own group users (member of or manager of). Note, a user cannot edit the calendar of his manager.";
$LANG['perm_perm_editOwnUserCalendars_title'] = "Edit Own User Calendars";
$LANG['perm_perm_editOwnUserCalendars_desc'] = "Allows to edit only own calendars. If you run a central absence management you might want to switch this off for Users so they can only view, not edit their calendars.";
$LANG['perm_perm_editAllUserDaynotes_title'] = "Edit All User Daynotes";
$LANG['perm_perm_editAllUserDaynotes_desc'] = "Allows to edit the daynotes of all users. Note, a user can always edit his own daynotes.";
$LANG['perm_perm_editGroupUserDaynotes_title'] = "Edit Group User Daynotes";
$LANG['perm_perm_editGroupUserDaynotes_desc'] = "Allows to edit the daynotes of own group users (member or manager of). Note, a user can always edit his own daynotes but never of his own manager.";

/**
 * Config page
 */
$LANG['admin_config_pscheme'] = "Permission Scheme";
$LANG['admin_config_pscheme_comment'] = "The permission defines who can do what in TeamCal Pro. The permission schemes can be configured on the permissions page.";
$LANG['admin_config_system_options'] = 'System Options';
$LANG['admin_config_jQueryCDN'] = 'jQuery CDN';
$LANG['admin_config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) can offer a performance benefit by hosting popular '.
      'web modules on servers spread across the globe. jQuery is such a module and TeamCal Pro uses it too. Pulling it from a CDN location '.
      'also offers an advantage that if the visitor to your webpage has already downloaded a copy of jQuery from the same CDN, it won\'t '.
      'have to be re-downloaded.<br>Switch this option off if you are running TeamCal Pro in an environment with no Internet connectivity.';
$LANG['admin_config_welcomeIcon'] = 'Show Welcome Message Icon';
$LANG['admin_config_welcomeIcon_comment'] = 'You can choose to display the TeamCal calendar icon next to the welcome text. It will be '.
      'placed at the top left and the text will flow around it. Select the size in the drop down list.';
$LANG['no']='No';
$LANG['admin_config_googleAnalytics'] = "Google Analytics";
$LANG['admin_config_googleAnalytics_comment'] = "TeamCal Pro supports Google Analytics. If you run your TeamCal Pro instance in the Internet and want ".
      "to use Google Analytics to trace access to it, you can check this box and enter your Google Analytics ID. TeamCal Pro will add the corresponding ".
      "Javascript code.";
$LANG['admin_config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['admin_config_webMeasure'] = "webMeasure";
$LANG['admin_config_webMeasure_comment'] = "webMeasure is a free online conversion utility by Lewe.com. It is hosted on Lewe.com and can be accessed via the Internet. ".
      "With this option here it can be offered as a bonus feature to your users in the Tools menu.";

/**
 * System Log Page
 */
$LANG['log_title'] = 'System Log';
$LANG['log_settings'] = 'Log Settings';
$LANG['log_settings_event'] = 'Event type';
$LANG['log_settings_log'] = 'Log this event type';
$LANG['log_settings_show'] = 'Show this event type in the system log';
$LANG['log_sort_asc'] = 'Sort ascending...';
$LANG['log_sort_desc'] = 'Sort descending...';
$LANG['log_header_timestamp'] = 'Timestamp (UTC)';
$LANG['log_header_type'] = 'Event Type';
$LANG['log_header_user'] = 'User';
$LANG['log_header_event'] = 'Event';
$LANG['logfilterAbsence'] = 'Absence';
$LANG['logfilterAnnouncement'] = 'Announcement';
$LANG['logfilterConfig'] = 'Config';
$LANG['logfilterDatabase'] = 'Database';
$LANG['logfilterDaynote'] = 'Daynote';
$LANG['logfilterGroup'] = 'Groups';
$LANG['logfilterHoliday'] = 'Holiday';
$LANG['logfilterLogin'] = 'Login';
$LANG['logfilterLoglevel'] = 'Login';
$LANG['logfilterMonth'] = 'Month';
$LANG['logfilterPermission'] = 'Permissions';
$LANG['logfilterRegion'] = 'Region';
$LANG['logfilterRegistration'] = 'Registration';
$LANG['logfilterUser'] = 'User';

/**
 * Error Messages
 */
$LANG['err_input_caption'] = 'Input Validaton Error';
$LANG['err_input_hol_add'] = 'You have to specify a name, color and background color for the new Holiday.';
$LANG['err_input_abs_taken_1'] = 'The absence symbol \'';
$LANG['err_input_abs_taken_2'] = '\' is already taken. Choose a symbol that is not used yet.';
$LANG['err_input_abs_add'] = 'You have to add at least a symbol in order to add a new absence type.';
$LANG['err_input_abs_invalid_1'] = 'The absence symbol \'';
$LANG['err_input_abs_invalid_2'] = '\' is invalid. Choose an upper case character or a number from 0 to 9.';
$LANG['err_input_reg_invalid_1'] = 'The region name \'';
$LANG['err_input_reg_invalid_2'] = '\' is invalid. Choose upper or lower case characters or numbers from 0 to 9. Don\'t use blanks.';
$LANG['err_input_reg_add'] = 'You have to add at least a shortname in order to add a new region.';
$LANG['err_input_perm_invalid_1'] = 'The permission scheme name \'';
$LANG['err_input_perm_invalid_2'] = '\' is invalid. Choose upper or lower case characters or numbers from 0 to 9. Don\'t use blanks.';
$LANG['err_input_perm_exists_1'] = 'The permission scheme \'';
$LANG['err_input_perm_exists_2'] = '\' already exists. Use a different name or delete the old one first.';
$LANG['err_input_group_add'] = 'You have to add at least a name in order to add a new group.';
$LANG['err_input_max_daytype'] = 'You have reached the maximum amount of day types. Please delete one before you create a new one.';
$LANG['err_input_dbmaint_clean'] = 'Please provide both, year and month, for cleaning up old templates.';
$LANG['err_input_dbmaint_clean_confirm'] = 'You need to confirm the database cleanup by typing \'CLEANUP\' (using capital letters) in the confirmation field.';
$LANG['err_input_dbmaint_del'] = 'You need to confirm the database record deletion by typing \'DELETE\' (using capital letters) in the confirmation field.';
$LANG['err_input_daynote_nouser'] = 'The specified user does not exist.';
$LANG['err_input_daynote_date'] = 'Date: ';
$LANG['err_input_daynote_username'] = 'Username: ';
$LANG['err_input_daynote_save'] = 'You can\'t save an empty note. Please submit some text in the daynote field or use the [Delete] button if you want to delete this daynote.';
$LANG['err_input_daynote_create'] = 'You can\'t create an empty daynote. Please submit some text in the daynote field.';
$LANG['err_input_declbefore'] = 'You need to select a decline-before date.';
$LANG['err_input_period'] = 'You need to select a valid declination period. The start date must be before the end date.';

/**
 * User Profile Dialog
 */
$LANG['profile_group_hidden'] = '(hidden)';

/**
 * Year calendar
 */
$LANG['year_select_year'] = 'Select year';
$LANG['year_select_user'] = 'Select user';

/**
 * Calendar Display
 */
$LANG['cal_img_alt_edit_dayn'] = 'Edit daynotes for this person...';

/**
 * Daynote Edit Dialog
 */
$LANG['dayn_edit'] = 'Daynotes of ';

/**
 * Icon Upload Dialog
 */
$LANG['upload_type_avatar'] = 'Avatar Image';
$LANG['upload_type_icon'] = 'Icon';
$LANG['upload_type_homepage'] = 'Welcome Page Image';

/**
 * Declination Management Page
 */
$LANG['admin_decl_notify_options'] = 'Select who will be notified by e-mail in case a request is declined.';
$LANG['admin_decl_notify_options_ff'] = '(These settings have no affect if the adminstrator has disabled e-mail notifications globally.)';

/**
 * Announcement Page
 */
$LANG['ann_col_ann'] = 'Announcement';
$LANG['ann_col_action'] = 'Action';
$LANG['ann_confirm_all_confirm'] = 'Are you sure you want to confirm and remove all your announcements?';
$LANG['ann_no_ann'] = 'There are no announcements for you at this time.';
$LANG['btn_confirm_all'] = 'Confirm all';

/**
 * Database Maintenance
 */
$LANG['admin_dbmaint_del_pschemes'] = 'Delete custom permission schemes (except "Default")';

/**
 * Editcalendar
 */
$LANG['notification_new_template'] = 'New template for: ';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.001
 */

/**
 * Edit Profile Dialog
 */
$LANG['uo_showInGroups'] = 'Show in other groups';
$LANG['uo_showInGroups_comment'] = 'Show the calendar of this user in the following groups, even if not a member. Use this feature if the user is '.
'not a member but the absences are still important for other members of that group.';

/**
 * Calendar Display
 */
$LANG['cal_tt_related_1'] = 'This user is not member of the group ';
$LANG['cal_tt_related_2'] = ' but is configured to be shown as a group related user.';

/**
 * Error messages
 */
$LANG['err_unspecified_short'] = 'Unknown Error';
$LANG['err_unspecified_long'] = 'An unspecified error has been encountered.';
$LANG['err_notarget_short'] = 'Parameter Error';
$LANG['err_notarget_long'] = 'No target object has been specified. The page needs something to work with.';

/**
 * Configuration page
 */
$LANG['btn_styles'] = 'Rebuild style sheet';

/**
 * Declination Management Page
 */
$LANG['decl_title'] = 'Declination Management';
$LANG['decl_options'] = 'Declination Options';
$LANG['decl_activate'] = 'Activate';
$LANG['decl_threshold'] = 'Threshold Declination';
$LANG['decl_threshold_comment'] = 'You can setup absence requests declination when certain absences threshold are reached.';
$LANG['decl_threshold_value'] = 'Threshold in %';
$LANG['decl_based_on'] = 'Based on:';
$LANG['decl_base_all'] = 'All';
$LANG['decl_base_group'] = 'Group';
$LANG['decl_before'] = 'Decline Before';
$LANG['decl_before_comment'] = 'You can setup the declination of absence requests lying before a certain date. Select "Before today" to decline absence requests lying in the past.';
$LANG['decl_before_today'] = 'Before today (not including)';
$LANG['decl_before_date'] = 'Before date (not including)';
$LANG['decl_period'] = 'Declination Period';
$LANG['decl_period_comment'] = 'You can setup a declination period in which absence requests are declined. The start and end date you pick here are included in that period.';
$LANG['decl_period_start'] = 'Start date (included)';
$LANG['decl_period_end'] = 'End date (included)';
$LANG['decl_notify'] = 'Declination Notifications';
$LANG['decl_notify_comment'] = 'Select here who will be notified via e-mail in case a declination occurs.';
$LANG['decl_notify_user'] = 'Requesting user';
$LANG['decl_notify_manager'] = 'Group Manager';
$LANG['decl_notify_director'] = 'Director(s)';
$LANG['decl_notify_admin'] = 'Administrator';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.002
 */

/*
 * Log page
 */
$LANG['log_tt_notallowed'] = 'This event type is currently logged. You are not allowed to change this setting.';
$LANG['log_btn_clearlog'] = 'Clear system log';
$LANG['log_clear_confirm'] = 'Are you sure you want to clear the system log? All entries will be deleted.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.000
 */

/*
 * Absence type page
 */
$LANG['abs_sel_abs'] = 'Select absence';
$LANG['abs_sel_confirm'] = "Are you sure you want to select this absence type?\\nAll changes to the current one that have not been applied will be lost.";
$LANG['abs_del_confirm'] = "Are you sure you want to delete this absence type: ";
$LANG['abs_create_abs'] = 'Create absence';
$LANG['abs_title'] = 'Absence type settings for \'';
$LANG['abs_help_title'] = 'Absence type settings';
$LANG['abs_sample'] = 'Sample display';
$LANG['abs_sample_desc'] = 'This is what your absence type will look in your calendar based on your current settings after they have been saved. This sample always uses the default symbol "A".';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_desc'] = 'The absence type name is used in lists and descriptions and should tell what this absence type is about, e.g. "Duty trip". It can be 80 characters long.';
$LANG['abs_symbol'] = 'Symbol';
$LANG['abs_symbol_desc'] = 'The absence type symbol is used in the calendar display if no icon is set for this absence type. It is also used in '.
'notification e-mails. Choose a single character. A symbol is mandatory for each absence type, however, you are not restricted and can use the same '.
'character for mutliple absence types. The default is "A".';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_icon_desc'] = 'The absence type icon is used in the calendar display. If no icon is set for this absence type, the symbol will be used instead.';
$LANG['abs_color'] = 'Text color';
$LANG['abs_color_desc'] = 'In case the character symbol is used, this is the color it is displayed in. Click into the field to open the color picker.';
$LANG['abs_bgcolor'] = 'Background color';
$LANG['abs_bgcolor_desc'] = 'This is the background color used for this absence type, independent from symbol or icon. Click into the field to open the color picker.';
$LANG['abs_factor'] = 'Factor';
$LANG['abs_factor_desc'] = 'TeamCal can count the amount of days taken per absence type. You can find the results in the "Absence" tab of the user '.
'profile dialog. The "Factor" field here offers the option to multiply each found absence with a value of your choice. The default is 1.<br>'.
'Example: You create an absence type called "Half Day Training". You would want to assign it the factor 0.5 in order to get the total count of '.
'training days. An employee that has taken 10 half training days would end up with a total of 5 (10 * 0.5 = 5).<br>'.
'Setting the factor to 0 will exclude the absence type from the count.';
$LANG['abs_allowance'] = 'Allowance';
$LANG['abs_allowance_desc'] = 'Set an allowance for this absence type per year here. This amount refers to the current calendar year. When displaying '.
'a user profile the absence count section will contain the remaining amount for this absence type for the user (A negative value will indicate that the '.
'user has used too many absence days of this type.). If allowance is set to 0 no limit is assumed.';
$LANG['abs_show_in_remainder'] = 'Show in remainder';
$LANG['abs_show_in_remainder_desc'] = 'The Calendar Display offers an expandable section to display the remaining allowance for each absence type for '.
'each user for the current year. Use this switch to decide which absence types will be included in that display. If none of the absence types are '.
'marked for display in the remainder section then no expand/collapse button will be visible in the calendar display even though showing the remainder '.
'is generally switched on.<br>'.
'Note: It does not seem to make sense to include an absence type in the remainder display when the Factor is set to 0. The allowance and remaining allowance will always be the same.';
$LANG['abs_show_totals'] = 'Show totals';
$LANG['abs_show_totals_desc'] = 'The remainder section can be configured to also include a totals display for the current month. This totals '.
'section shows the sums of each absence type taken for the month displayed. Use this switch to include this absence type in that section. '.
'If none of the absence types are marked for display in the totals section then the totals section will not be shown at all.';
$LANG['abs_approval_required'] = 'Approval required';
$LANG['abs_approval_required_desc'] = 'Checking this box defines that this absence type requires approval by the group manager, director or '.
'administrator. A regular user choosing this absence type in his calendar will receive an error message telling him so. The group manager of '.
'this user will receive an e-mail informing him that his approval is required for this request. He can then enter this absence for the user '.
'if he approves it.';
$LANG['abs_counts_as_present'] = 'Counts as present';
$LANG['abs_counts_as_present_desc'] = 'Checking this box defines that this absence type counts as "present". Let\'s say you maintain an absence '.
'type "Home Office" but since this person is working you do not want to count this as "absent". In that case check the box and all Home Office '.
'absences count as present in the summary count section. Thus, "Home Office" is also not listed in the absence type list in the summary count.';
$LANG['abs_manager_only'] = 'Management only';
$LANG['abs_manager_only_desc'] = 'Checking this box defines that this absence type is only available to directors and managers. A regular '.
'member can see this absence type in his calendar but setting them will be refused. Only his manager or the director can check the boxes for him. '.
'This feature comes in handy if only the manager or director is supposed to manage this absence, e.g. vacation.';
$LANG['abs_hide_in_profile'] = 'Hide in profile';
$LANG['abs_hide_in_profile_desc'] = 'Checking this box defines that regular users cannot see this absence type on the Absences tab of their profile. '.
'Only Managers, Directors or Administrators will see it there. This feature is useful if a manager wants to use an absence type for tracking '.
'purposes only or if the remainders are of no interest to regular users.';
$LANG['abs_confidential'] = 'Confidential';
$LANG['abs_confidential_desc'] = 'Checking this box marks this absence type a "confidential". The public and regular users cannot see this absence '.
'in the calendar, except if it is the regular user\'s own absence. This feature is useful if you want to hide sensitive absence types from regular users.';
$LANG['abs_groups'] = 'Group assignments';
$LANG['abs_groups_desc'] = 'Select the groups for which this absence type is valid. If a group is not assigned, members of that group cannot use '.
'this absence type.';

/**
 * Error Messages
 */
$LANG['err_input_abs_no_name'] = 'You have to enter a name for the new absence type.';
$LANG['err_input_abs_name'] = 'Please use only alphanumeric characters, blanks, hyphens and underscores in the absence type name.';
$LANG['err_input_abs_symbol'] = 'Please use only alphanumeric characters and any of -=+*#$%&*()_ in the absence type symbol.';
$LANG['err_input_abs_color'] = 'Please use only hexadecimal characters as color and background color values.';
$LANG['err_input_abs_factor'] = 'Please use a float number for Factor.';
$LANG['err_input_abs_allowance'] = 'Please use a decimal number for Allowance.';

/**
 * Edit Calendar
 */
$LANG['month_current_absence'] = 'Current absence';

/**
 * Database maintenance
 */
$LANG['admin_dbmaint_cleanup_chkOptimize'] = 'Optimize tables';

/**
 * Config page
 */
$LANG['admin_config_mail_smtp_ssl'] = 'SMTP TLS/SSL protocol';
$LANG['admin_config_mail_smtp_ssl_comment'] = 'Use the TLS/SSL protocol for the SMTP connection';
$LANG['admin_config_jqtheme'] = 'jQuery Theme';
$LANG['admin_config_jqtheme_comment'] = 'TeamCal Pro uses jQuery, a popular collection of Javascript utilities. jQuery offers themes as well '.
'used for the display of the tabbed dialogs and other features. The default theme is "base" which is a neutral gray shaded theme. '.
'Try more from the list, some of them are quite colorful. This is a global setting, users cannot choose an indiviual jQuery theme.';

/**
 * Message Dialog
 */
$LANG['message_title'] = 'TeamCal Pro Message Center';
$LANG['message_type'] = 'Message Type';
$LANG['message_type_desc'] = 'Choose the type of message you want to send. A silent announcement will be put on the announcement page only. '.
'A popup announcement will be put on the announcement page and the announcement page will be shown for every recipient upon login.';
$LANG['message_type_email'] = 'e-mail';
$LANG['message_type_announcement_silent'] = 'Silent announcement';
$LANG['message_type_announcement_popup'] = 'Popup announcement';
$LANG['message_sendto'] = 'Recipient';
$LANG['message_sendto_desc'] = 'Select the recipient(s) of this message.';
$LANG['message_sendto_all'] = 'All';
$LANG['message_sendto_group'] = 'Group:';
$LANG['message_sendto_user'] = 'User:';
$LANG['message_msg'] = 'Message';
$LANG['message_msg_desc'] = 'Enter the subject and your message here.';
$LANG['message_msg_subject'] = 'Subject';
$LANG['message_msg_subject_sample'] = 'TeamCal Pro Message';
$LANG['message_msg_body'] = 'Body';
$LANG['message_msg_body_sample'] = '...your text here...';
$LANG['message_msgsent'] = 'Your message was sent.';
$LANG['message_sendto_err'] = 'You have to select at least one user to send the message to.';

/**
 * Tipsy Tooltip
 */
$LANG['tt_title_userinfo'] = 'User Information';
$LANG['tt_title_userdayinfo'] = 'User Day Information';
$LANG['tt_title_dayinfo'] = 'Day Information';
$LANG['tt_edit_profile'] = 'Edit user profile...';
$LANG['tt_view_profile'] = 'View user profile...';

/**
 * HTML titles
 */
$LANG['html_title_absences'] = 'Absences';
$LANG['html_title_addprofile'] = 'Add Profile';
$LANG['html_title_announcement'] = 'Announcements';
$LANG['html_title_calendar'] = 'Calendar';
$LANG['html_title_config'] = 'Configuration';
$LANG['html_title_database'] = 'Database';
$LANG['html_title_daynote'] = 'Daynote';
$LANG['html_title_declination'] = 'Declination';
$LANG['html_title_editcalendar'] = 'Edit Calendar';
$LANG['html_title_editmonth'] = 'Edit Month';
$LANG['html_title_editprofile'] = 'Edit Profile';
$LANG['html_title_environment'] = 'Environment';
$LANG['html_title_error'] = 'Error';
$LANG['html_title_eportdata'] = 'Export Data';
$LANG['html_title_groupassign'] = 'Group Assignments';
$LANG['html_title_groups'] = 'Groups';
$LANG['html_title_holidays'] = 'Holidays';
$LANG['html_title_homepage'] = 'Home';
$LANG['html_title_legend'] = 'Legend';
$LANG['html_title_log'] = 'Log';
$LANG['html_title_login'] = 'Login';
$LANG['html_title_message'] = 'Message Center';
$LANG['html_title_permissions'] = 'Permissions';
$LANG['html_title_phpinfo'] = 'PHP Info';
$LANG['html_title_regions'] = 'Regions';
$LANG['html_title_register'] = 'Register';
$LANG['html_title_showyear'] = 'Year Calendar';
$LANG['html_title_statistics'] = 'Statistics';
$LANG['html_title_upload'] = 'Upload';
$LANG['html_title_userimport'] = 'User Import';
$LANG['html_title_userlist'] = 'User List';
$LANG['html_title_verify'] = 'User Verification';
$LANG['html_title_viewprofile'] = 'View Profile';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.000
 */

/**
 * Calendar Edit Dialog
 */
$LANG['cal_clear_absence'] = 'Clear absence';

/**
 * Calendar View
 */
$LANG['cal_fastedit'] = 'Fast Edit';
$LANG['cal_fastedit_tt'] = 'Fast edit absences for this day...';
$LANG['cal_abs_present'] = 'Present';

/**
 * Config page
 */
$LANG['admin_config_fastedit'] = 'Fast Edit';
$LANG['admin_config_fastedit_comment'] = 'With this option enabled, an additional row will be displayed at the bottom of the calendar with an icon '.
'button for each day. Clicking it will show an absence drop down list for each user for that day. An absence type can be selected and the [Apply] '.
'button will save the selected absences right away. Note, that no declination check will be performed with Fast Edit. This feature here is rather meant '.
'for managers. However, you can of course enable the corresponding permission for regular users as well.<br>'.
'<br><strong>Attention</strong>: Fast Edit may create a large amount of $_POST input variables, depending on the number of users. Check the <i>max_input_vars</i> '.
'value in your php.ini. It is commonly set to 1000, i.e. you will reach that amount if you maintain around 20 users. Fast Edit will not work anymore '.
'if that value is exceeded.';

/**
 * Permissions page
 */
$LANG['perm_perm_viewFastEdit_title'] = "Allow Fast Edit";
$LANG['perm_perm_viewFastEdit_desc'] = "Allows to access the Fast Edit feature in the calendar view if it is enabled in the TeamCal Pro configuration.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.001
 */

/**
 * Month Edit Dialog
 */
$LANG['month_clear_holiday'] = 'Clear Holiday';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.002
 */

/**
 * Statistics page
 */
$LANG['stat_choose_group'] = 'Group';
$LANG['stat_choose_absence'] = 'Absence';

/**
 * Config page
 */
$LANG['admin_config_usermanual'] = 'User Manual';
$LANG['admin_config_usermanual_comment'] = 'TeamCal Pro\'s user manual is maintained in English and is available at the TeamCal Pro community site. '.
'However, translations might be available authored by the community. If your language is available, change the link to it here.<br>'.
'If you are interested in participating or creating a translation, register at the <a href="https://georgelewe.atlassian.net" target="_blank">'.
'TeamCal Pro community site (https://georgelewe.atlassian.net)</a> and create a task in the issue tracker for it.<br>'.
'If you leave this field empty, TeamCal Pro will insert the default link.';
$LANG['admin_config_lang'] = 'Default Language';
$LANG['admin_config_lang_comment'] = 'TeamCal Pro is distributed in English and German. The administrator might have added more languages. '.
'Chose the default language of your installation here.';

/**
 * About page
 */
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'License';
$LANG['about_credits'] = 'Credits';
$LANG['about_for'] = 'for';
$LANG['about_misc'] = 'many users for testing and suggesting...';

/**
 * Menu bar
 */
$LANG['mnu_announcements'] = 'You\'ve got announcements. Click to read them...';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.005
 */

/**
 * Config page
 */
$LANG['admin_config_loglang'] = "Log Language";
$LANG['admin_config_loglang_comment'] = "This setting sets the language for the system log entries.";

/**
 * Edit calendar page
 */
$LANG['err_decl_manager_only'] = "' can only be set/unset by managers.";
$LANG['abs_info_approval_required'] = "This absence type requires management approval. ";
$LANG['abs_info_manager_only'] = "This absence type can only be set/unset by managers. ";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.006
 */

/**
 * Config page
 */
$LANG['admin_config_hideManagerOnlyAbsences'] = 'Hide Management Only Absences';
$LANG['admin_config_hideManagerOnlyAbsences_comment'] = 'Absence types can be marked as "manager-only", making them only editable to managers. 
      These absences are shown to the regular users but they cannot edit them. You can hide these absences to regular users here.';
$LANG['admin_config_presenceBase'] = 'Presence Statistics Base';
$LANG['admin_config_presenceBase_comment'] = 'The statistics page also counts presence days per month. Check here whether that count shall
      be based on calendar days per month or business days per month. E.g. "Business days": If a user is present throghout all June, his presence
      count would be 20 because June has 20 business days. The count would be 30 based on "Calendar days".';
$LANG['admin_config_presenceBase_calendar'] = 'Calendar days';
$LANG['admin_config_presenceBase_business'] = 'Business days';

/**
 * Statistics Page
 */
$LANG['stat_days'] = 'days';

/**
 * Profile Page
 */
$LANG['ut_assistant'] = 'Assistant';

/**
 * Permission scheme page
 */
$LANG['perm_col_assistant'] = "Assistant";
$LANG['perm_col_assistant_tt'] = "Assistants can perform this action.";

/**
 * Status Bar
 */
$LANG['status_ut_assistant'] = "Assistant";

/**
 * Admin Pages
 */
$LANG['icon_assistant'] = 'Assistant';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.008
 */

/**
 * Month Dialog
 */
$LANG['month_global_daynote'] = 'Global Daynote';
$LANG['month_personal_daynote'] = 'Personal Daynote';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.009
 */

/**
 * Configuration Page
 */
$LANG['admin_config_emailnopastnotifications'] = 'No Past E-mail Notifications';
$LANG['admin_config_emailnopastnotifications_comment'] =
'Enable/Disable e-mail notifications for calendar changes that are entirely in the past. This setting can be useful ' .
'if you do cleanup work for past absences and don\'t want e-mails to be sent out for those changes. But as soon as one '.
'of the changed dates is for today or in the future the e-mails will be sent.';

$LANG['admin_config_user_search'] = 'Show User Search Box';
$LANG['admin_config_user_search_comment'] =
'Enable/Disable a user search box in the Calendar view, enabling to search for single users.';

$LANG['admin_config_avatarmaxsize'] = 'Avatar Max Size';
$LANG['admin_config_avatarmaxsize_comment'] =
'Specifies the maximum files size in bytes for the avatar image file.';

/**
 * Calendar page
 */
$LANG['cal_user_search'] = 'User';

/**
 * Absence type page
 */
$LANG['abs_counts_as'] = 'Counts as';
$LANG['abs_counts_as_desc'] = 'Select whether taken absences of this type count against the allowance of another absence type. ' . 
'If you select any other absence type the allowance of this absence type is not taken into account, but the allowance of the selected one.<br> ' .
'Example: "Vacation half day" with factor 0.5 counts against the allowance of "Vacation".';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.011
 */

/**
 * Declination Page
 */
$LANG['decl_applyto'] = 'Apply Declination To';
$LANG['decl_applyto_comment'] =
'Select whether the declination management will apply to regular users only or to managers and directors too. Declination management does not apply to administrators.';
$LANG['decl_applyto_regular'] = 'Regular users only';
$LANG['decl_applyto_all'] = 'All users (but administrators)';

/**
 * User list page
 */
$LANG['tab_active_users'] = 'Active Users';
$LANG['tab_archived_users'] = 'Archived Users';
$LANG['select_all'] = 'Select all';
$LANG['btn_delete_selected'] = 'Delete selected';
$LANG['btn_archive_selected'] = 'Archive selected';
$LANG['btn_restore_selected'] = 'Restore selected';
$LANG['btn_reset_password_selected'] = 'Reset password of selected';
$LANG['user_archive_confirm'] = 'Are you sure you want to archive the selected users?';
$LANG['user_restore_confirm'] = 'Are you sure you want to restore the selected users?';
$LANG['confirmation_success'] = 'Success';
$LANG['confirmation_failure'] = 'Problem';
$LANG['confirmation_delete_selected_users'] = 'The selected users were deleted.';
$LANG['confirmation_archive_selected_users'] = 'The selected users were archived.';
$LANG['confirmation_archive_selected_users_failed'] = 'One or more of the selected users already exist in the archive. This could be the same user or one with the same username.<br>Please delete these archived users first.';
$LANG['confirmation_restore_selected_users'] = 'The selected users were restored.';
$LANG['confirmation_restore_selected_users_failed'] = 'One or more of the selected users already exist as active users. This could be the same user or one with the same username.<br>Please delete these active users first.';
$LANG['confirmation_reset_password_selected'] = 'The passwords of selected users were reset and a corresponding e-mail was sent to them.';

/**
 * Absence list page
 */
$LANG['abs_list_title'] = 'Absence Types';
$LANG['abs_counts_as'] = 'Counts as';
$LANG['confirmation_delete_selected_absences'] = 'The selected absence types were deleted.';
$LANG['abs_delete_confirm'] = 'Are you sure you want to delete the selected absence types?';
$LANG['btn_abs_list'] = 'Show list view';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_tab_cleanup'] = "Cleanup";
$LANG['admin_dbmaint_tab_delete'] = "Delete Records";
$LANG['admin_dbmaint_tab_export'] = "Export";
$LANG['admin_dbmaint_tab_restore'] = "Restore";
$LANG['admin_dbmaint_cleanup_note'] = 'Note: The database cleanup will not delete any archived records.';
$LANG['admin_dbmaint_cleanup_success'] = "All cleanup activities have been completed.";
$LANG['admin_dbmaint_del_chkArchive'] = 'Clear archive tables';
$LANG['admin_dbmaint_del_confirm_popup'] = "The selected records have been deleted.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.011
 */

/**
 * Regions page
 */
$LANG['region_ical_in'] = '" imported as new region: ';

/**
 * Messages
 */
$LANG['information'] = 'TeamCal Pro Information';
$LANG['success'] = 'TeamCal Pro Success';
$LANG['warning'] = 'TeamCal Pro Warning';
$LANG['error'] = 'TeamCal Pro Error';
$LANG['err_avatar_upload'] = 'An error occurred while uploading your avatar.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.012
 */

/**
 * Regions page
 */
$LANG['region_ical_into_region'] = 'Import iCal into existing region';
$LANG['region_ical_select_region'] = 'Select the region into which the iCal events will be imported.';
$LANG['region_ical_in_existing'] = '" imported into existing region: ';
$LANG['msg_ical_import_caption'] = 'iCal Import';
$LANG['msg_ical_import_text'] = 'iCal file "';
$LANG['msg_region_merge_text'] = 'These regions were merged: ';

/**
 * Config page
 */
$LANG['admin_config_userregion'] = 'Show regional holidays per user';
$LANG['admin_config_userregion_comment'] =
'If this option is on, the calendar will show the regional holidays in each user row based on the default region set for the user. These holidays might then differ from the  
global regional holidays shown in the month header. This offers a better view on regional holiday differences if you manage users from different regions. Note, that this 
might be a bit confusing depending on the amount of users and regions. Check it out and pick your choice.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.016
 */

/**
 * Error messages
 */
$LANG['err_input_group_update'] = 'You have to enter a group name.';

/**
 * Config page
 */
$LANG['admin_config_showRangeInput'] = 'Show Range Input';
$LANG['admin_config_showRangeInput_comment'] = 'Check to show the Range Input section on the Edit Calendar page.';
$LANG['admin_config_showRecurringInput'] = 'Show Recurring Input';
$LANG['admin_config_showRecurringInput_comment'] = 'Check to show the Recurring Input section on the Edit Calendar page.';
$LANG['admin_config_showCommentReason'] = 'Show Comment/Reason';
$LANG['admin_config_showCommentReason_comment'] = 'Check to show the Comment/Reason section on the Edit Calendar page.';

/**
 * Absence page
 */
$LANG['abs_admin_allowance'] = 'Admin allowance';
$LANG['abs_admin_allowance_desc'] = 'Check to only allow the admin to change the allowance for this type in the "Absences" tab of the Edit Profile dialog.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.017
 */

/**
 * Absence page
 */
$LANG['abs_bgtransparent'] = 'Background transparent';
$LANG['abs_bgtransparent_desc'] = 'With this option checked, the background color will be ignored.';

/**
 * Common
 */
$LANG['default']='Default';

/**
 * Config page
 */
$LANG['admin_config_appLogo'] = 'Application logo';
$LANG['admin_config_appLogo_comment'] = 'You can select a logo from the "img" directory of the current Theme folder here. Default is "logo.gif" that comes with TeamCal Pro.  
If an own logo was created and copied into the "img" directory of the theme folder, it can be selected here. The default logo has the dimensions of 264 x 55 pixels.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.018
 */

/**
 * Config page
 */
$LANG['admin_config_charset'] = 'Character Set';
$LANG['admin_config_charset_comment'] = 'You can specify the HTML character set here. The default is "UTF-8". You can change that to "ISO-8859-1".';

/**
 * Error page
 */
$LANG['err_not_authorized_login'] = 'If you have an account, try again by using the button below.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.019
 */

/**
 * Password Check
 */
$LANG['pwchk_username'] = 'You must specify a valid username.<br>';
$LANG['pwchk_confirm'] = 'Either the new password or its confirmation is missing.<br>';
$LANG['pwchk_mismatch'] = 'The new password and its confirmation do not match.<br>';
$LANG['pwchk_minlength'] = 'The password must be at least ' . $LC->readConfig("pwdLength") . ' characters long.<br>';
$LANG['pwchk_notusername'] = 'The new password cannot contain the username.<br>';
$LANG['pwchk_notusername_backwards'] = 'The new password cannot contain the username backwards.<br>';
$LANG['pwchk_notold'] = 'The new password cannot be the old one.<br>';
$LANG['pwchk_number'] = 'The password must contain a number.<br>';
$LANG['pwchk_lower'] = 'The password must contain a lower case letter.<br>';
$LANG['pwchk_upper'] = 'The password must contain an UPPER case letter.<br>';
$LANG['pwchk_punctuation'] = 'The password must contain a punctuation character.<br>';
?>