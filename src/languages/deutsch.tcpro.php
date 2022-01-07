<?php
/**
 * deutsch.tcpro.php
 *
 * German language file
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
$LANG['monthnames'] = array(1=>"Januar","Februar","M&auml;rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
$LANG['weekdays']   = array(1=>"Mo","Di","Mi","Do","Fr","Sa","So");
$LANG['weekdays_long']   = array(1=>"Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag");
$LANG['date_picker']   = 'Datum ausw&auml;hlen...';
$LANG['date_picker_tt'] = "Klicke hier f&uuml;r den Datumsauswahl Dialog. Das Datum wird in der Form JJJJ-MM-TT angezeigt.";
$LANG['color_picker']   = 'Datum ausw&auml;hlen...';
$LANG['color_picker_tt'] = "Klicke hier f&uuml;r den Farbauswahl Dialog. Die Farben werden in dieser Ansicht als Hexadezimalwerte angezeigt.";

/**
 * Menu
 */
$LANG['mnu_teamcal'] = 'TeamCal';
$LANG['mnu_teamcal_login'] = 'Login';
$LANG['mnu_teamcal_register'] = 'Registrieren';
$LANG['mnu_teamcal_logout'] = 'Logout';
$LANG['mnu_view'] = 'Ansicht';
$LANG['mnu_view_homepage'] = 'Startseite';
$LANG['mnu_view_calendar'] = 'Kalender';
$LANG['mnu_view_yearcalendar'] = 'Jahreskalender';
$LANG['mnu_view_announcement'] = 'Ank&uuml;ndigungen';
$LANG['mnu_view_statistics'] = 'Statistik';
$LANG['mnu_view_statistics_g'] = 'Globale Statistik';
$LANG['mnu_view_statistics_r'] = 'Verbleibend Aktuelles Jahr';
$LANG['mnu_tools'] = 'Optionen';
$LANG['mnu_tools_profile'] = 'User Profil';
$LANG['mnu_tools_message'] = 'Nachricht';
$LANG['mnu_tools_webmeasure'] = 'webMeasure';
$LANG['mnu_tools_admin'] = 'Administration';
$LANG['mnu_tools_admin_config'] = 'TeamCal Konfiguration';
$LANG['mnu_tools_admin_perm'] = "Berechtigungsschema";
$LANG['mnu_tools_admin_users'] = 'Nutzer';
$LANG['mnu_tools_admin_groups'] = 'Gruppen';
$LANG['mnu_tools_admin_usergroups'] = 'Gruppenzuordnung';
$LANG['mnu_tools_admin_absences'] = 'Abwesenheitstypen';
$LANG['mnu_tools_admin_absences_edit'] = 'Abwesenheitstypen bearbeiten';
$LANG['mnu_tools_admin_regions'] = 'Regionen';
$LANG['mnu_tools_admin_holidays'] = 'Feiertagstypen';
$LANG['mnu_tools_admin_declination'] = 'Ablehnungs Management';
$LANG['mnu_tools_admin_database'] = 'Datenbank Verwaltung';
$LANG['mnu_tools_admin_systemlog'] = 'System Log';
$LANG['mnu_tools_admin_env'] = 'Umgebung';
$LANG['mnu_tools_admin_phpinfo'] = 'PHP Info';
$LANG['mnu_help'] = 'Hilfe';
$LANG['mnu_help_legend'] = 'Legende';
$LANG['mnu_help_help'] = 'Nutzerhandbuch';
$LANG['mnu_help_about'] = '&Uuml;ber TeamCal Pro';

/**
 * Filter
 */
$LANG['nav_groupfilter'] = 'Gruppe:';
$LANG['nav_language'] = 'Sprache:';
$LANG['nav_start_with'] = 'Start:';
$LANG['drop_group_all'] = 'Alle';
$LANG['drop_group_allbygroup'] = 'Alle (nach Gruppen)';
$LANG['drop_show_1_months'] = '1 Monat zeigen';
$LANG['drop_show_2_months'] = '2 Monate zeigen';
$LANG['drop_show_3_months'] = '3 Monate zeigen';
$LANG['drop_show_6_months'] = '6 Monate zeigen';
$LANG['drop_show_12_months'] = '12 Monate zeigen';

/**
 * Buttons
 */
$LANG['btn_activate'] = "Aktivieren";
$LANG['btn_add'] = 'Hinzuf&uuml;gen';
$LANG['btn_apply'] = 'Anwenden';
$LANG['btn_assign'] = 'Zuordnen';
$LANG['btn_assign_all'] = 'Allen zuordnen';
$LANG['btn_backup'] = 'Sichern';
$LANG['btn_cancel'] = 'Abbrechen';
$LANG['btn_clear'] = 'Entfernen';
$LANG['btn_close'] = 'Schlie&szlig;en';
$LANG['btn_confirm'] = 'Best&auml;tigen';
$LANG['btn_create'] = 'Anlegen';
$LANG['btn_delete'] = 'L&ouml;schen';
$LANG['btn_delete_records'] = 'Datens&auml;tze l&ouml;schen';
$LANG['btn_done'] = 'Fertig';
$LANG['btn_edit'] = 'Editieren';
$LANG['btn_edit_profile'] = 'Profil bearbeiten';
$LANG['btn_export'] = 'Export';
$LANG['btn_help'] = 'Hilfe';
$LANG['btn_icon'] = 'Icon...';
$LANG['btn_import'] = 'Import';
$LANG['btn_import_ical'] = 'iCal importieren';
$LANG['btn_install'] = 'Installation';
$LANG['btn_login'] = 'Login';
$LANG['btn_logout'] = 'Logout';
$LANG['btn_merge'] = 'Verschmelzen';
$LANG['btn_next'] = 'N&auml;ch';
$LANG['btn_prev'] = 'Vorh';
$LANG['btn_refresh'] = 'Aktualisieren';
$LANG['btn_remove'] = 'Entfernen';
$LANG['btn_reset'] = 'Zur&uuml;cksetzen';
$LANG['btn_reset_password'] = 'Passwort zur&uuml;cksetzen';
$LANG['btn_restore'] = 'Wiederherstellen';
$LANG['btn_save'] = 'Speichern';
$LANG['btn_search'] = 'Suchen';
$LANG['btn_select'] = "Ausw&auml;hlen";
$LANG['btn_send'] = 'Senden';
$LANG['btn_submit'] = 'Abschicken';
$LANG['btn_switch'] = 'Anwenden';
$LANG['btn_update'] = 'Aktualisieren';
$LANG['btn_upload'] = 'Hochladen';

/**
 * Calendar Display
 */
$LANG['cal_caption_weeknumber'] = 'Kalenderwoche';
$LANG['cal_caption_name'] = 'Name';
$LANG['cal_img_alt_edit_month'] = 'Feiertage f&uuml;r diesen Monat editieren...';
$LANG['cal_img_alt_edit_cal'] = 'Kalendar f&uuml;r diese Person editieren...';
$LANG['cal_birthday'] = 'Geburtstag';
$LANG['cal_age'] = 'Alter';
$LANG['sum_summary'] = 'Zusammenfassung';
$LANG['sum_present'] = 'Anwesend';
$LANG['sum_absent'] = 'Abwesend';
$LANG['sum_delta'] = 'Delta';
$LANG['sum_absence_summary'] = 'Abwesenheiten im einzelnen';
$LANG['sum_business_day_count'] = 'Arbeitstage';
$LANG['remainder'] = 'Resttage';
$LANG['exp_summary'] = 'Zusammenfassung einblenden...';
$LANG['col_summary'] = 'Zusammenfassung ausblenden...';
$LANG['exp_remainder'] = 'Resttage einblenden...';
$LANG['col_remainder'] = 'Resttage ausblenden...';

/**
 * Edit Calendar Dialog
 */
$LANG['member_edit'] = 'Kalender von ';
$LANG['cal_clear_confirm'] = "Bist du sicher, dass du alle Eintr&auml;ge best&auml;tigen und l&ouml;schen willst?\\r\\n";
$LANG['cal_range_within'] = "Der Bereich muss innerhalb des aktuell editierten Monats sein!";
$LANG['cal_range_start'] = "Das Endedatum kann nicht vor dem Startdatum liegen.";
$LANG['cal_range_title'] = "Zeitraum Eingabe (innerhalb diesen Monats)";
$LANG['cal_range_type'] = "Abwesenheitstyp:";
$LANG['cal_range_from'] = "Von:";
$LANG['cal_range_to'] = "Bis:";
$LANG['cal_recurring_title'] = "Wiederkehrende Eingabe (innerhalb diesen Monats)";
$LANG['cal_reason_title'] = "Kommentar/Begr&uuml;ndung (wird in Benachrichtigungsmails aufgef&uuml;hrt)";
$LANG['cal_reason_dummy'] = "Gib einen Kommentar/Grund der Abwesenheitsanfrage hier ein...";

/**
 * Edit Groups Dialog
 */
$LANG['edit_groups'] = 'Gruppen bearbeiten';
$LANG['column_shortname'] = 'Kurzname';
$LANG['column_description'] = 'Beschreibung';
$LANG['column_hide'] = 'Verbergen';
$LANG['column_action'] = 'Aktion';
$LANG['eg_delete_confirm'] = 'Bist du sicher, dass du diese Gruppe l&ouml;schen willst?';

/**
 * Edit Absence Dialog
 */
$LANG['edit_absence'] = "Abwesenheits Symbole";
$LANG['ea_column_name'] = "Name";
$LANG['ea_column_symbol'] = "Symbol";
$LANG['ea_column_color'] = "Text";
$LANG['ea_column_bgcolor'] = "Hinterg.";
$LANG['ea_column_allowance'] = "#";
$LANG['ea_column_factor'] = "*";
$LANG['ea_column_showremain'] = "R";
$LANG['ea_column_approval'] = "G";
$LANG['ea_column_icon'] = "Symbol";
$LANG['ea_column_groups'] = "Gruppen";
$LANG['ea_column_action'] = "Aktion";
$LANG['ea_color_help'] = "Gib bitte die Farben in Hex-Werten ein, z.B. 000000. ";
$LANG['ea_color_help'] .= "Die ersten beiden Stellen geben den Rot Anteil an, die zweiten ";
$LANG['ea_color_help'] .= "beiden Stellen den Gr&uuml;n Anteil, die letzten beiden Stellen den Blau Anteil. ";
$LANG['ea_color_help'] .= "Kombinationen mischen die Farben entsprechend.<br>";
$LANG['ea_color_help'] .= "Beispiele: 000000 = Schwarz, FFFFFF = Weiss, FF0000 = Rot, 00FF00 = Grün, 0000FF = Blau, 888888 = Grau.<br>";
$LANG['ea_delete_confirm'] = "Bist du sicher, dass du diesen Abwesenheits-Typ l&ouml;schen willst?\\r\\n";
$LANG['ea_delete_confirm'] .= "Alle Vorkommen eines gel&ouml;schten Typs werden bei ALLEN Usern\\r\\n";
$LANG['ea_delete_confirm'] .= "durch \'anwesend\' ersetzt.";
$LANG['ea_groups_all'] = "Alle";
$LANG['ea_groups_selection'] = "Auswahl";
$LANG['ea_tt_upload'] = "Klicke hier f&uuml;r den Upload Dialog um Bilder hochzuladen. Abwesenheitssymbole werden immer in einer Gr&ouml;&szlig;e von 16*16 Pixel angezeigt. Nach dem Hochladen aktualisiere diese Seite, damit die Datei hier ausw&auml;lbar ist.";
$LANG['ea_tt_icon'] = "Dies ist das Icon f&uuml;r diesen Abwesenheitstyp. Es wird im Kalendar anstatt seines Buchstabens angezeigt.";
$LANG['ea_tt_groups'] = "Klicke hier fuer den Zuordnungsdialog, in dem Abwesenheitstypen nur bestimmten Gruppen zugeordnet werden k&ouml;nnen.";

/**
 * Edit Day Types Dialog
 */
$LANG['edit_daytypes'] = "Feiertage";
$LANG['ed_column_name'] = "Name";
$LANG['ed_column_symbol'] = "Symbol";
$LANG['ed_column_color'] = "Text";
$LANG['ed_column_bgcolor'] = "Hinterg.";
$LANG['ed_column_businessday'] = "Werktag";
$LANG['ed_column_action'] = "Aktion";
$LANG['ed_color_help'] = "Gib bitte die Farben in Hex-Werten ein, z.B. 000000. ";
$LANG['ed_color_help'] .= "Die ersten beiden Stellen geben den Rot Anteil an, die zweiten ";
$LANG['ed_color_help'] .= "beiden Stellen den Gr&uuml;n Anteil, die letzten beiden Stellen den Blau Anteil. ";
$LANG['ed_color_help'] .= "Kombinationen mischen die Farben entsprechend.<br>";
$LANG['ed_color_help'] .= "Beispiele: 000000 = Schwarz, FFFFFF = Weiss, FF0000 = Rot, 00FF00 = Grün, 0000FF = Blau, 888888 = Grau.<br>";
$LANG['ed_delete_confirm'] = "Bist du sicher, dass du diesen Tages-Typ l&ouml;schen willst?\\r\\n";

/**
 * Legend Dialog
 */
$LANG['teamcal_legend'] = 'TeamCal Pro Legende';
$LANG['col_month_header'] = 'Monatsfelder';
$LANG['col_day_holidays'] = 'Feiertage';
$LANG['col_day_absences'] = 'Abwesenheiten';
$LANG['dom_prefix'] = 'Monatstag:';
$LANG['dow_prefix'] = 'Wochentag:';
$LANG['dow_daynote'] = 'Wochentag: mit Tagesnotiz';
$LANG['btn_edit_month'] = 'Knopf: Monatsschablone bearbeiten';
$LANG['btn_edit_member'] = 'Knopf: Nutzerkalender bearbeiten';
$LANG['legend_today'] = 'Heute-Markierung';

/**
 * Month Dialog
 */
$LANG['month_edit'] = 'Schablone f&uuml;r ';
$LANG['month_daynote'] = 'Tagesnotiz';
$LANG['month_daynote_tooltip'] = 'Tagesnotiz bearbeiten...';

/**
 * Daynote Dialog
 */
$LANG['daynote_edit_title'] = 'Tagesnotiz f&uuml;r ';
$LANG['daynote_edit_title_for'] = 'f&uuml;r';
$LANG['daynote_edit_msg_caption'] = 'Tagesnotiz:';
$LANG['daynote_edit_msg_hint'] = '(Du kannst Format HTML Tags benutzen wie &lt;b&gt;.)';
$LANG['daynote_edit_msg'] = '<strong>Tagesnotiz</strong><br>...die ist die Notiz...';
$LANG['daynote_edit_event_created'] = '[CONFIRMATION]\n\nDie Tagesnotiz wurde angelegt.';
$LANG['daynote_edit_event_saved'] = '[CONFIRMATION]\n\nDie Tagesnotiz wurde gespeichert.';
$LANG['daynote_edit_event_deleted'] = '[CONFIRMATION]\n\nDie Tagesnotiz wurde gel&ouml;scht.';

/**
 * Icon Upload Dialog
 */
$LANG['upload_title'] = 'TeamCal Pro Datei Upload';
$LANG['upload_maxsize'] = 'Maximale Dateigr&ouml;&szlig;e';
$LANG['upload_extensions'] = 'Erlaubte Dateitypen';
$LANG['upload_file'] = 'Datei...';

/**
 * Notification Messages
 */
$LANG['notification_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Aktualisierung";
$LANG['notification_subject_approval'] = $CONF['app_name']." ".$CONF['app_version']." - Genehmigung erforderlich";

$LANG['notification_greeting']  = "<hr>Diese Nachricht wurde automatisch generiert von ".$CONF['app_name']." ".$CONF['app_version']." at:<br />".$CONF['app_url']."<hr>";
$LANG['notification_greeting'] .= "Hallo TeamCal Pro Nutzer,<br /><br />";

$LANG['notification_usr_msg'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei Hinzuf&uuml;gen oder &Auml;derung von Nutzern zu benachrichtigen.<br /><br />";
$LANG['notification_usr_add_msg'] = "Der folgende Nutzer wurde hinzugefuegt:<br /><br />";
$LANG['notification_usr_chg_msg'] = "Der folgende Nutzer wurde geaendert:<br /><br />";
$LANG['notification_usr_del_msg'] = "Der folgende Nutzer wurde geloescht:<br /><br />";

$LANG['notification_usr_cal'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei &Auml;nderung von Nutzer Kalendern zu benachrichtigen.<br /><br />";
$LANG['notification_usr_cal_msg'] = "Der Kalender des folgenden Nutzers wurde geaendert:<br /><br />";

$LANG['notification_grp_msg'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei Hinzuf&uuml;gen oder &Auml;nderung von Gruppen zu benachrichtigen.\r\n";
$LANG['notification_grp_add_msg'] = "Die folgende Gruppe wurde hinzugefuegt:<br /><br />";
$LANG['notification_grp_chg_msg'] = "Die folgende Gruppe wurde geaendert:<br /><br />";
$LANG['notification_grp_del_msg'] = "Die folgende Gruppe wurde geloescht:<br /><br />";

$LANG['notification_abs_msg'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei Hinzuf&uuml;gen oder &Auml;nderung von Abwesenheittypen zu benachrichtigen.\r\n";
$LANG['notification_abs_add_msg'] = "Der folgende Abwesenheitstyp wurde hinzugefuegt:<br /><br />";
$LANG['notification_abs_chg_msg'] = "Der folgende Abwesenheitstyp wurde geaendert:<br /><br />";
$LANG['notification_abs_del_msg'] = "Der folgende Abwesenheitstyp wurde geloescht:<br /><br />";

$LANG['notification_hol_msg'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei Hinzuf&uuml;gen oder &Auml;nderung von Feiertagen zu benachrichtigen.\r\n";
$LANG['notification_hol_add_msg'] = "Der folgende Feiertag wurde hinzugefuegt:<br /><br />";
$LANG['notification_hol_chg_msg'] = "Der folgende Feiertag wurde geaendert:<br /><br />";
$LANG['notification_hol_del_msg'] = "Der folgende Feiertag wurde geloescht:<br /><br />";

$LANG['notification_month_msg'] = "dein TeamCal Pro Nutzerprofil ist konfiguriert, dich bei Aktualisierungen an Monats-Schablonen zu benachrichtigen.<br />Eine Aktualisierung wurde vorgenommen an der Schablone fuer:<br /><br />";

$LANG['notification_signature']  = "Du kannst die Einstellungen f&uuml;r e-Mail Benachrichtigungen in deinem Nutzerprofil in TeamCal Pro &auml;ndern.<br /><br />";
$LANG['notification_signature'] .= "Mit freundlichen Gr&uuml;&szlig;en,<br /><br />";
$LANG['notification_signature'] .= "Deine TeamCal Pro Administration<br />";

$LANG['notification_decl_msg']    = "eine Abwesenheitsanfrage wurde wegen zu hoher Gesamtabwesenheit abgelehnt.<br /><br />";
$LANG['notification_decl_msg_2']  = "eine Abwesenheitsanfrage wurde abgelehnt, weil der Abwesenheitstyp einer Genehmigung bedarf.<br /><br />";
$LANG['notification_decl_user']   = "Anfragender Nutzer: ";
$LANG['notification_decl_reason'] = "Angegebener Grund: ";
$LANG['notification_decl_sign']   = "<br /><br />Wenn du der anfragende Nutzer bist, kannst du deinen Gruppenmanager kontaktieren, der deine Anfrage bestaetigen kann.<br />";
$LANG['notification_decl_sign']  .= "Wenn du der Manager der betroffenen Gruppe bist, bitte nehme mit dem Nutzer Kontakt auf.<br /><br />";
$LANG['notification_decl_sign']  .= "Mit freundlichen Gr&uuml;&szlig;en,<br /><br />";
$LANG['notification_decl_sign']  .= "Deine TeamCal Pro Administration<br />";

/**
 * Login Dialog
 */
$LANG['login_login'] = 'TeamCal Pro Login';
$LANG['login_username'] = 'Benutzername:';
$LANG['login_password'] = 'Passwort:';
$LANG['login_error_0'] = 'Login erfolgreich';
$LANG['login_error_1'] = 'Benutzername oder Passwort nicht angegeben';
$LANG['login_error_2'] = 'Benutzername unbekannt';
$LANG['login_error_3'] = 'Dieser Account ist gesperrt bzw. noch nicht best&aum;tigt.';
$LANG['login_error_4a'] = 'Password falsch. Dies war Fehlversuch Nummer ';
$LANG['login_error_4b'] = ' . Nach ';
$LANG['login_error_4c'] = ' Fehlversuchen wird der Account gesperrt f&uuml;r ';
$LANG['login_error_4d'] = ' Sekunden.';
$LANG['login_error_6a'] = 'Dieser Account ist wegen zu vieler falscher Loginversuche vor&uuml;bergehend gesperrt. Die Grace Periode endet in ';
$LANG['login_error_6b'] = ' Sekunden.';
$LANG['login_error_7'] = 'Passwort inkorrekt';
$LANG['login_error_8'] = 'Konto nicht verifiziert. Du solltest eine E-Mail mit einem Verfizierungslink erhalten haben.';
$LANG['login_error_91'] = 'LDAP Fehler: Passwort fehlt';
$LANG['login_error_92'] = 'LDAP Fehler: Authentifizierung fehlgeschlagen';
$LANG['login_error_93'] = 'LDAP Fehler: Verbindung zum LDAP Server fehlgeschlagen';
$LANG['login_error_94'] = 'LDAP Fehler: Start von TLS fehlgeschlagen';
$LANG['login_error_95'] = 'LDAP Fehler: Benutzername nicht gefunden';
$LANG['login_error_96'] = 'LDAP Fehler: "Search bind" fehlgeschlagen';

/**
 * Status Bar
 */
$LANG['status_logged_in'] = "Du bist eingeloggt als ";
$LANG['status_logged_out'] = "Du bist nicht eingeloggt. Anzeigen und Editieren kann eingeschr&auml;nkt sein.";
$LANG['status_ut_user'] = "Regul&auml;rer Nutzer";
$LANG['status_ut_manager'] = "Manager der Gruppe: ";
$LANG['status_ut_director'] = "Direktor";
$LANG['status_ut_assistant'] = "Assistent";
$LANG['status_ut_admin'] = "Administrator";

/**
 * User Profile Dialog
 */
$LANG['view_profile_title'] = 'Nutzerprofil anzeigen';
$LANG['edit_profile_title'] = 'Nutzerprofil editieren';
$LANG['add_profile_title'] = 'Nutzer hinzuf&uuml;gen';
$LANG['tab_personal_data'] = 'Personalien';
$LANG['tab_membership'] = 'Gruppe';
$LANG['tab_membership_group'] = 'Gruppe';
$LANG['tab_membership_member'] = 'Mitglied';
$LANG['tab_membership_manager'] = 'Manager';
$LANG['tab_options'] = 'Optionen';
$LANG['tab_privileges'] = 'Konto';
$LANG['tab_absences'] = 'Abwesenheiten';
$LANG['show_profile_uname'] = 'Benutzername:';
$LANG['show_profile_password'] = 'Neues&nbsp;Passwort:';
$LANG['show_profile_verify_password'] = 'Password&nbsp;best&auml;tigen:';
$LANG['show_profile_name'] = 'Name:';
$LANG['show_profile_lname'] = 'Nachname:';
$LANG['show_profile_fname'] = 'Vorname:';
$LANG['show_profile_usertitle'] = 'Titel:';
$LANG['show_profile_position'] = 'Position:';
$LANG['show_profile_idnumber'] = 'ID-Nummer:';
$LANG['show_profile_email'] = 'E-Mail:';
$LANG['show_profile_birthday'] = "Geburtstag:";
$LANG['show_profile_birthday_format'] = "(Format: JJJJ-MM-TT)";
$LANG['show_profile_gender'] = "Geschlecht:";
$LANG['show_profile_male'] = "M&auml;nnlich";
$LANG['show_profile_female'] = "Weiblich";
$LANG['show_profile_phone'] = 'Telefon:';
$LANG['show_profile_mobile'] = 'Handy:';
$LANG['show_profile_group'] = 'Gruppe:';
$LANG['show_profile_sendmail'] = 'Sende eine E-Mail and diesen Nutzer:';
$LANG['show_profile_subject'] = 'Thema:';
$LANG['show_profile_message'] = 'Nachricht:';
$LANG['show_profile_from'] = 'TeamCal Pro - Nutzerprofil Anzeige Message';
$LANG['show_profile_msgsent'] = 'Deine Mail wurde gesendet.';
$LANG['user_delete_confirm'] = "Bist du sicher, dass du die ausgew&auml;hlten Nutzer l&ouml;schen willst?\\r\\n";
$LANG['frame_user_type'] = "Spezial&nbsp;Usertyp";
$LANG['ut_caption'] = "Privilegien nach Spezial Usertyp";
$LANG['ut_user'] = "Normaler User (Mitglied und/oder Manager)";
$LANG['ut_admin'] = "Administrator";
$LANG['ut_director'] = "Director";
$LANG['frame_personal_details'] = "Pers&ouml;nliche Details";
$LANG['frame_user_groupmember'] = "Gruppen&nbsp;Mitgliedschaft";
$LANG['frame_user_status'] = "User&nbsp;Status";
$LANG['us_caption'] = "User Status setzen";
$LANG['us_locked'] = "User gesperrt";
$LANG['us_logloc'] = "Login gesperrt";
$LANG['us_hidden'] = "Nicht im Kalender zeigen";
$LANG['frame_mail_notification'] = "E-Mail&nbsp;Benachrichtigung";
$LANG['show_absence'] = "Abwesenheiten";
$LANG['show_absence_from'] = "Abwesenheitstage von";
$LANG['show_absence_to'] = "bis";
$LANG['show_absence_type'] = "Abwesenheit";
$LANG['show_absence_lastyear'] = "Vorjahr";
$LANG['show_absence_allowance'] = "Kontingent";
$LANG['show_absence_taken'] = "Genommen";
$LANG['show_absence_factor'] = "Faktor";
$LANG['show_absence_remainder'] = "Rest";
$LANG['frame_uo'] = "Optionen";
$LANG['uo_caption'] = "Verschiedene Optionen";
$LANG['uo_owngroupsonly'] = "Nur meine eigene Gruppen anzeigen";
$LANG['uo_showbirthday'] = "Meinen Geburtstag im Kalender zeigen";
$LANG['uo_ignoreage'] = "Mein Geburtsjahr (Alter) ignorieren";
$LANG['uo_notifybirthday'] = "Melde mir andere Geburtstage";
$LANG['uo_language'] = "Standard Sprache";
$LANG['uo_defgroup'] = "Standard Gruppenfilter";
$LANG['error_password_mismatch'] = "Dein Passw&ouml;rter stimmen nicht &uuml;berein.";
$LANG['error_user_exists'] = "Der Benutzername existiert bereits. Bitte w&auml;hle einen anderen.";
$LANG['error_user_nospecialchars'] = "Es sind keine Sonderzeichen beim Benutzernamen erlaubt.\\nBitte w&auml;hle einen anderen.";
$LANG['profile_updated'] = "Das Profil wurde aktualisiert.";
$LANG['user_add_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Dein Account";
$LANG['user_add_greeting']  = "------------------------------------------------------------\n";
$LANG['user_add_greeting'] .= "Diese Nachricht wurde automatisch generiert von:\n";
$LANG['user_add_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." -> ".$CONF['app_url'].".\n";
$LANG['user_add_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['user_add_greeting'] .= "Willkommen bei TeamCal Pro!\n";
$LANG['user_add_greeting'] .= "Herzlichen Gl&uuml;ckwunsch, du hast einen User Account bei TeamCal Pro erhalten.\n\n";
$LANG['user_add_info_1']    = "So geht es zu TeamCal Pro:\n";
$LANG['user_add_info_1']   .= $CONF['app_url']."\n\n";
$LANG['user_add_info_1']   .= "Klick den [Login] Button and nutze folgende Login Daten:\n";
$LANG['user_add_info_1']   .= "Dein Benutzername: ";
$LANG['user_add_info_2']    = "\nDein Passwort: ";
$LANG['user_add_info_3']    = "\n\nDeine TeamCal Pro Administration\n";
$LANG['tab_avatar']    = "Avatar";
$LANG['ava_title']    = "Avatar";
$LANG['ava_upload']    = "Lade ein Bild von der Festplatte hoch. Erlaubt sind Bilder vom Typ JPG, GIF und PNG, nicht gr&ouml;&szlig;er als 250 KB mit den maximalen Ausma&szlig;en ".$LC->readConfig("avatarWidth")."*".$LC->readConfig("avatarHeight")." Pixel.";
$LANG['ava_wrongtype_1']    = "Falscher Dateityp: ";
$LANG['ava_wrongtype_2']    = "Erlaubt sind die Dateitypen ";
$LANG['ava_write_error']    = "Es ist ein Fehler beim Schreiben der Avatar Datei aufgetreten.";
$LANG['ava_upload_error']    = "Es ist ein unbekannter Fehler beim Upload aufgetreten. Bitte &uuml;berpr&uuml;fe nochmal Gr&ouml;&szlig;e und Typ der hochzuladenen Datei.";
$LANG['ava_upload_error_1']    = "Die Datei ist zu gro&szlig;. Sie &uuml;bersteigt die upload_max_filesize Direktive in php.ini.";
$LANG['ava_upload_error_2a']    = "Die Datei ist zu gro&szlig;. Die Upload Gr&ouml;&szlig;e ist limitiert auf ";
$LANG['ava_upload_error_2b']    = " Bytes.";
$LANG['ava_upload_error_3']    = "Die Datei wurde nur zum Teil hochgeladen.";
$LANG['ava_upload_error_4']    = "Es wurde keine Datei hochgeladen.";
$LANG['notify_caption'] = 'Ich m&ouml;chte per E-Mail benachrichtigt werden, wenn...';
$LANG['notify_team'] = 'ein User hinzugef&uuml;gt oder ein Profil ge&auml;ndert wurde.';
$LANG['notify_groups'] = 'eine Gruppe hinzugef&uuml;gt oder ge&auml;ndert wurde.';
$LANG['notify_month'] = 'eine Monats-Schablone ge&auml;ndert wurde.';
$LANG['notify_absence'] = 'ein Abwesenheitstyp hinzugef&uuml;gt oder ge&auml;ndert wurde.';
$LANG['notify_holiday'] = 'ein Feiertag hinzugef&uuml;gt oder ge&auml;ndert wurde.';
$LANG['notify_usercal'] = 'ein Mitarbeiter Kalender ge&auml;ndert wurde';
$LANG['notify_ofgroup'] = 'von Gruppe:';

/**
 * Admin Pages
 */
$LANG['admin_user_user'] = 'User';
$LANG['admin_user_attributes'] = 'Attribute';
$LANG['admin_user_lastlogin'] = 'Letztes Login';
$LANG['admin_user_action'] = 'Aktion';
$LANG['admin_user_title'] = 'User Verwaltung';
$LANG['tt_user_logloc'] = "Dieser Nutzer hatte zuviele fehlgeschlagene Login Vesuche. Sein Konto ist f&uuml;r die L&auml;nge der Schonfrist gesperrt.";
$LANG['tt_user_locked'] = "Dieser Nutzer ist geblockt bzw. noch nicht best&auml;tigt. Der Administrator kann die Blockierung im Nutzerprofil aufheben.";
$LANG['tt_user_hidden'] = "Dieser Nuzter wird nicht im Kalender angezeigt. Der Administrator kann dies im Nuzterprofil aufheben.";
$LANG['tt_user_verify'] = "Dieser Nutzer hat sein Konto noch nicht verifiziert.";
$LANG['admin_group_title'] = 'Gruppen Verwaltung';
$LANG['admin_absence_title'] = 'Abwesenheits Verwaltung';
$LANG['admin_holiday_title'] = 'Feiertag Verwaltung';
$LANG['admin_help_title'] = 'Hilfe';
$LANG['admin_create_new_user'] = 'Einen neuen User anlegen...';
$LANG['admin_import_user'] = 'User Import mit CSV Datei...';
$LANG['admin_create_new_group'] = 'Eine neue Gruppe anlegen...';
$LANG['admin_create_new_absence'] = 'Einen neuen Abwesenheitstyp anlegen...';
$LANG['admin_create_new_holiday'] = 'Einen neuen Feiertagstyp anlegen...';
$LANG['admin_column_user'] = 'Nachname, Vorname (username)';
$LANG['icon_user'] = 'User';
$LANG['icon_manager'] = 'Gruppenmanager';
$LANG['icon_director'] = 'Direktor';
$LANG['icon_admin'] = 'Administrator';

/**
 * Configuration Page
 */
$LANG['admin_config_register_globals'] =
'TeamCal hat erkannt, dass die PHP Umgebungsvariable \'register_globals\' auf \'on\' steht. ' .
'Es wird dringend empfohlen, diese Einstellung auf \'off\' zu stellen, da sie eine Sicherheitsl&uumlcke darstellt.\\n\\n' .
'Wenn du deinen Webserver selbst verwaltest, editiere deine PHP.INI Datei und suche nach der Zeile ' .
'\'register_globals=On\'. &auml;ndere sie in \'register_globals=Off\'.\\n\\n' .
'Wenn du keinen Zugriff auf die PHP.INI Datei hast, erstelle eine Datei namens \'.htaccess\' in deinem TeamCal ' .
'Verzeichnis mit der Anweisung \'php_value register_globals 0\'.';
$LANG['admin_config_register_globals_on'] = 'register_globals=On';
$LANG['admin_config_title'] = 'TeamCal Konfiguration';
$LANG['admin_config_general'] = 'Allgemeine Optionen';
$LANG['admin_config_appsubtitle'] = 'Applikations Untertitel';
$LANG['admin_config_appsubtitle_comment'] = 'Wird direkt &uuml;ber dem TeamCal Menu angezeigt.';
$LANG['admin_config_appfootercpy'] = 'Applikation Fu&szlig;zeilen Copyright';
$LANG['admin_config_appfootercpy_comment'] = 'Wird in der Fu&szlig;zeile direkt &uuml;ber der "Powered by..." Zeile angezeigt.';
$LANG['admin_config_display'] = 'Kalenderanzeige';
$LANG['admin_config_showmonths'] = 'Anzahl Monate';
$LANG['admin_config_showmonths_comment'] = 'Mit dieser Option wird die Anzahl der Monate angegeben, die standardm&auml;&szlig;ig in der Kalenderansicht dargestellt werden.';
$LANG['admin_config_showmonths_1'] = '1 Monat';
$LANG['admin_config_showmonths_2'] = '2 Monate';
$LANG['admin_config_showmonths_3'] = '3 Monate';
$LANG['admin_config_showmonths_6'] = '6 Monate';
$LANG['admin_config_showmonths_12'] = '12 Monate';
$LANG['admin_config_weeknumbers'] = 'Wochennummern anzeigen';
$LANG['admin_config_weeknumbers_comment'] = 'Mit dieser Option wird im Kalender eine Zeile mit den Nummern der Kalenderwochen hinzugef&uuml;gt.';
$LANG['admin_config_remainder'] = 'Resttage Spalte';
$LANG['admin_config_remainder_comment'] =
'Mit dieser Option wird im Kalender eine aufklappbare Spalte mit den Resttagen hinzugef&uuml;gt, '.
'die f&uuml;r jeden Nutzer die verbleibenden Tage pro Abwesenheitstyp anzeigt. Hinweis: Die Abwesenheitstypen, '.
'die in der Resttage Anzeige enthalten sein sollen, m&uuml;ssen entsprechend konfiguriert werden.';
$LANG['admin_config_remainder_total'] = 'Resttage Summe';
$LANG['admin_config_remainder_total_comment'] =
'Mit dieser Option werden der Resttage Anzeige die erlaubten Tage pro Abwesenheitstyp hinzugef&uuml;gt ('.
'getrennt durch einen Schr&auml;gstrich).';
$LANG['admin_config_show_remainder'] = 'Resttage anzeigen';
$LANG['admin_config_show_remainder_comment'] =
'Mit dieser Option wird die Resttage Spalte standardm&auml;&szlig;ig aufgeklappt.';
$LANG['admin_config_summary'] = 'Summen Abschnitt';
$LANG['admin_config_summary_comment'] =
'Mit dieser Option wird eine aufklappbare Zusammenfassung unter jedem Monat angezeigt, die die Summen '.
'der Abwesenheiten auff&uuml;hrt.';
$LANG['admin_config_show_summary'] = 'Summen Abschnitt anzeigen';
$LANG['admin_config_show_summary_comment'] =
'Mit dieser Option wird der Summen Abschnitt standardm&auml;&szlig;ig aufgeklappt.';
$LANG['admin_config_repeatheadercount'] = 'Kopfzeilen Wiederholungs Z&auml;hler';
$LANG['admin_config_repeatheadercount_comment'] =
'Gibt die Anzahl von Zeilen an, nach der die Monatskopfzeile f&uuml;r bessere Lesbarkeit wiederholt wird.';
$LANG['admin_config_todaybordercolor'] = 'Heute Randfarbe';
$LANG['admin_config_todaybordercolor_comment'] =
'Gibt die Farbe in Hexadezimal an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['admin_config_todaybordersize'] = 'Heute Randst&auml;rke';
$LANG['admin_config_todaybordersize_comment'] =
'Gibt die Dicke in Pixel an, in der der rechte und linke Rand der Heute Spalte erscheint.';
$LANG['admin_config_usericonsavatars'] = 'User Icons und Avatare';
$LANG['admin_config_usericons'] = 'User Icons anzeigen';
$LANG['admin_config_usericons_comment'] =
'Mir dieser Option werden links vom Benutzernamen User Icons angezeigt, die die User Rolle und das Geschlecht anzeigen.';
$LANG['admin_config_avatars'] = 'Avatars anzeigen';
$LANG['admin_config_avatars_comment'] =
'Mit dieser Option wird ein User Avatar in einem Pop-Up Fenster angezeigt, wenn die Maus &uuml;ber das User Icon '.
'gef&uuml;hrt wird. Hinweis: Diese Funktion arbeitet nur, wenn "'.$LANG['admin_config_usericons'].'" eingschaltet ist.';
$LANG['admin_config_avatarwidth'] = 'Avatar Maximale Breite';
$LANG['admin_config_avatarwidth_comment'] =
'Gibt die Breite in Pixel von Avatar Bildern an. Avatar Bilder mit gr&ouml;&szlig;erer Breite werden auf diese Breite reduziert und die H&ouml;he wird proportional angepasst.';
$LANG['admin_config_avatarheight'] = 'Avatar Maximale H&ouml;he';
$LANG['admin_config_avatarheight_comment'] =
'Gibt die H&ouml;he in Pixel von Avatar Bildern an. Avatar Bilder mit gr&ouml;&szlig;erer H&ouml;he werden auf diese H&ouml;he reduziert und die Breite wird proportional angepasst.';
$LANG['admin_config_debughide'] = 'Debug Info Verstecken';
$LANG['admin_config_debughide_comment'] =
'Mit diesem Switch werden sensitive Daten wie (Server, Name, Userid) von der Environment Seite verborgen. ' .
'Das db_password wir unabh&auml;ngig von diesem Switch aber immer versteckt als ****** ausgegeben.';
$LANG['admin_config_timezone'] = 'Zeitzone';
$LANG['admin_config_timezone_comment'] =
'Wenn der Webserver in einer anderen Zeitzone steht als die TeamCal Nutzer, kann hier die TeamCal Zeitzone angepasst werden.';
$LANG['admin_config_login'] = 'Login Optionen';
$LANG['admin_config_pwd_length'] = 'Passwort L&auml;nge';
$LANG['admin_config_pwd_length_comment'] = 'Minimale L&auml;nge des Passworts.';
$LANG['admin_config_pwd_strength'] = 'Passwort Sicherheit';
$LANG['admin_config_pwd_strength_comment'] =
'Die Passwort Sicherheit bestimmt, welchen Anforderungen das User Passwort gen&uuml;gen muss.'.
'</span><ul style="list-style: square; margin-left: 0px;">'.
'<li><span class="function">Minimum</span><br><span class="config-comment">Alles ist erlaubt, solange die Minimall&auml;nge eingehalten wird '.
'und eine neues Passwort sich vom alten unterscheidet.</span></li>'.
'<li><span class="function">Low</span><br><span class="config-comment">Das Passwort muss die Minimall&auml;nge einhalten '.
'und darf nicht den Benutzernamen vorw&auml;rts oder r&uuml;ckw&auml;rts enthalten.</span></li>'.
'<li><span class="function">Medium</span><br><span class="config-comment">Wie "Low", muss aber zus&auml;tzlich Zahlen enthalten.</span></li>'.
'<li><span class="function">High</span><br><span class="config-comment">Wie "Medium", muss aber zus&auml;tzlich Gro&szlig;- und Kleinschreibung und Punktuation enthalten.</span></li>'.
'</ul><span class="config-comment">';
$LANG['admin_config_cookie_lifetime'] = 'Cookie Lebensdauer';
$LANG['admin_config_cookie_lifetime_comment'] =
'Bei erfolgreichem Einloggen wird ein Cookie auf dem lokalen Rechner des Users abgelegt. ' .
'Dieser Cookie hat eine bestimmte Lebensdauer, nach dem er nicht mehr anerkannt wird. Ein erneutes Login is notwendig. '.
'Die Lebensdauer kann hier in Sekunden angegeben werden (0-999999).';
$LANG['admin_config_bad_logins'] = 'Ung&uuml;ltige Logins';
$LANG['admin_config_bad_logins_comment'] =
'Anzahl der ung&uuml;ltigen Login Versuche bevore der User Status auf \'LOCKED\' gesetzt wird. Der User muss danach solange '.
'warten wie in der Schonfrist angegeben, bevor er sich erneut einloggen kann. Wenn dieser Wert auf 0 gesetzt wird, ist diese Funktion deaktiviert.';
$LANG['admin_config_grace_period'] = 'Schonfrist';
$LANG['admin_config_grace_period_comment'] =
'Zeit in Sekunden, die ein User warten muss, bevor er sich nach zu vielen fehlgeschlagenen Versuchen wieder einloggen kann.';
$LANG['admin_config_mailfrom'] = 'Mail Von';
$LANG['admin_config_mailfrom_comment'] =
'Gibt den Absender Namen von Benachrichtigungs E-Mails an.';
$LANG['admin_config_mailreply'] = 'Mail Antwort';
$LANG['admin_config_mailreply_comment'] =
'Gibt die R&uuml;ckantwort Adresse von Benachrichtigungs E-Mails an. Dieses Feld muss eine g&uuml;ltige E-Mail Adresse enthalten. Wenn das nicht der '.
'Fall ist, wird die Dummy Adresse "noreply@teamcalpro.com" gespeichert.';
$LANG['admin_config_registration'] = 'User Registrierung';
$LANG['admin_config_allow_registration'] = 'User Selbst-Registration erlauben';
$LANG['admin_config_allow_registration_comment'] =
'Erlaubt die Registrierung durch den User. Ein zus&auml;tzlicher Menueintrag erscheint im TeamCal Menu.';
$LANG['admin_config_email_confirmation'] = 'E-Mail Best&auml;tigung erforderlich';
$LANG['admin_config_email_confirmation_comment'] =
'Durch die Registrierung erh&auml;lt der User eine E-Mail an die von ihm angegebene Adresse. Sie enth&auml;lt '.
'einen Aktivierungslink, dem er folgen muss, um seine Angaben zu bets&auml;tigen.';
$LANG['admin_config_admin_approval'] = 'Administrator Freischaltung erforderlich';
$LANG['admin_config_admin_approval_comment'] =
'Der Administrator erh&auml;lt eine E-Mail bei einer Neuregistrierung. Er muss den Account manuell freischalten.';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_title'] = 'Datenbank Management';
$LANG['admin_dbmaint_import_caption'] = 'Import von Abwesenheiten:';
$LANG['admin_dbmaint_import_original'] = 'Originale Abwesenheitstypen importieren';
$LANG['admin_dbmaint_import_convert'] = 'Originale Abwesenheitstypen konvertieren zu "nicht anwesend"';
$LANG['admin_dbmaint_import_button'] = 'Importieren';
$LANG['admin_dbmaint_cleanup_caption'] = 'Alte Templates und Tagesnotizen l&ouml;schen';
$LANG['admin_dbmaint_cleanup_year'] = 'Jahr:';
$LANG['admin_dbmaint_cleanup_month'] = 'Monat:';
$LANG['admin_dbmaint_cleanup_hint'] = '(&auml;ter als und inklusive dieses Monats)';
$LANG['admin_dbmaint_cleanup_chkUsers'] = 'Userbezogene Templates und Tagesnotizen l&ouml;schen';
$LANG['admin_dbmaint_cleanup_chkMonths'] = 'Allgemeine Templates und Tagesnotizen l&ouml;schen';
$LANG['admin_dbmaint_cleanup_confirm'] = 'Gib bitte "CLEANUP" ein, um diese Aktion zu best&auml;tigen:';
$LANG['admin_dbmaint_del_caption'] = 'Datens&auml;tze l&ouml;schen';
$LANG['admin_dbmaint_del_chkUsers'] = 'Alle User inkl. deren Abwesenheiten und Notizen l&ouml;schen (ausser "admin")';
$LANG['admin_dbmaint_del_chkGroups'] = 'Alle Gruppen l&ouml;schen';
$LANG['admin_dbmaint_del_chkHolidays'] = 'Alle Feiertage l&ouml;schen (ausser "Wochenende" und "Arbeitstag")';
$LANG['admin_dbmaint_del_chkAbsence'] = 'Alle Abwesenheitstypen l&ouml;schen';
$LANG['admin_dbmaint_del_chkDaynotes'] = 'Alle allgemeinen Tagesnotizen l&ouml;schen';
$LANG['admin_dbmaint_del_chkAnnouncements'] = 'Alle Ank&uuml;ndigungen l&ouml;schen';
$LANG['admin_dbmaint_del_chkOrphAnnouncements'] = 'Verwaiste Ank&uuml;ndigungen l&ouml;schen';
$LANG['admin_dbmaint_del_chkLog'] = 'System Log l&ouml;schen';
$LANG['admin_dbmaint_del_confirm'] = 'Gib bitte "DELETE" ein, um diese Aktion zu best&auml;tigen:';

/**
 * Environment Page
 */
$LANG['env_title'] = 'TeamCal Pro Umgebungsanzeige';
$LANG['env_config'] = 'tc_config Variablen';
$LANG['env_language'] = 'tc_language Variablen';

/**
 * Error Messages
 */
$LANG['err_title'] = 'TeamCal Pro Fehler Nachricht';
$LANG['err_not_authorized_short'] = 'Nicht Authorisiert';
$LANG['err_not_authorized_long'] = 'Es ist dir nicht erlaubt, diese Seite anzuzeigen oder diese Aktion durchzuf&uuml;hren.';
$LANG['err_allowance_not_numeric'] = 'Bitte numerische Werte f&uuml;r Abwesenheitskontingente angeben.';

/**
 * XML Export Dialog
 */
$LANG['exp_title'] = 'TeamCal Pro Daten Export';
$LANG['exp_table'] = 'Export Tabelle:';
$LANG['exp_table_absence'] = 'Abwesenheitstypen';
$LANG['exp_table_group'] = 'Gruppen';
$LANG['exp_table_holiday'] = 'Feiertage';
$LANG['exp_table_log'] = 'Log Eintr&auml;ge';
$LANG['exp_table_month'] = 'Monats Schablonen';
$LANG['exp_table_template'] = 'Nutzer Schablonen';
$LANG['exp_table_user'] = 'Nutzerprofile';
$LANG['exp_format'] = 'Export Format:';
$LANG['exp_format_xml'] = 'XML (Extensible Markup Language)';
$LANG['exp_format_csv'] = 'CSV (Comma Seperated Values)';

/**
 * Announcement Page
 */
$LANG['ann_title'] = 'Ank&uuml;ndigungen f&uuml;r ';
$LANG['ann_delete_confirm_1'] = 'Bist du sicher, dass du die Ank&uuml;ndigung [';
$LANG['ann_delete_confirm_2'] = '] von deiner Liste entfernen willst?';
$LANG['ann_id'] = 'Ank&uuml;ndigungs-ID';
$LANG['ann_bday_title'] = 'Geburtstage am ';

/**
 * Statistics Page
 */
$LANG['stat_title'] = 'Statistik';
$LANG['stat_period_month'] = 'Aktueller Monat';
$LANG['stat_period_quarter'] = 'Aktuelles Quartal';
$LANG['stat_period_half'] = 'Aktuelles Halbjahr';
$LANG['stat_period_year'] = 'Aktuelles Jahr';
$LANG['stat_results_total_per_type'] = 'Abwesenheiten pro Typ im Zeitraum:&nbsp;&nbsp;';
$LANG['stat_results_remainders'] = 'Resttage pro Typ f&uuml;r&nbsp;';
$LANG['stat_results_all_groups'] = 'Summe (Gruppen):';
$LANG['stat_results_all_members'] = 'Summe (Personen):';
$LANG['stat_results_group'] = 'Gruppe ';
$LANG['stat_graph_total_absence_title'] = 'Abwesenheiten pro Gruppe';
$LANG['stat_graph_total_presence_title'] = 'Anwesenheiten pro Gruppe';
$LANG['stat_graph_total_type_title'] = 'Abwesenheiten pro Typ';
$LANG['stat_graph_total_remainder_title'] = 'Resttage pro Typ f&uuml;r ';

/**
 * Absence-Group Assignment Dialog
 */
$LANG['abs_group_title'] = 'Abwesenheitstyp/Gruppen Zuordnung';
$LANG['abs_group_frame_title'] = 'Abwesenheitszuordnung';
$LANG['abs_group_hint'] = 'W&auml;hle die Gruppen aus f&uuml;r die folgender Abwesenheitstyp g&uuml;ltig ist: ';

/**
 * User Import Dialog
 */
$LANG['uimp_title'] = 'TeamCal Pro User Import';
$LANG['uimp_import'] = 'W&auml;hle eine CSV (comma separated values) Datei von einem lokalen Laufwerk. Details &uuml;ber den Inhalt bitte in der Hilfe nachlesen.';
$LANG['uimp_source'] = 'CSV Quelldatei:';
$LANG['uimp_header'] = 'Datei enth&auml;lt Kopfzeile:';
$LANG['uimp_separator'] = 'Feldtrennzeichen:';
$LANG['uimp_enclose'] = 'Textmarkierzeichen:';
$LANG['uimp_escape'] = 'Escape Zeichen:';
$LANG['uimp_error_file'] = 'Bitte einen Dateinamen angeben!';
$LANG['uimp_lockuser'] = 'Nutzer blocken:';
$LANG['uimp_hideuser'] = 'Nutzer nicht im Kalender anzeigen:';
$LANG['uimp_defgroup'] = 'Standard Gruppe:';
$LANG['uimp_deflang'] = 'Standard Sprache:';
$LANG['uimp_error'] = '<span style="color: #FF0000;">Fehler</span>';
$LANG['uimp_err_col_1'] =
'Die CSV Datei enth&auml;lt weniger oder mehr als 11 Felder in mindestens einer Zeile!<br><br>'.
'Die Zeile startet mit "';
$LANG['uimp_err_col_2'] = '" und enth&auml;lt ';
$LANG['uimp_err_col_3'] =
' Felder.<br>Die CSV Datei muss folgendes Format haben:<br>'.
'"username";"firstname";"lastname";"title";"position";"phone";"mobile";"email";"idnumber";"birthday";"showbirthday"<br><br>'.
'Bitte berichtige den Fehler und versuche es erneut.<br>Note: Stelle auch sicher, dass keine leere Zeile am Ende der Datei ist.<br>&nbsp;';
$LANG['uimp_success'] = '<span style="color: #009900;">Erfolg</span>';
$LANG['uimp_success_1'] = ' Zeilen importiert.';
$LANG['uimp_success_2'] = ' Zeilen &uuml;bersprungen.';

/**
 * User Registration Dialog
 */
$LANG['register_title'] = 'User Registrierung';
$LANG['register_frame'] = 'Registrierung Details';
$LANG['register_lastname'] = 'Nachname';
$LANG['register_firstname'] = 'Vorname';
$LANG['register_username'] = 'Benutzername';
$LANG['register_email'] = 'E-Mail Adresse';
$LANG['register_password'] = 'Passwort';
$LANG['register_password2'] = 'Passwort wiederholen';
$LANG['register_group'] = 'User Gruppe';
$LANG['register_code'] = 'Sicherheitscode';
$LANG['register_result'] = 'Ergebnis';
$LANG['register_error_code'] = 'Du hast einen falschen Sicherheitscode eingegeben.';
$LANG['register_error_incomplete'] = 'Folgende Felder m&uuml;ssen ausgef&uuml;llt sein: '.$LANG['register_lastname'].
', '.$LANG['register_username'].', '.$LANG['register_email'].', '.$LANG['register_password'].' und '.$LANG['register_code'].'.';
$LANG['register_error_username'] = 'Dieser Benutzername ist bereits vergeben. Bitte w&auml;hle einen anderen.';
$LANG['register_error_username_format'] = 'Only alphanumeric characters are allowed in usernames.';
$LANG['register_success'] = 'Die Registrierung war erfolgreich. ';
$LANG['register_success_ok'] = 'Du kannst diesen Dialog nun schlie&szlig;en und dich einloggen.';
$LANG['register_success_verify'] = 'Eine E-Mail mit einem Best&auml;tigungslink wurde an dich gesendet, den du zur Verifizierung deines Kontos ausf&uuml;hren musst. ';
$LANG['register_success_approval'] = 'Ausserdem muss der Administrator deinen Antrag best&auml;tigen.';

$LANG['register_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Dein Account";
$LANG['register_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['register_mail_greeting'] .= "Diese Nachricht wurde automatisch generiert von:\n";
$LANG['register_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." -> ".$CONF['app_url'].".\n";
$LANG['register_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['register_mail_greeting'] .= "Willkommen bei TeamCal Pro!\n";
$LANG['register_mail_greeting'] .= "Herzlichen Gl&uuml;ckwunsch, du hast deinen User Account bei TeamCal Pro erfolgreich registriert.\n\n";
$LANG['register_mail_verify_1']  = "Du musst deinen Account mit folgendem Hyperlink verifizieren::\n";
$LANG['register_mail_verify_2a'] = "Nach der Verifizierung kannst du dich in TeamCal Pro einloggen.\n\n";
$LANG['register_mail_verify_2b'] = "Nach der Verifizierung und der Bestaetigung des Administrators kannst du dich in TeamCal Pro einloggen.\n\n";
$LANG['register_mail_verify_3']  = "W&auml;hle [Login] aus dem TeamCal Menu und benutze folgende Login Daten:\n";
$LANG['register_mail_verify_3'] .= "Dein Benutzername: [USERNAME]\n";
$LANG['register_mail_verify_3'] .= "Dein Passwort: [PASSWORD]\n\n";
$LANG['register_mail_verify_3'] .= "\n\nDeine TeamCal Pro Administration\n";

$LANG['register_admin_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Neue Nutzer Registrierung";
$LANG['register_admin_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['register_admin_mail_greeting'] .= "Diese Mail wurde automatisch generiert von:\n";
$LANG['register_admin_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['register_admin_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['register_admin_mail_greeting'] .= "Hallo Administrator,\n\n";
$LANG['register_admin_mail_message']   = "Ein neuer Nutzer hat sich registriert:\n";
$LANG['register_admin_mail_message']  .= "Name: [FIRSTNAME] [LASTNAME]\n";
$LANG['register_admin_mail_message']  .= "Benutzername: [USERNAME]\n\n";
$LANG['register_admin_mail_message_1'] = "Der Nuzter muss sein Konto noch verifizieren.\n";
$LANG['register_admin_mail_message_2'] = "Nach erfolgreicher Verifizierung muss der Administrator das Konto noch freischalten. Du wirst eine separate Mail dazu erhalten.";
$LANG['register_admin_mail_message_3'] = "\n\nDie TeamCal Pro Administration\n";

$LANG['verify_title'] = 'Nutzer Verifizierung';
$LANG['verify_result'] = 'Verifizierungsergebnis';
$LANG['verify_err_link'] = 'Der Link ist falsch oder unvollst&auml;ndig. Stell sicher, dass du den ganzen Link aus der E-Mail benutzt. Manchmal ist dieser durch einen Zeilenumbruch getrennt. Kopiere und einf&uuml;ge den kompletten link in deinen Browser und versuche es erneut.';
$LANG['verify_err_user'] = 'Der Benutzername existiert nicht.';
$LANG['verify_err_code'] = 'Der Verifizierungscode existiert nicht.';
$LANG['verify_err_match'] = 'Der Verifizierungscode stimmt nicht &uuml;berein.';
$LANG['verify_info_success'] = 'Das Nutzerkonto wurde erfolgreich verifiziert. ';
$LANG['verify_info_login'] = 'Du kannst dich nun einloggen mit den bei Registrierung angegebenen Daten.';
$LANG['verify_info_approval'] = 'Der Administrator muss das Nutzerkonto jedoch noch freischalten, bis ein Login mit den bei der Registrierung angegebnen Daten m&ouml;glich ist. Er wurde per E-Mail benachrichtigt.';

$LANG['verify_mail_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Kontobestaetigung erforderlich";
$LANG['verify_mail_greeting']  = "------------------------------------------------------------\n";
$LANG['verify_mail_greeting'] .= "Diese Mail wurde automatisch generiert von:\n";
$LANG['verify_mail_greeting'] .= $CONF['app_name']." ".$CONF['app_version']." at ".$CONF['app_url'].".\n";
$LANG['verify_mail_greeting'] .= "------------------------------------------------------------\n\n";
$LANG['verify_mail_greeting'] .= "Hallo Administrator,\n\n";
$LANG['verify_mail_message']   = "der Nutzer [USERNAME] hat sein Konto erfolgreich verifiziert.\n";
$LANG['verify_mail_message']  .= "Deine Bestaeigung ist erforderlich, um den Nutzer aktivieren und im Kalender sichtbar zu machen.\n";
$LANG['verify_mail_message']  .= "Bitte editiere das Profil entsprechend deiner Entscheidung.\n\n";
$LANG['verify_mail_message']  .= "\n\nDie TeamCal Pro Administration\n";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.0.000
 */

/**
 * Common
 */
$LANG['result'] = "Ergebnis";

/**
 * Filter
 */
$LANG['nav_user'] = 'Nutzer:';
$LANG['nav_year'] = 'Jahr:';

/**
 * User Profile Dialog
 */
$LANG['tab_other'] = 'Weiteres';
$LANG['other_title']    = "Pers&ouml;nliche Information";
$LANG['other_customFree']    = "Kommentar";
$LANG['other_customPopup']    = "Popup Info";
$LANG['uo_deftheme']    = "Standard Design";

/**
 * Admin Pages
 */
$LANG['user_search'] = 'Suche:';

/**
 * Configuration Page
 */
$LANG['admin_config_emailnotifications'] = 'E-Mail Benachrichtigungen';
$LANG['admin_config_emailnotifications_comment'] =
'Aktivierung/Deaktivierung von E-Mail Benachrichtigungen. Wenn diese Option ausgeschaltet ist, werden keine automatischen '.
'Benachrichtigungen per E-Mails verschickt. Dies trifft aber nicht auf Selbst-Registrierungsmails und auf manuell gesendete '.
'Mails im Message Center und im Viewprofile Dialog zu.';
$LANG['admin_config_userCustom'] = 'Benutzer Felder';
$LANG['admin_config_userCustom1'] = 'Benutzerfeld 1 Titel';
$LANG['admin_config_userCustom1_comment'] = 'Gibt den Titel dieses Benutzerfeldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['admin_config_userCustom2'] = 'Benutzerfeld 2 Titel';
$LANG['admin_config_userCustom2_comment'] = 'Gibt den Titel dieses Benutzerfeldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['admin_config_userCustom3'] = 'Benutzerfeld 3 Titel';
$LANG['admin_config_userCustom3_comment'] = 'Gibt den Titel dieses Benutzerfeldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['admin_config_userCustom4'] = 'Benutzerfeld 4 Titel';
$LANG['admin_config_userCustom4_comment'] = 'Gibt den Titel dieses Benutzerfeldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['admin_config_userCustom5'] = 'Benutzerfeld 5 Titel';
$LANG['admin_config_userCustom5_comment'] = 'Gibt den Titel dieses Benutzerfeldes an, der im Benutzerprofil Dialog angezeigt wird.';
$LANG['admin_config_theme'] = 'Design (Theme)';
$LANG['admin_config_theme_comment'] = 'W&auml;hle ein Design (auch \'Theme\' oder \'Skin\' genannt) aus, umd das Aussehen ' .
'von TeamCal Pro zu &auml;ndern. Du kannst ein eigenes Theme erstellen, indem du eine Kopie von dem \'tcpro\' Verzeichnis im \'themes\' '.
'Ordner anlegst und das Style Sheet und die Bilder entsprechend anpasst. Dein neues Verzeichnis wir dann automatisch in dieser Liste hier angezeigt.';
$LANG['admin_config_usertheme'] = 'User Design (Theme)';
$LANG['admin_config_usertheme_comment'] = 'W&auml;hle aus, ob jeder User sein eigenes TeamCal Pro Theme w&auml;hlen kann.';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_rest_caption'] = 'Datenbank Import';
$LANG['admin_dbmaint_rest_comment'] = 'W&auml;hle eine Datei aus mit einem Datenbank Dump, der importiert werden soll. ' .
'Es muss sich um einen Datenbank Dump der gleichen TeamCal Pro Version handeln.<br><span class="erraction">Exportiere oder sichere die aktuelle Datenbank zuerst! Alle Daten werden &uuml;berschrieben!</span>';
$LANG['admin_dbmaint_msg_001'] = "Kein g&uuml;ltiges SQL Statement in hochgeladener Datei gefunden.";
$LANG['admin_dbmaint_msg_002'] = "Datenbank erfolgreich wiederhergestellt. Die Datei wurde ausserdem in dein 'sql' Verzeichnis hochgeladen.";
$LANG['admin_dbmaint_msg_003'] = "Die Datei konnte nicht hochgeladen werden.";
$LANG['admin_dbmaint_msg_004'] = "Es wurde kein Dateiname zum Hochladen angegeben.";
$LANG['admin_dbmaint_exp_caption'] = 'Datenbank Export';
$LANG['exp_table_all'] = 'Alle';
$LANG['exp_format'] = 'Export Format:';
$LANG['exp_format_csv'] = 'CSV (Comma Seperated Values)';
$LANG['exp_format_sql'] = 'SQL (Structured Query Language)';
$LANG['exp_format_xml'] = 'XML (Extensible Markup Language)';
$LANG['exp_output'] = 'Export Ausgabe:';
$LANG['exp_output_browser'] = 'Browser';
$LANG['exp_output_file'] = 'Datei Download';

/**
 * Show Year Page
 */
$LANG['showyear_title_1'] = 'Jahreskalender';
$LANG['showyear_title_2'] = 'f&uuml;r:';
$LANG['showyear_tt_day'] = 'Tages-Information';
$LANG['showyear_tt_user'] = 'Nutzer-Information';
$LANG['showyear_weeknumber'] = 'Kalenderwoche';

/**
 * Error Messages
 */
$LANG['err_instfile_title'] = "Sicherheits Warnung";
$LANG['err_instfile'] = "Es scheint, dass TeamCal Pro bereits installiert wurde. Jedoch befindet sich immer noch die Datei " .
"\"installation.php\" im TeamCal Pro Verzeichnis. Aus Sicherheitsgr&uuml;nden sollte diese umgehend gel&ouml;scht werden.";

/**
 * PHPInfo Page
 */
$LANG['php_title'] = 'PHP Umgebung';

/**
 * Group Assignment Page
 */
$LANG['uassign_title'] = 'Gruppenzuordnung';
$LANG['uassign_usertype'] = 'User-Typ';
$LANG['uassign_tt_member'] = 'Normaler User (Mitglied oder Manager)';
$LANG['uassign_tt_director'] = 'Direktor';
$LANG['uassign_tt_admin'] = 'Administrator';
$LANG['uassign_tt_gnotmember'] = 'Kein Mitglied dieser Gruppe';
$LANG['uassign_tt_gmember'] = 'Mitglied dieser Gruppe';
$LANG['uassign_tt_gmanager'] = 'Manager dieser Gruppe';

/**
 * Absence Icon Page
 */
$LANG['absicon_title'] = 'Abwesenheist-Icon f&uuml;r ';
$LANG['absicon_none'] = 'Kein';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.0.001
 */

/**
 * Loglevel Management Page
 */
$LANG['admin_loglevel_loglevel'] = 'Loglevel &Auml;nderungen loggen';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.000
 */

/**
 * Calendar Display
 */
$LANG['totals'] = 'Dieser Monat';

/**
 * Configuration Page
 */
$LANG['admin_config_totals'] = 'Monatssummen anzeigen';
$LANG['admin_config_totals_comment'] =
'Mit dieser Option wird in der Resttage Spalte ein weiterer Bereich mit den Monatssummen pro '.
'Abwesenheitstyp angezeigt. Hinweis: Die Abwesenheitstypen, '.
'die in der Summenanzeige enthalten sein sollen, m&uuml;ssen entsprechend konfiguriert werden.';

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
$LANG['icon_template'] = 'Template User (Abwesenheiten werden auf alle Mitglieder der gleichen Gruppe kopiert.)';
$LANG['template_user'] = '[Template User]';

/**
 * Configuration Page
 */
$LANG['admin_config_repeatheadersafter'] = 'Gruppenzuordnung Anzeige: Kopfzeile wiederholen nach';
$LANG['admin_config_repeatheadersafter_comment'] = 'Bestimmt die Anzahl der Reihen, nach denen die Kopfzeile wiederholt wird, um eine bessere Lesbarkeit zu erreichen.';
$LANG['admin_config_repeatusernamesafter'] = 'Gruppenzuordnung Anzeige: Benutzernamen wiederholen nach';
$LANG['admin_config_repeatusernamesafter_comment'] = 'Bestimmt die Anzahl der Gruppenspalten, nach denen die Benutzernamen wiederholt werden, um eine bessere Lesbarkeit zu erreichen.';
$LANG['admin_config_optionsbar'] = 'Optionsleiste';
$LANG['admin_config_optionsbar_language'] = 'Sprachauswahl anzeigen';
$LANG['admin_config_optionsbar_group'] = 'Gruppenauswahl anzeigen';
$LANG['admin_config_optionsbar_today'] = 'Heute-Auswahl anzeigen';
$LANG['admin_config_optionsbar_start'] = 'Start-Auswahl anzeigen';
$LANG['admin_config_pastdaycolor'] = 'Vergangenheitsfarbe';
$LANG['admin_config_pastdaycolor_comment'] = 'Setzt die Hintergrundfarbe f&uuml;r die Tage des aktuellen Monats, die in der Vergangenheit liegen. '
.'Bei keinem Wert in diesem Feld wird keine Farbe eingesetzt.';

/**
 * Filter
 */
$LANG['nav_absencefilter'] = 'Heute:';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.004
 */

/**
 * Printout
 */
$LANG['print_title'] = 'TeamCal Pro Ausdruck';

/**
 * Configuration Page
 */
$LANG['admin_config_hide_managers'] = 'Manager in Alle-nach-Gruppen und Gruppen Anzeige verbergen';
$LANG['admin_config_hide_managers_comment'] = 'Mit dieser Option werden alle Manager in der Alle-nach-Gruppen und Gruppen Anzeige verborgen mit Ausnahme der Gruppen, in der sie nur Mitglied sind.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.005
 */

/**
 * Declination Management Page
 */
$LANG['admin_decl_before_today'] = 'Abwesenheiten ablehnen, die in der Vergangenheit liegen (vom jeweiligen Tag der Anfrage)';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.1.006
 */

/**
 * Configuration Page
 */
$LANG['admin_config_firstdayofweek'] = 'Erster Wochentag';
$LANG['admin_config_firstdayofweek_comment'] = 'Dieser kann auf Montag oder Sonntag gesetzt werden. Die Auswahl wirkt sich auf die Anzeige der Wochennummern aus.';
$LANG['admin_config_firstdayofweek_1'] = 'Montag';
$LANG['admin_config_firstdayofweek_7'] = 'Sonntag';
$LANG['admin_config_defgroupfilter'] = 'Default Gruppenfilter';
$LANG['admin_config_defgroupfilter_comment'] = 'Auswahl des Default Gruppenfilters f&uuml;r die Kalenderanzeige. Jeder User kann diese Einstellung individuell in seinem Profil &auml;ndern.';

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
$LANG['err_input_region_add'] = 'Mindestens ein Kurzname muss angegeben werden, um eine Region hinzuzuf&uuml;gen..';

/**
 * Admin Pages
 */
$LANG['admin_region_title'] = 'Regions-Verwaltung';

/**
 * Configuration Page
 */
$LANG['admin_config_defregion'] = 'Default Region f&uuml;r Basiskalendar';
$LANG['admin_config_defregion_comment'] = 'Auswahl der Default Region f&uuml;r den Basiskalender. Jeder User kann diese Einstellung individuell in seinem Profil &auml;ndern.';
$LANG['admin_config_optionsbar_comment'] =
'Die Optionsleiste liegt gleich unter der Menuzeile. Sie enth&auml;lt die Sprachauswahl, Gruppenauswahl '.
'und andere Filteroptionen f&uuml;r die Kalenderanzeige. Diese k&ouml;nnen hier ein- bzw. ausgeschaltet werden.'.
'</span><ul style="list-style: square; margin-left: 0px;">'.
'<li><span class="function">Gruppenauswahl</span><br><span class="config-comment">Zeigt die Drop Down Liste zur Gruppenauswahl an.</span></li>'.
'<li><span class="function">Regionsauswahl</span><br><span class="config-comment">Zeigt die Drop Down Liste zur Regionsauswahl an.</span></li>'.
'<li><span class="function">Heute-Auswahl</span><br><span class="config-comment">Zeigt die Drop Down Liste zur heutigen Abwesenheit an.</span></li>'.
'<li><span class="function">Start-Auswahl</span><br><span class="config-comment">Zeigt die Auswahl zum Startjahr, Startmonat und Anzahl der angezeigten Monate an.</span></li>'.
'</ul><span class="config-comment">';
$LANG['admin_config_optionsbar_region'] = 'Regionsauswahl anzeigen';
$LANG['admin_config_hide_daynotes'] = 'Pers&ouml;nliche Tagesnotizen verbergen';
$LANG['admin_config_hide_daynotes_comment'] = 'Mt diesem Schalter k&ouml;nnen die pers&ouml;nlichen Tagesnotizen vor normalen Nutzern verborgen werden. Nur Manager, Direktoren und Administratoren k&ouml;nnen sie editieren und sehen. So k&ouml;nnen sie f&uuml;r Managementzwecke genutzt werden. Dieser Schalter beeinflusst nicht die Geburtstagsnotizen.';

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
$LANG['admin_dbmaint_del_chkRegions'] = 'Alle Regionen und deren Feiertage l&ouml;schen (ausser "default")';

/**
 * Database Export (Database Maintenance Page)
 */
$LANG['exp_table_region'] = 'Regionen';

/**
 * Edit Absence Dialog
 */
$LANG['ea_column_manager_only'] = "M";
$LANG['ea_column_allowance_mouseover'] = "Erlaubte Anzahl";
$LANG['ea_column_factor_mouseover'] = "Faktor";
$LANG['ea_column_showremain_mouseover'] = "Verbleibende Anzeigen";
$LANG['ea_column_showtotals_mouseover'] = "Summe Anzeigen";
$LANG['ea_column_approval_mouseover'] = "Genehmigung Erforderlich";
$LANG['ea_column_presence_mouseover'] = "Z&auml;hlt als Anwesend";
$LANG['ea_column_manager_only_mouseover'] = "Nur Management";
$LANG['ea_column_groups_mouseover'] = "Abwesenheit zu Gruppe Zuordnung";
$LANG['ea_column_hide_in_profile'] = "H";
$LANG['ea_column_hide_in_profile_mouseover'] = "In Nutzerprofil verbergen";

/**
 * Edit Groups Dialog
 */
$LANG['column_min_present'] = 'Min';
$LANG['column_max_absent'] = 'Max';

/**
 * Notification Messages
 */
$LANG['notification_decl_minpresent'] = "Minimum anwesender Gruppenmitglieder erreicht: ";
$LANG['notification_decl_minpresent1'] = " Mitglieder von ";
$LANG['notification_decl_minpresent2'] = " m&uuml;ssen mindestens gleichzeitig anwesend sein. Mit dieser Anfrage w&uuml;rde der Wert unterschritten.";
$LANG['notification_decl_maxabsent'] = "Maximum abwesender Gruppenmitglieder erreicht: ";
$LANG['notification_decl_maxabsent1'] = " Mitglieder von ";
$LANG['notification_decl_maxabsent2'] = " d&uuml;rfen maximal gleichzeitig abwesend sein. Mit dieser Anfrage w&uuml;rde dieser Wert &uuml;berschritten.";

/**
 * Error Messages
 */
$LANG['err_decline_minpresent1'] = 'Der Administrator hat eine minimale Anzahl gleichzeitig anwesender Mitglieder festgesetzt,\\nund zwar ';
$LANG['err_decline_minpresent2'] = ' Mitglieder deiner Gruppe: ';
$LANG['err_decline_maxabsent1'] = 'Der Administrator hat eine meximale Anzahl gleichzeitig abwesender Mitglieder festgesetzt,\\nund zwar ';
$LANG['err_decline_maxabsent2'] = ' Mitglieder deiner Gruppe: ';
$LANG['err_decline_period_1'] = 'Der Administrator hat einen Ablehnungszeitraum definiert.\\nEs sind keine Abwesenheiten erlaubt beginnend am (einschlie&szlig;lich) ';
$LANG['err_decline_period_2'] = ' und endend am (einschlie&szlig;lich) ';

/**
 * Declination Management Page
 */
$LANG['admin_decl_period'] = 'Abwesenheitsanfragen ablehnen in folgendem Zeitraum.';
$LANG['admin_decl_period_start'] = 'Der Ablehnungszeitraum beginnt (einschlie&szlig;lich):&nbsp;';
$LANG['admin_decl_period_end'] = '&nbsp;und endet am (einschlie&szlig;lich):&nbsp;';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.2.003
 */

/**
 * Statistics Page
 */
$LANG['stat_all'] = 'Alle';
$LANG['stat_group'] = 'Gruppe';
$LANG['stat_absence'] = 'Abwesenheitstyp';
$LANG['stat_results_total_absence_user'] = 'Summe der Abwesenheiten pro Mitglied im Zeitraum:&nbsp;&nbsp;';
$LANG['stat_results_total_presence_user'] = 'Summe der Anwesenheiten pro Mitglied im Zeitraum:&nbsp;&nbsp;';
$LANG['stat_results_total_absence_group'] = 'Summe der Abwesenheiten pro Gruppe im Zeitraum:&nbsp;&nbsp;';
$LANG['stat_results_total_presence_group'] = 'Summe der Anwesenheiten pro Gruppe im Zeitraum:&nbsp;&nbsp;';
$LANG['stat_choose_period'] = 'Standard Zeitraum';
$LANG['stat_choose_custom_period'] = 'Individual Zeitraum';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.001
 */

/**
 * Statistics Page
 */
$LANG['stat_u_title'] = 'Nutzerstatistik';
$LANG['stat_u_total'] = 'Summe:';
$LANG['stat_u_taken'] = 'Genommen';
$LANG['stat_u_sel_group_user'] = 'Gruppe bzw. Nutzer ausw&auml;hlen';
$LANG['stat_u_sel_group'] = 'Gruppe ausw&auml;hlen:';
$LANG['stat_u_sel_user'] = 'Nutzer ausw&auml;hlen:';
$LANG['stat_graph_u_remainder_title_1'] = 'Verbleibend in Periode (';
$LANG['stat_graph_u_remainder_title_2'] = ') f&uuml;r Nutzer: ';
$LANG['stat_u_type'] = 'Typ';
$LANG['stat_u_total_remainder'] = 'Verbleibend';

/**
 * Edit Absence Dialog
 */
$LANG['ea_column_confidential'] = "V";
$LANG['ea_column_confidential_mouseover'] = "Abwesenheitstyp ist vertraulich";

/**
 * Calendar Edit Dialog
 */
$LANG['cal_recurring_workdays'] = "Mo-Fr";
$LANG['cal_recurring_weekend'] = "Sa-So";

/**
 * Configuration Page
 */
$LANG['admin_config_defperiod'] = 'Standard Erlaubnis Periode';
$LANG['admin_config_defperiod_comment'] = 'W&auml;hle hier das Start- und Endedatum der standard Erlaubnisperiode. Dies ist normalerweise das ' .
      'Kalenderjahr, vom 1.Jan. bis 31.Dez.. Vielleicht nutz du aber eine andere Periode gegen die die Abwesenheiten berechnet werden. Wenn du eine ' .
      'andere Periode definierst, bedenke, dass sich dann die Begriffe "aktuelles Jahr" and "vorheriges Jahr" auf die neue Periode beziehen. ' .
      'Das Von-Datum muss kleiner sein als das Bis-Datum.';
$LANG['admin_config_defperiod_from'] = 'Von';
$LANG['admin_config_defperiod_to'] = 'Bis';

/**
 * Statistics Page
 */
$LANG['stat_period_period'] = 'Aktuelle Standard Periode';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.004
 */

/**
 * Configuration Page
 */
$LANG['admin_config_mark_confidential'] = 'Vertrauliche Abwesenheiten Markieren';
$LANG['admin_config_mark_confidential_comment'] = 'Normale Nutzer k&ouml;nnen vertrauliche Abwesenheiten anderer Nutzer nicht sehen. Mit dieser ' .
      'Option hier k&ouml;nnen diese jedoch mit einem "X" im Kalender gekennzeichnet werden, so dass deren Abwesenheit erkennbar ist.';
$LANG['admin_config_homepage'] = 'Startseite';
$LANG['admin_config_homepage_comment'] = 'Hier kann die Startseite nach dem Login und Logout eingestellt werden. Dies kann entweder die Willkommen Seite sein oder die Kalenderansicht.';
$LANG['admin_config_homepage_welcome'] = 'Willkommen Seite';
$LANG['admin_config_homepage_calendar'] = 'Kalender';
$LANG['admin_config_welcome'] = 'Willkommen Seite Text';
$LANG['admin_config_welcome_comment'] = 'Hier kann ein Titel und ein Text f&uuml;r die Willkommen Seite eingegeben werden. Die Felder erlauben die '.
'Verwendung von den HTML Tags < i > und < b >. Zeilenumbr&uuml;che werden automatisch in < br > Tags &uuml;bersetzt. Alle anderen HTML Tags werden entfernt.';

/**
 * Userlist Page
 */
$LANG['user_pwd_reset_confirm'] = "Bist du sicher, dass du das Passw&ouml;rter der ausgew&auml;hlten Nutzer zur&uuml;cksetzen willst?\\r\\n";

/**
 * Notification Messages
 */
$LANG['notification_usr_pwd_subject']   = $CONF['app_name']." ".$CONF['app_version']." - Dein Neues Passwort";
$LANG['notification_usr_pwd_reset'] = "Der Administrator hat dein Passwort zur&uuml;ckgesetzt.\r\n\r\nDein neues Passwort ist: ";
$LANG['notification_sign'] = "Deine TeamCal Pro Administration.\n";

/**
 * User Profile Dialog
 */
$LANG['profile_added'] = 'Der Nutzer wurde hinzugef&uuml;gt.';

/**
 * Welcome Page
 */
$LANG['welcome_title'] = 'Willkommen bei TeamCal Pro';

/**
 * Message Dialog
 */
$LANG['message_type_announcement_welcome'] = 'Willkommen Seite';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.007
 */

/**
 * User Registration Dialog
 */
 $LANG['register_error_email'] = 'Du musst eine g&uuml;ltige E-Mail Adresse f&uuml;r die Registrierung angeben.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.3.010
 */

/**
 * Configuration Page
 */
$LANG['admin_config_usersperpage'] = 'Anzahl User pro Seite';
$LANG['admin_config_usersperpage_comment'] = 'Wenn du eine gro&szlig;e Anzahl an Usern in TeamCal Pro pflegst, bietet es sich an, die Kalenderanzeige in ' .
      'Seiten aufzuteilen. Gebe hier an, wieviel User pro Seite angezeigt werden sollen. Ein Wert von 0 zeigt alle User auf einer Seite an. Wenn du ' .
      'eine Seitenaufteilung w&auml;hlst, werden am Ende der Seite Schaltfl&auml;chen fuer das Bl&auml;ttern angezeigt.';
$LANG['admin_config_mail_options'] = 'E-Mail Optionen';
$LANG['admin_config_mail_smtp'] = 'Externen SMTP Server benutzen';
$LANG['admin_config_mail_smtp_comment'] = 'Mit diesm Schalter wird ein externer SMTP Server zum Versenden von E-Mails benutzt anstatt der PHP mail() '.
'Funktion. Diese Feature erfordert das PEAR Mail Paket auf dem Tcpro Server. Viele Hoster installieren dieses Paket als Standard. '.
'Ausserdem ist es erforderlich, dass sich der Tcro Server per SMTP oder TLS/SSL protocol mit den gebr&auuml;chlichen SMTP port 25, 465 und 587 mit dem '.
'SMTP Server verbinden kann. Bei einigen Hostern ist dies durch Firewalleinstellungen nicht m&ouml;glich. '.
'Es erscheint dann eie Fehlermeldung.';
$LANG['admin_config_mail_smtp_host'] = 'SMTP Host';
$LANG['admin_config_mail_smtp_host_comment'] = 'Gib den SMTP Host Namen an.';
$LANG['admin_config_mail_smtp_port'] = 'SMTP Port';
$LANG['admin_config_mail_smtp_port_comment'] = 'Gib den SMTP Host Port an.';
$LANG['admin_config_mail_smtp_username'] = 'SMTP Benutzername';
$LANG['admin_config_mail_smtp_username_comment'] = 'Gib den SMTP Benutzernamen an.';
$LANG['admin_config_mail_smtp_password'] = 'SMTP Passwort';
$LANG['admin_config_mail_smtp_password_comment'] = 'Gib das SMTP Passwort an.';
$LANG['admin_config_satbusi'] = 'Samstag ist ein Arbeitstag';
$LANG['admin_config_satbusi_comment'] = 'Mit dieser Option wird Samstag als Arbeitstag dargestellt und gez&auml;hlt.';
$LANG['admin_config_sunbusi'] = 'Sonntag ist ein Arbeitstag';
$LANG['admin_config_sunbusi_comment'] = 'Mit dieser Option wird Sonntag als Arbeitstag dargestellt und gez&auml;hlt.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.000
 */

/**
 * Regions Page
 */
$LANG['tt_region'] = 'Region';
$LANG['tt_add_region'] = 'Neue Region anlegen';
$LANG['tt_add_ical'] = 'iCal als neue Region importieren';
$LANG['region_caption_add'] = 'Regionen Anlegen';
$LANG['region_caption_existing'] = 'Regionen Bearbeiten';
$LANG['err_input_no_filename'] = "Es wurde kein Dateiname angegeben.";
$LANG['region_ical_description'] = 'W&auml;hle eine iCal Datei mit Ganztagsereignissen (z.B. Schulferien) von einem lokalen Laufwerk. Dann w&auml;hle einen Feiertagstyp, der f&uuml;r alle Ereignisse in der iCal Datein benutzt werden soll. Dann klicke auf ['.$LANG['btn_import_ical'].']';
$LANG['reg_delete_confirm'] = 'Bist du sicher, dass du diese Region l&ouml;schen willst?';
$LANG['region_caption_merge'] = 'Regionen Verschmelzen';
$LANG['column_source_region'] = 'Quellregion';
$LANG['column_target_region'] = 'Zielregion';
$LANG['column_overwrite'] = '&Uuml;berschreiben';
$LANG['err_input_same_region'] = 'Du kannst keine Region mit sich selbst verschmelzen.';
$LANG['err_input_region_exists'] = 'Eine Region mit dem Kurznamen existiert bereits. Lösche die bestehende Region zuerst oder wähle einen anderen Kurznamen.';

/**
 * Edit month page
 */
$LANG['tt_page_bwd'] = "Einen Monat zur&uuml;ck bl&auml;ttern...";
$LANG['tt_page_fwd'] = "Einen Monat vorw&auml;rts bl&auml;ttern...";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.001
 */

/**
 * Userlist Page
 */
$LANG['notification_usr_pwd_reset_user'] = "Dein Benutzername ist: ";
$LANG['notification_usr_pwd_reset_pwd'] = "Dein neues Passwort ist: ";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.002
 */

/**
 * Edit Calendar Page
 */
$LANG['cal_only_business'] = "Nur Arbeitstage markieren";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.4.003
 */

/**
 * Error Messages
 */
$LANG['err_decl_title'] = "[EINGABE-VALIDIERUNG]\\n\\nBitte nimm mit deinem Manager Kontakt auf.\\n";
$LANG['err_decl_subtitle'] = "Nicht alle deine Abwesenheiten konnten wegen der folgenden Gruende angenommen werden:\\n\\n";
$LANG['err_decl_day'] = "Tag ";
$LANG['err_decl_group_threshold'] = ": Die Abwesenheitsgrenze wurde erreicht für die Gruppe(n): ";
$LANG['err_decl_total_threshold'] = ": Die generelle Abwesenheitsgrenze wurde erreicht.";
$LANG['err_decl_min_present'] = ": Die minimale Anwesenheit wurde unterschritten fuer die Gruppe(n): ";
$LANG['err_decl_max_absent'] = ": Die maximale Abwesenheit wurde überschritten fuer die Gruppe(n): ";
$LANG['err_decl_before'] = ": Kalenderaenderungen sind nicht erlaubt an und vor ";
$LANG['err_decl_period'] = ": Kalenderaenderungen sind nicht erlaubt zwischen ";
$LANG['err_decl_and'] = " und ";
$LANG['err_decl_abs'] = ": Der Abwesenheitstyp '";
$LANG['err_decl_old_abs'] = ": Der bereits eingetragene Abwesenheitstyp '";
$LANG['err_decl_new_abs'] = ": Der angefragte Abwesenheitstyp '";
$LANG['err_decl_approval'] = "' erfordert Managerbestaetigung und kann nicht geaendert oder gesetzt werden.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.000
 */

/**
 * Permission Scheme Page
 */
$LANG['perm_select_confirm'] = "Soll dieses Berechtigungsschema gelöscht willst?\\nAlle nicht gespeicherten Änderungen des momentanen Schemas gehen verloren.";
$LANG['perm_activate_confirm'] = "Soll dieses Berechtigungsschema aktiviert werden?";
$LANG['perm_reset_confirm'] = "Soll dieses Berechtigungsschema auf den Standard zurückgesetzt werden?";
$LANG['perm_delete_confirm'] = "Soll dieses Berechtigungsschema gelöscht werden?\\nDadurch wird das Standard Scheme geladen und aktiviert.";
$LANG['perm_title'] = "Berechtigungsschema: ";
$LANG['perm_sel_scheme'] = "Schema ausw&auml;hlen";
$LANG['perm_create_scheme'] = "Schema anlegen";
$LANG['perm_col_perm_admin'] = "Administrative Berechtigungen";
$LANG['perm_col_perm_cal'] = "Globale Kalender Berechtigungen";
$LANG['perm_col_perm_user'] = "Nutzerbezogene Berechtigungen";
$LANG['perm_col_perm_view'] = "Ansicht Berechtigungen";
$LANG['perm_col_admin'] = "Administrator";
$LANG['perm_col_admin_tt'] = "Administratoren haben diese Berechtigung";
$LANG['perm_col_director'] = "Direktor";
$LANG['perm_col_director_tt'] = "Direktoren haben diese Berechtigung";
$LANG['perm_col_manager'] = "Manager";
$LANG['perm_col_manager_tt'] = "Manager haben diese Berechtigung";
$LANG['perm_col_user'] = "Nutzer";
$LANG['perm_col_user_tt'] = "Nutzer haben diese Berechtigung";
$LANG['perm_col_public'] = "Besucher";
$LANG['perm_col_public_tt'] = "Besucher (nicht eingeloggt) haben diese Berechtigung";
$LANG['perm_perm_editConfig_title'] = "TeamCal Pro Konfiguration bearbeiten";
$LANG['perm_perm_editConfig_desc'] = "Erlaubt die Bearbeitung der TeamCal Pro Konfiguration.";
$LANG['perm_perm_editPermissionScheme_title'] = "Berechtigungsschema bearbeiten";
$LANG['perm_perm_editPermissionScheme_desc'] = "Erlaubt die Bearbeitung der Berechtigungsschemen. Diese Berechtigung kann f&uuml;r Administratoren nicht abgeschaltet werden.";
$LANG['perm_perm_manageUsers_title'] = "Nutzer bearbeiten";
$LANG['perm_perm_manageUsers_desc'] = "Erlaubt das Anlegen, Importieren, Bearbeiten und L&ouml;schen von Nutzerkonten.";
$LANG['perm_perm_manageGroups_title'] = "Gruppen bearbeiten";
$LANG['perm_perm_manageGroups_desc'] = "Erlaubt das Anlegen, Bearbeiten und L&ouml;schen von Gruppen.";
$LANG['perm_perm_manageGroupMemberships_title'] = "Gruppenmitgliedschaft bearbeiten";
$LANG['perm_perm_manageGroupMemberships_desc'] = "Erlaubt das Bearbeiten von Gruppenmitgliedschaften.";
$LANG['perm_perm_editAbsenceTypes_title'] = "Abwesenheitstypen bearbeiten";
$LANG['perm_perm_editAbsenceTypes_desc'] = "Erlaubt das Bearbeiten von Abwesenheitstypen.";
$LANG['perm_perm_editRegions_title'] = "Regionen bearbeiten";
$LANG['perm_perm_editRegions_desc'] = "Erlaubt das Bearbeiten von Regionen.";
$LANG['perm_perm_editHolidays_title'] = "Feiertage bearbeiten";
$LANG['perm_perm_editHolidays_desc'] = "Erlaubt das Bearbeiten von Feiertagen.";
$LANG['perm_perm_editDeclination_title'] = "Genehmigungsregeln bearbeiten";
$LANG['perm_perm_editDeclination_desc'] = "Erlaubt das Bearbeiten von Genehmigungsregeln.";
$LANG['perm_perm_manageDatabase_title'] = "Datenbank verwalten";
$LANG['perm_perm_manageDatabase_desc'] = "Erlaubt das Verwaltung der Datenbanke.";
$LANG['perm_perm_viewSystemLog_title'] = "System Log anzeigen";
$LANG['perm_perm_viewSystemLog_desc'] = "Erlaubt das Anzeigen des System Logbuchs.";
$LANG['perm_perm_editSystemLog_title'] = "System Log bearbeiten";
$LANG['perm_perm_editSystemLog_desc'] = "Erlaubt das Einstellen des System Logbuchs.";
$LANG['perm_perm_viewEnvironment_title'] = "Umgebungsvariablen anzeigen";
$LANG['perm_perm_viewEnvironment_desc'] = "Erlaubt das Anzeigen der Umgebungsvariablen von TeamCal Pro und von PHP Informationen.";
$LANG['perm_perm_viewStatistics_title'] = "Statistik anzeigen";
$LANG['perm_perm_viewStatistics_desc'] = "Erlaubt das Anzeigen der Statistiken.";
$LANG['perm_perm_editGlobalCalendar_title'] = "Globalen Kalender bearbeiten";
$LANG['perm_perm_editGlobalCalendar_desc'] = "Erlaubt das Bearbeiten des globalen Kalenders aller Regionen, z.B. um Feiertage einzutragen.";
$LANG['perm_perm_editGlobalDaynotes_title'] = "Global Tagesnotizen bearbeiten";
$LANG['perm_perm_editGlobalDaynotes_desc'] = "Erlaubt das Bearbeiten von globalen Tagesnotizen aus dem globalen Kalender heraus.";
$LANG['perm_perm_useMessageCenter_title'] = "Nachrichten senden";
$LANG['perm_perm_useMessageCenter_desc'] = "Erlaubt die Nutzung des Nachrichten Dialogs.";
$LANG['perm_perm_viewCalendar_title'] = "Kalender &ouml;ffnen";
$LANG['perm_perm_viewCalendar_desc'] = "Erlaubt die generelle Anzeige des Kalenders. Ohne diese Berechtigung kann kein Kalender angezeigt werden. Mit dieser Berechtigung kann Besuchern die Anzeige des Kalenders erlaubt werden.";
$LANG['perm_perm_viewYearCalendar_title'] = "Jahreskalender anzeigen";
$LANG['perm_perm_viewYearCalendar_desc'] = "Erlaubt die Anzeige des Jahreskalenders.";
$LANG['perm_perm_viewAnnouncements_title'] = "Ank&uuml;ndigungen anzeigen";
$LANG['perm_perm_viewAnnouncements_desc'] = "Erlaubt die Anzeige von Ank&uuml;ndigungen. Es werden aber immer nur die Ank&uuml;ndigungen des aktuell eingeloggten Nutzers angezeigt.";
$LANG['perm_perm_viewAllGroups_title'] = "Alle Gruppen anzeigen";
$LANG['perm_perm_viewAllGroups_desc'] = "Erlaubt die Anzeige aller Gruppen in Kalendern und Filtern. Ohne diese Berechtigung k&ouml;nnen nur die eigenen Gruppen gesehen werden (Mitglied oder Manager).";
$LANG['perm_perm_viewAllUserCalendars_title'] = "Alle Nutzerkalender anzeigen";
$LANG['perm_perm_viewAllUserCalendars_desc'] = "Erlaubt die Anzeige aller Nutzerkalender. Ein Nutzer kann aber immer seinen eigenen Kalender anzeigen.";
$LANG['perm_perm_viewGroupUserCalendars_title'] = "Gruppenkalender anzeigen";
$LANG['perm_perm_viewGroupUserCalendars_desc'] = "Erlaubt die Anzeige aller Nutzerkalender der eigenen Gruppen (Mitglied oder Manager). Ein Nutzer kann aber immer seinen eigenen Kalender anzeigen.";
$LANG['perm_perm_viewUserProfiles_title'] = "Nutzerprofile anzeigen";
$LANG['perm_perm_viewUserProfiles_desc'] = "Erlaubt die Anzeige von allen Nutzerprofilen mit Basisinformationen wie Name oder Telefonnummer. Das Anzeigen von User Popups ist ebenfalls abh&auml;ngig von dieser Berechtigung.";
$LANG['perm_perm_editAllUserAllowances_title'] = "Alle Abwesenheistkontingente bearbeiten";
$LANG['perm_perm_editAllUserAllowances_desc'] = "Erlaubt die Bearbeitung der Abwesenheitskontingente aller Nutzer. Mit dieser Berechtigung kann man die Kontingente im Nutzerprofildialog bearbeiten.";
$LANG['perm_perm_editGroupUserAllowances_title'] = "Gruppen Abwesenhietskontingente bearbeiten";
$LANG['perm_perm_editGroupUserAllowances_desc'] = "Erlaubt die Bearbeitung der Abwesenheitskontingente aller Gruppenmitglieder. Mit dieser Berechtigung kann man die Kontingente im Nutzerprofildialog bearbeiten.";
$LANG['perm_perm_viewUserAbsenceCounts_title'] = "Nutzer-Abwesenheitssummen anzeigen";
$LANG['perm_perm_viewUserAbsenceCounts_desc'] = "Erlaubt die Anzeige der Abwesenheitssummen eines Nutzers.";
$LANG['perm_perm_editAllUserProfiles_title'] = "Alle Nutzerprofile bearbeiten";
$LANG['perm_perm_editAllUserProfiles_desc'] = "Erlaubt die Bearbeitung aller Nutzerprofile. Ein Nutzer kann aber immer sein eigenes Profil bearbeiten.";
$LANG['perm_perm_editGroupUserProfiles_title'] = "Gruppen Nutzerprofile bearbeiten";
$LANG['perm_perm_editGroupUserProfiles_desc'] = "Erlaubt die Bearbeitung aller Nutzerprofile der eigenen Gruppenmitglieder. Ein Nutzer kann aber immer sein eigenes Profil bearbeiten aber nicht das seines eigenen Managers.";
$LANG['perm_perm_editAllUserCalendars_title'] = "Alle Nutzerkalender bearbeiten";
$LANG['perm_perm_editAllUserCalendars_desc'] = "Erlaubt die Bearbeitung aller Nutzerkalender.";
$LANG['perm_perm_editGroupUserCalendars_title'] = "Gruppenkalender bearbeiten";
$LANG['perm_perm_editGroupUserCalendars_desc'] = "Erlaubt die Bearbeitung aller Nutzerkalender der eigene Gruppenmitglieder. Ein Nutzer kann aber nicht den Kalender seines eigenen Managers bearbeiten.";
$LANG['perm_perm_editOwnUserCalendars_title'] = "Eigenen Nutzerkalender bearbeiten";
$LANG['perm_perm_editOwnUserCalendars_desc'] = "Erlaubt die Bearbeitung des eigenen Nutzeralenders. Wenn nur eine zentrale Bearbeitung erw&uuml;nscht ist, kann hiermit die Berechtigung den Nutzern entziehen.";
$LANG['perm_perm_editAllUserDaynotes_title'] = "Alle Nutzertagesnotizen bearbeiten";
$LANG['perm_perm_editAllUserDaynotes_desc'] = "Erlaubt die Bearbeitung aller Nutzertagesnotizen. Ein Nutzer kann aber immer seine eigenen Tagesnotizen bearbeiten.";
$LANG['perm_perm_editGroupUserDaynotes_title'] = "Gruppentagesnotizen bearbeiten";
$LANG['perm_perm_editGroupUserDaynotes_desc'] = "Erlaubt die Bearbeitung aller Nutzertagesnotizen der eigenen Gruppenmitglieder. Ein Nutzer kann aber immer seine eigenen Tagesnotizen bearbeiten aber nicht die seines eigenen Managers.";

/**
 * Config page
 */
$LANG['admin_config_pscheme'] = "Berechtigungsschema";
$LANG['admin_config_pscheme_comment'] = "Hiermit wird das aktive Berechtigungsschema ausgew&auml;hlt. Das Schema kann auf der Berechtigungsschema Seite bearbeitet werden.";
$LANG['admin_config_system_options'] = 'System Optionen';
$LANG['admin_config_jQueryCDN'] = 'jQuery CDN';
$LANG['admin_config_jQueryCDN_comment'] = 'CDNs (Content Distributed Network) k&ouml;nnen einen Performance-Vorteil bieten dadurch dass popul&auml;re '.
      'Web Module von Servern rund um den Globus geladen werden. jQuery ist so ein Modul, dass auch von TeamCal Pro genutzt wird. Wenn es von einem CDN Server '.
      'geladen wird, von dem das gleiche Modul f&uuml;r den Nutzer schon durch eine andere Anwedung geladen wurde, ist es bereits im Cache des Nutzers und '.
      'muss nicht nochmal heruntergeladen werden.<br>Schalte diese Option aus, wenn du TeamCal Pro in einer Umgebung ohne Internetverbindung betreibst.';
$LANG['admin_config_welcomeIcon'] = 'Willkommen Text Icon';
$LANG['admin_config_welcomeIcon_comment'] = 'Mit dem Willkommenstext kann das TeamCal Kalender Icon angezeigt werden. Es wird oben links platziert '.
      'und der Text flie&szlig;t um es herum. Die Gr&ouml;$szlig;e kann in der Drop Down Liste ausgew&auml;hlt werden.';
$LANG['no']='Nein';
$LANG['admin_config_googleAnalytics'] = "Google Analytics";
$LANG['admin_config_googleAnalytics_comment'] = "TeamCal Pro unterst&uuml;tzt Google Analytics. Wenn du deine TeamCal Pro Instanz im Internet betreibst ".
      "und den Zugriff von Google Analytics tracken lassen willst, ticke die Checkbox hier und trage deine Google Analytics ID ein. TeamCal Pro wird den entsprechenden ".
      "Javascript Code einf&uuml;gen.";
$LANG['admin_config_googleAnalyticsID'] = "Google Analytics ID";
$LANG['admin_config_webMeasure'] = "webMeasure";
$LANG['admin_config_webMeasure_comment'] = "webMeasure ist ein kostenloses Produkt von Lewe.com zur Umrechnung von Ma&szlig;einheiten. Es ist ein Onlne Service gehostet auf Lewe.com und im Internet ver&uuml;gbar. ".
      "Mit dieser Option kann webMeasure als Bonusfeature im Tools menu angeboten werden.";

/**
 * System Log Page
 */
$LANG['log_title'] = 'System Logbuch';
$LANG['log_settings'] = 'Logbuch-Einstellungen';
$LANG['log_settings_event'] = 'Ereignistyp';
$LANG['log_settings_log'] = 'Ereignistyp loggen';
$LANG['log_settings_show'] = 'Ereignistyp im Logbuch anzeigen';
$LANG['log_sort_asc'] = 'Aufsteigend sortieren...';
$LANG['log_sort_desc'] = 'Absteigend sortieren...';
$LANG['log_header_timestamp'] = 'Zeitstempel (UTC)';
$LANG['log_header_type'] = 'Ereignistyp';
$LANG['log_header_user'] = 'Nutzer';
$LANG['log_header_event'] = 'Ereignis';
$LANG['logfilterAbsence'] = 'Abwesenheitstyp';
$LANG['logfilterAnnouncement'] = 'Ank&uuml;ndigung';
$LANG['logfilterConfig'] = 'Konfiguration';
$LANG['logfilterDatabase'] = 'Datenbank';
$LANG['logfilterDaynote'] = 'Tagesnotiz';
$LANG['logfilterGroup'] = 'Gruppe';
$LANG['logfilterHoliday'] = 'Feiertag';
$LANG['logfilterLogin'] = 'Login';
$LANG['logfilterLoglevel'] = 'Login';
$LANG['logfilterMonth'] = 'Monat';
$LANG['logfilterPermission'] = 'Berechtigung';
$LANG['logfilterRegion'] = 'Region';
$LANG['logfilterRegistration'] = 'Registrierung';
$LANG['logfilterUser'] = 'Nutzer';

/**
 * Error Messages
 */
$LANG['err_input_caption'] = '[EINGABEFEHLER]';
$LANG['err_input_hol_add'] = 'Du musst einen Namen, eine Textfarbe und eine Hintergrundfarbe angeben.';
$LANG['err_input_abs_taken_1'] = 'Das Abwesenheitssymbol \'';
$LANG['err_input_abs_taken_2'] = ' ist schon vergeben. Bitte w&auml;hle eins, das noch frei ist.';
$LANG['err_input_abs_add'] = 'Du musst ein Symbol angeben, unter dem der neue Abwesenheitstyp angelegt werden soll.';
$LANG['err_input_abs_invalid_1'] = 'Das Abwesenheitssymbol \'';
$LANG['err_input_abs_invalid_2'] = '\' ist ung&uuml;ltig. Bitte nur Grossbuchstaben und Zahlen von 0-9 ein.';
$LANG['err_input_reg_invalid_1'] = 'Der Regionsname \'';
$LANG['err_input_reg_invalid_2'] = '\' ist ung&uuml;ltig. Bitte nur Grossbuchstaben und Zahlen von 0-9 ein. Leerzeichen sind nicht erlaubt.';
$LANG['err_input_reg_add'] = 'You have to add at least a shortname in order to add a new region.';
$LANG['err_input_perm_invalid_1'] = 'Der Name des Berechtigungsschemas \'';
$LANG['err_input_perm_invalid_2'] = '\' ist ung&uuml;ltig. Bitte nur Grossbuchstaben und Zahlen von 0-9 ein. Leerzeichen sind nicht erlaubt.';
$LANG['err_input_perm_exists_1'] = 'Das Berechtigungsschema \'';
$LANG['err_input_perm_exists_2'] = '\' existiert bereits. Bitte nimm einen anderen Namen oder l&ouml;sche das alte zuerst.';
$LANG['err_input_group_add'] = 'Du musst mindestens einen Namen f&uuml;r die neue Gruppe angeben.';
$LANG['err_input_max_daytype'] = 'Du hast die maximale Anzahl von Feiertagstypen erreicht.\\nBitte l&ouml;sche erst einen anderen.\\n';
$LANG['err_input_dbmaint_clean'] = 'Bitte sowohl Jahr als auch Monat angeben,\\num alte Templates und Notizen zu l&ouml;schen.\\n';
$LANG['err_input_dbmaint_clean_confirm'] = 'Das Aufr&auml;umen der Datenbank muss mit der Eingabe\\nvon \'CLEANUP\' (in Grossbuchstaben) best&auml;tigt werden.\\n';
$LANG['err_input_dbmaint_del'] = 'Die L&ouml;schung von Datenbankeintr&auml;gen muss mit der Eingabe\\nvon \'DELETE\' (in Grossbuchstaben) best&auml;tigt werden.\\n';
$LANG['err_input_daynote_nouser'] = 'Der angegebene User existiert nicht.\\n';
$LANG['err_input_daynote_date'] = 'Datum: ';
$LANG['err_input_daynote_username'] = 'Benutzername: ';
$LANG['err_input_daynote_save'] = 'Du kannst keine leere Notiz speichern.\\nBitte gebe einen Text im Notizfeld ein\\noder klicke [Delete], um die Notiz zu l&ouml;schen.';
$LANG['err_input_daynote_create'] = 'Du kannst keine leere Notiz anlegen.\\nBitte gebe einen Text im Notizfeld ein.\\n';
$LANG['err_input_declbefore'] = 'Du musst ein ablehnen-bevor Datum eingeben.';
$LANG['err_input_period'] = 'Du musst eine g&uuml;ltige Ablehnungsperiode angeben. Das Startdatum mus vor dem Endedatum liegen.';

/**
 * User Profile Dialog
 */
$LANG['profile_group_hidden'] = '(verborgen)';

/**
 * Year calendar
 */
$LANG['year_select_year'] = 'Jahr w&auml;hlen';
$LANG['year_select_user'] = 'Nutzer w&auml;hlen';

/**
 * Calendar Display
 */
$LANG['cal_img_alt_edit_dayn'] = 'Tagesnotizen f&uuml;r diesen Nutzer bearbeiten...';

/**
 * Daynote Edit Dialog
 */
$LANG['dayn_edit'] = 'Tagesnotizen von ';

/**
 * Icon Upload Dialog
 */
$LANG['upload_type_avatar'] = 'Avatar Bild';
$LANG['upload_type_icon'] = 'Icon';
$LANG['upload_type_homepage'] = 'Willkommen Bild';

/**
 * Declination Management Page
 */
$LANG['admin_decl_notify_options'] = 'W&auml;hle aus, wer im Fall eine Ablehnung einer Anfrage per E-Mail benachrichtige werden soll.';
$LANG['admin_decl_notify_options_ff'] = '(Diese Einstellungen haben keinen Effekt, wenn der Administrator E-Mail Benachrichtigungen global ausgeschaltet hat.)';

/**
 * Announcement Page
 */
$LANG['ann_col_ann'] = 'Ank&uuml;ndigung';
$LANG['ann_col_action'] = 'Aktion';
$LANG['ann_confirm_all_confirm'] = 'Bist du sicher, dass du alle Ank&uuml;ndigungen best&auml;tigen und l&ouml;schen willst?';
$LANG['ann_no_ann'] = 'Zurzeit sind keine Ank&uuml;ndigungen vorhanden.';
$LANG['btn_confirm_all'] = 'Alle best&auml;tigen';

/**
 * Database Maintenance
 */
$LANG['admin_dbmaint_del_pschemes'] = 'Eigene Berechtigungsschemen l&ouml;schen (ausser "Default")';

/**
 * Editcalendar
 */
$LANG['notification_new_template'] = 'Neues Template f&uuml;r: ';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.001
 */

/**
 * Edit Profile Dialog
 */
$LANG['uo_showInGroups'] = 'In anderen Gruppen anzeigen';
$LANG['uo_showInGroups_comment'] = 'Zeigt den Kalender dieses Nutzers in den folgenden Gruppen, auch wenn keine Mitgliedschaft vorliegt. Dieses Feature ist sinnvoll, wenn der Nutzer '.
'zwar kein Mitglied, seine Abwesenheit aber trotzdem relevant f&uuml;r die Gruppe sind.';

/**
 * Calendar Display
 */
$LANG['cal_tt_related_1'] = 'Dieser Nutzer ist kein Mitglied der Gruppe ';
$LANG['cal_tt_related_2'] = ', er wurde aber als gruppenrelevanter Nutzer kofiguriert.';

/**
 * Error messages
 */
$LANG['err_unspecified_short'] = 'Unbekannter Fehler';
$LANG['err_unspecified_long'] = 'Ein nicht weiter spezifizierter Fehler ist aufgetreten.';
$LANG['err_notarget_short'] = 'Parameter Fehler';
$LANG['err_notarget_long'] = 'Es wurde kein Zielobjekt &uuml;bergeben. Die Seite braucht etwas zum Bearbeiten.';

/**
 * Configuration page
 */
$LANG['btn_styles'] = 'Style Sheet erneuern';

/**
 * Declination Management Page
 */
$LANG['decl_title'] = 'Ablehnungs-Management';
$LANG['decl_options'] = 'Ablehnungsoptionen';
$LANG['decl_activate'] = 'Aktivieren';
$LANG['decl_threshold'] = 'Abwesenheitsrate';
$LANG['decl_threshold_comment'] = 'Hier kann eine Abwesenheitsrate in Prozent angegeben werden, die nicht unterschritten werden darf. Die Rate kann sich auf die jeweilige Gruppe das Anfragenden beziehen oder auf alle Nutzer.';
$LANG['decl_threshold_value'] = 'Rate in %';
$LANG['decl_based_on'] = 'Basierend auf:';
$LANG['decl_base_all'] = 'Alle';
$LANG['decl_base_group'] = 'Gruppe';
$LANG['decl_before'] = 'Ablehnen vor Datum';
$LANG['decl_before_comment'] = 'Abwesenheitsanfragen k&ouml;nnen abgelehnt werden, wenn sie vor einem bestimmten Datum liegen. Bei Auswahl von "vor Heute" werden Abwesenheitsanfragen in der Vergangenheit abgelehnt.';
$LANG['decl_before_today'] = 'vor Heute (nicht eingeschlossen)';
$LANG['decl_before_date'] = 'vor Datum (nicht eingeschlossen)';
$LANG['decl_period'] = 'Ablehnungsperiode';
$LANG['decl_period_comment'] = 'Hier kann eine Periode definiert werden, innerhalb derer Abwesenheitsanfragen abgelehnt werden. Start und Ende Datum sind in diesem Fall mit eingeschlossen.';
$LANG['decl_period_start'] = 'Start Datum (eingeschlossen)';
$LANG['decl_period_end'] = 'Ende Datum (eingeschlossen)';
$LANG['decl_notify'] = 'Ablehnungs-Benachrichtigungen';
$LANG['decl_notify_comment'] = 'W&auml;hle hier aus, wer im Falle einer Ablehnung per E-Mail informiert werden soll.';
$LANG['decl_notify_user'] = 'Anfragender Nutzer';
$LANG['decl_notify_manager'] = 'Gruppenmanager';
$LANG['decl_notify_director'] = 'Direktor(en)';
$LANG['decl_notify_admin'] = 'Administratoren';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.002
 */

/*
 * Log page
 */
$LANG['log_tt_notallowed'] = 'Dieser Ereignistyp wird zurzeit gelogt. Du bist nicht berechtigt, diese Einstellung zu &auml;ndern.';
$LANG['log_btn_clearlog'] = 'System log l&ouml;schen';
$LANG['log_clear_confirm'] = 'Bist du sicher, dass du das System log l&ouml;schen willst? Alle Eintr&auml;ge werden gel&ouml;scht.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.5.003
 */

/*
 * Absence type page
 */
$LANG['abs_sel_abs'] = 'Typ ausw&auml;hlen';
$LANG['abs_sel_confirm'] = "Soll dieser Abwesenheitstyp ausgew&auml;hlt werden?\\nAlle noch nicht gespeicherten &Auml;nderungen gehen verloren.";
$LANG['abs_del_confirm'] = "Soll dieser Abwesenheitstyp ausgew&auml;hlt werden?: ";
$LANG['abs_create_abs'] = 'Typ anlegen';
$LANG['abs_title'] = 'Abwesenheitstyp Einstellungen f&uuml;r "';
$LANG['abs_help_title'] = 'Abwesenheitstyp Einstellungen';
$LANG['abs_sample'] = 'Beispielanzeige';
$LANG['abs_sample_desc'] = 'So w&uuml;rde der Abswesenheitstyp im Kalender angezeigt werden basierend auf den aktuellen Einstellungen (nach Speicherung).';
$LANG['abs_name'] = 'Name';
$LANG['abs_name_desc'] = 'Der Name wird in Listen und Beschreibungen benutzt. Er sollte aussagekr&auml;ftig sein, z.B. "Dienstreise". Maximal 80 Zeichen.';
$LANG['abs_symbol'] = 'Symbol';
$LANG['abs_symbol_desc'] = 'Das Symbol wird im Kalender engezeigt, wenn kein Icon gesetzt wurde. Es wird ausserdem in E-Mails benutzt. '.
'Das Symbol ist ein alphanumerisches Zeichen lang und muss angegeben werden. Allerdings kann das gleiche Symbol f&uuml;r mehrere Abwesenheitstypen benutzt werden. '.
'Als Standard wird "A" eingesetzt.';
$LANG['abs_icon'] = 'Icon';
$LANG['abs_icon_desc'] = 'Das Icon wird im Kalender benutzt. Wenn kein Icon ausgew&auml;hlt wird, wird stattdessen das Symbol angezeigt.';
$LANG['abs_color'] = 'Textfarbe';
$LANG['abs_color_desc'] = 'Wenn das Symbol benutzt wird (kein Icon), wird diese Textfarbe benutzt. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_bgcolor'] = 'Hintergundfarbe';
$LANG['abs_bgcolor_desc'] = 'Die Hintergundfarbe wird im Kalender benutzt, egal ob Symbol oder Icon gew&auml;hlt ist. Ein Farbdialog erscheint beim Klicken in das Feld.';
$LANG['abs_factor'] = 'Faktor';
$LANG['abs_factor_desc'] = 'TeamCal kann die genommen Tage dieses Abwesenheitstypen summieren. Das Ergebnis kann im "Abwesenheiten" Reiter des '.
'Nutzerprofils eingesehen werden. Der "Faktor" hier bietet einen Multiplikator f&uuml;r diesen Abwesenheitstypen f&uuml;r diese Berechnung. Der Standard ist 1.<br>'.
'Beispiel: Du kannst einen Abwesenheitstypen "Halbtagstraining" anlegen. Du w&uuml;rdest den Faktor dabei logischerweise auf 0.5 setzen, um die korrekte Summe '.
'genommener Trainingstage zu erhalten. Ein Nutzer, der 10 Halbtagstrainings genommen hat, k&auml;me so auf eine Summe von 5 (10 * 0.5 = 5) ganzen Trainingstagen.<br>'.
'Wenn der Faktor auf 0 gesetzt wird, wird er von der Berechnung ausgeschlossen.';
$LANG['abs_allowance'] = 'Erlaubte Anzahl';
$LANG['abs_allowance_desc'] = 'Hier kann die erlaubte Anzahl pro Kalenderjahr f&uuml;r diesen Typen gesetzt werden. Im Nutzerprofil '.
'wird die genommene und noch verbleibende Anzahl angezeigt (Ein negativer Wert in der Anzeige bedeutet, dass der Nutzer die erlaubte Anzahl '.
'&uuml;berschritten hat.). Wenn der Wert auf 0 gesetzt wird, gilt eine unbegrenzte Erlaubnis.';
$LANG['abs_show_in_remainder'] = 'Verbleibende anzeigen';
$LANG['abs_show_in_remainder_desc'] = 'Im Kalender gibt es eine aufklappbare "Verbleibend" Anzeige f&uuml;r alle Abwesenheitstypen pro Jahr pro Nutzer. '.
'Mit diesem Schalter kann bestimmt werden, ob dieser Typ in der Anzeige enthalten sein soll. Wenn kein Abwesenheitstyp f&uuml;r diese Anzeige '.
'aktiviert it, ist die Anzeige auch nicht sichtbar, auch wenn die Anzeige grunds&auml;tzlich in der Konfiguration eingeschaltet ist<br>'.
'Hinweis: Es macht keinen Sinn, einen Typen in der Verbleibend-Anzeige anzuzeigen, wenn der Faktor auf 0 gesetzt ist. Die erlaubte und '.
'verbleibende Anzahl wird dann immer gleich sein.';
$LANG['abs_show_totals'] = 'Summen anzeigen';
$LANG['abs_show_totals_desc'] = 'Die Verbleibend-Anzeige kann konfiguriert werden, so dass sie die genommenen Tage pro Monat anzeigt. Dieser Wert zeigt '.
'die Summe der genommenen Tage dieses Typen f&uuml;r den angezeigten Monat an. Dieser Schalter aktiviert diesen Typen daf&uuml;r. '.
'Wenn kein Abwesenheitstyp dafuer aktiviert ist, wird der Summenteil nicht angezeigt.';
$LANG['abs_approval_required'] = 'Genehmigung erforderlich';
$LANG['abs_approval_required_desc'] = 'Dieser Schalter macht den Typen genehmigungspflichtig durch einen Manager, Direktor oder Administrator. '.
'Ein normaler Nutzer wird dann eine Fehlermeldung erhalten, wenn er diesen Typen eintr&auml;gt. Der Manager der Gruppe erh&auml;lt aber eine E-Mail, '.
'dass eine Genehmigung seinerseits erforderlich ist. Er kann dann den Kalender dieses Nutzers bearbeiten und die entsprechende Abwesenheit '.
'eintragen.';
$LANG['abs_counts_as_present'] = 'Z&auml;hlt als anwesend';
$LANG['abs_counts_as_present_desc'] = 'Dieser Schalter definiert einen Typen als "anwesend". Dies bietet sich z.B. beim Abwesenheitstyp '.
'"Heimarbeit" an. Weil die Person arbeitet, m&ouml;chte man dies nicht als "abwesend" z&auml;hlen. Mit diesem Schalter aktiviert wird dann der Typ '.
'in den Summen als anwesend gewertet. Somit w&uuml;rde "Heimarbeit" dann auch nicht in den Abwesenheiten angezeigt.';
$LANG['abs_manager_only'] = 'Nur Management';
$LANG['abs_manager_only_desc'] = 'Mit diesem Schalter aktiviert k&ouml;nnen nur Manager und Direktoren diesen Typen setzen. Ein normaler '.
'Nutzer kann den Abwesenheitstypen zwar sehen, aber nicht setzen. Diese Funktion macht Sinn, wenn z.B. nur Manager und Direktoren einen Typen wie '.
'"Urlaub" managen.';
$LANG['abs_hide_in_profile'] = 'Im Profil verbergen';
$LANG['abs_hide_in_profile_desc'] = 'Dieser Schalter kann benutzt werden, um diesen Typen f&uuml;r normale Nutzer nicht im "Abwesenheiten" Reiter der '.
'Nutzerprofile anzuzeigen. Nur Manager, Direktoren und Administratoren k&ouml;nnen ihn dort sehen. Diese Funktion macht Sinn, wenn Manager einen Typen '.
'nur zum Zwecke von Nachverfolgung nutzt oder die verbleibende Anzahl f&uuml;r den normalen Nutzer uninteressant ist.';
$LANG['abs_confidential'] = 'Vertraulich';
$LANG['abs_confidential_desc'] = 'Dieser Schalter macht den Typen "vertraulich". Normale Nutzer k&ouml;nnen diese Abwesenheit nicht im Kalender '.
'sehen, ausser es ist ihre eigene Abwesenheit. Dies kann f&uuml;r sensitive Abwesenheiten wie "Krankheit" n&uuml;tzlich sein.';
$LANG['abs_groups'] = 'Gruppenzuordnung';
$LANG['abs_groups_desc'] = 'W&auml;hle die Gruppen aus, f&uuml;r die dieser Abwesenheitstyp g&uuml;ltig sein soll. Wenn eine Gruppe nicht '.
'ausgew&auml;hlt ist, k&ouml;nnen Mitglieder dieser Gruppe den Abwesenheitstyp nicht nutzen.';

/**
 * Error Messages
 */
$LANG['err_input_abs_no_name'] = 'Du musst einen Namen f&uuml;r diesen Abwesenheitstypen angeben.';
$LANG['err_input_abs_name'] = 'Nur alphanumerische Zeichen, Leerzeichen, Bindestriche und Unterstriche sind beim Namen erlaubt.';
$LANG['err_input_abs_symbol'] = 'Nur alphanumerische Zeichen und -=+*#$%&*()_ sind beim Symbol erlaubt.';
$LANG['err_input_abs_color'] = 'Nur hexadezimale Zeichen sind bei den Fabrwerten erlaubt.';
$LANG['err_input_abs_factor'] = 'Nur numerische Eingaben sind beim Faktor erlaubt.';
$LANG['err_input_abs_allowance'] = 'Nur numerische Eingaben sind beim Erlaubte Anzahl erlaubt.';

/**
 * Edit Calendar
 */
$LANG['month_current_absence'] = 'Aktuelle Abwesenheit';

/**
 * Database maintenance
 */
$LANG['admin_dbmaint_cleanup_chkOptimize'] = 'Tabellen optimieren';

/**
 * Config page
 */
$LANG['admin_config_mail_smtp_ssl'] = 'SMTP TLS/SSL Protokoll';
$LANG['admin_config_mail_smtp_ssl_comment'] = 'TLS/SSL Protokoll f&uuml;r die SMTP Verbindung benutzen.';
$LANG['admin_config_jqtheme'] = 'jQuery Design (Theme)';
$LANG['admin_config_jqtheme_comment'] = 'TeamCal Pro nutzt jQuery, eine popul&auml;re Sammlung von Javascript Tools. jQuery bietet auch verschiedene Themes, '.
'die die Anzeige der Reiterdialoge u.a. Objekten bestimmen. Das Standard Theme ist "base", ein neutrales Schema mit Graut&ouml;nen. '.
'Versuche andere aus der Liste, manche sind recht fabenfroh. Diese Einstellung wirkt global. Nutzer k&ouml;nnen kein eigenes jQuery Theme w&auml;hlen.';

/**
 * Message Dialog
 */
$LANG['message_title'] = 'TeamCal Pro Nachrichtencenter';
$LANG['message_type'] = 'Nachrichtstyp';
$LANG['message_type_desc'] = 'Hier kann der Nachrichtentyp ausgew&auml;hlt werden. Eine stille Nachricht wird nur auf die Nachrichtenseite gesetzt. '.
'Eine Popup Nachricht wird auch auf die Nachrichtenseite gesetzt, letztere wird aber beim Login jedes Empf&auml;ngers gleich angezeigt.';
$LANG['message_type_email'] = 'E-Mail';
$LANG['message_type_announcement_silent'] = 'Stille Nachricht';
$LANG['message_type_announcement_popup'] = 'Popup Nachricht';
$LANG['message_sendto'] = 'Empf&auml;nger';
$LANG['message_sendto_desc'] = 'W&auml;hle hier den oder die Empf&auml;nger der Nachricht aus.';
$LANG['message_sendto_all'] = 'Alle';
$LANG['message_sendto_group'] = 'Gruppe:';
$LANG['message_sendto_user'] = 'Nutzer:';
$LANG['message_msg'] = 'Nachricht';
$LANG['message_msg_desc'] = 'Gebe Betreff und Nachricht hier ein.';
$LANG['message_msg_subject'] = 'Betreff';
$LANG['message_msg_subject_sample'] = 'TeamCal Pro Message';
$LANG['message_msg_body'] = 'Text';
$LANG['message_msg_body_sample'] = '...dein Text hier...';
$LANG['message_msgsent'] = 'Die Nachricht wurde gesendet.';
$LANG['message_sendto_err'] = 'Es muss mindestens ein Nutzer ausgew&auml;hlt werden.';

/**
 * Tipsy Tooltip
 */
$LANG['tt_title_userinfo'] = 'Nutzerinfon';
$LANG['tt_title_userdayinfo'] = 'Nutzer Tagseinfo';
$LANG['tt_title_dayinfo'] = 'Tagesinfo';
$LANG['tt_edit_profile'] = 'Nutzerprofil bearbeiten...';
$LANG['tt_view_profile'] = 'Nutzerprofil ansehen...';

/**
 * HTML titles
 */
$LANG['html_title_absences'] = 'Abwesenheitstypen';
$LANG['html_title_addprofile'] = 'Neues Konto';
$LANG['html_title_announcement'] = 'Nachrichten';
$LANG['html_title_calendar'] = 'Kalender';
$LANG['html_title_config'] = 'Konfiguration';
$LANG['html_title_database'] = 'Datenbank';
$LANG['html_title_daynote'] = 'Tagesnotiz';
$LANG['html_title_declination'] = 'Ablehnung';
$LANG['html_title_editcalendar'] = 'Kalender Bearbeiten';
$LANG['html_title_editmonth'] = 'Monat Bearbeiten';
$LANG['html_title_editprofile'] = 'Profil Bearbeiten';
$LANG['html_title_environment'] = 'Umgebung';
$LANG['html_title_error'] = 'Fehler';
$LANG['html_title_eportdata'] = 'Export';
$LANG['html_title_groupassign'] = 'Gruppenzuordnung';
$LANG['html_title_groups'] = 'Gruppen';
$LANG['html_title_holidays'] = 'Feiertage';
$LANG['html_title_homepage'] = 'Home';
$LANG['html_title_legend'] = 'Legende';
$LANG['html_title_log'] = 'Logbuch';
$LANG['html_title_login'] = 'Login';
$LANG['html_title_message'] = 'Message Center';
$LANG['html_title_permissions'] = 'Berechtigungen';
$LANG['html_title_phpinfo'] = 'PHP Info';
$LANG['html_title_regions'] = 'Regionen';
$LANG['html_title_register'] = 'Registrieren';
$LANG['html_title_showyear'] = 'Jahreskalender';
$LANG['html_title_statistics'] = 'Statistik';
$LANG['html_title_upload'] = 'Upload';
$LANG['html_title_userimport'] = 'Import';
$LANG['html_title_userlist'] = 'Nutzerkonten';
$LANG['html_title_verify'] = 'Konto Verfizieren';
$LANG['html_title_viewprofile'] = 'Profil';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.001
 */

/**
 * Calendar Edit Dialog
 */
$LANG['cal_clear_absence'] = 'Abwesenheit l&ouml;schen';

/**
 * Calendar View
 */
$LANG['cal_fastedit'] = 'Schnellbearbeitung';
$LANG['cal_fastedit_tt'] = 'Schnelles Bearbeiten dieses Tages..';
$LANG['cal_abs_present'] = 'Anwesend';

/**
 * Config page
 */
$LANG['admin_config_fastedit'] = 'Schnellbearbeitung';
$LANG['admin_config_fastedit_comment'] = 'Mit dieser Option wir eine zus&auml;tzliche Zeile unten im Kalender angezeigt mit einem klickbaren Icon pro Tag. '.
'Dadurch wird eine Abwesenheits-Drop-Down-Liste f&uuml;r jeden Nutzer an diesem Tag sichtbar. Eine Abwesenheit kann darin direkt gew&auml;hlt und mit dem '.
'[Anwenden] Knopf gespeichert werden. Hinweis: Bei der Schnellbearbeitung wird kein Ablehnungscheck durchlaufen. Dieses Feature hier ist eher f&uuml;r '.
'Manager gedacht. Nat&uuml;rlich kann es aber auch &uuml;ber die Berechtigungen normalen Nutzern verf&uuml;gbar gemachet werden.<br>'.
'<br>Achtung: Bei vielen Nutzern kann hier eine erhebliche Anzahl an $_POST Eingabe-Variablen enstehen. Bitte den <strong>max_input_vars</strong> '.
'Wert in der php.ini pr&uuml;fen. Dieser steht z.B. oft bei 1000, d.h. bei knapp 20 Nutzern werden die 1000 &uuml;berschritten und die Schnellbearbeitung '.
'funktioniert nicht mehr.';

/**
 * Permissions page
 */
$LANG['perm_perm_viewFastEdit_title'] = "Schnellbearbeitung erlauben";
$LANG['perm_perm_viewFastEdit_desc'] = "Erlaubt den Zugriff auf die Schnellbearbeitung im Kalender, wenn diese Funktion in der TeamCal Pro Konfiguration eingeschaltet ist.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.001
 */

/**
 * Month Edit Dialog
 */
$LANG['month_clear_holiday'] = 'Feiertag l&ouml;schen';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.002
 */

/**
 * Statistics page
 */
$LANG['stat_choose_group'] = 'Gruppe';
$LANG['stat_choose_absence'] = 'Abwesenheit';

/**
 * Config page
 */
$LANG['admin_config_usermanual'] = 'Nutzerhandbuch';
$LANG['admin_config_usermanual_comment'] = 'TeamCal Pro\'s Nutzerhandbuch ist in Englisch verf&uuml;gbar auf der TeamCal Pro Community site. '.
'Eventuell sind aber &Uuml;bersetzungen von anderen Nutzern verf&uuml;gbar sein. Wenn das so ist, kann der Link dazu hier eingegeben werden.<br>'.
'Wenn du selbst an der Mitarbeit oder and einer neuen &Uuml;bersetzung interssiert bist, registriere dich einfach bei der <a href="https://georgelewe.atlassian.net" target="_blank">'.
'TeamCal Pro Community Site (https://georgelewe.atlassian.net)</a> und &ouml;ffne eine Task im Issue Tracker dazu.<br>'.
'Wenn hier kein Eintrag gemacht wird, setzt TeamCal Pro den Standard Link ein.';
$LANG['admin_config_lang'] = 'Standard Sprache';
$LANG['admin_config_lang_comment'] = 'TeamCal Pro enth&auml;lt die Sprachen Englisch und Deutsch. Der Administrator hat eventuell weitere Sprachen installiert. '.
'Hier kann die Standard Sprache eingestellt werden.';

/**
 * About page
 */
$LANG['about_version'] = 'Version';
$LANG['about_copyright'] = 'Copyright';
$LANG['about_license'] = 'Lizenz';
$LANG['about_credits'] = 'Dank an';
$LANG['about_for'] = 'f&uuml;r';
$LANG['about_misc'] = 'viele Nutzer f&uuml;r Tests und Vorschl&auml;ge...';

/**
 * Menu bar
 */
$LANG['mnu_announcements'] = 'Du hast Nachrichten. Klick hier, um sie zu lesen...';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.005
 */

/**
 * Config page
 */
$LANG['admin_config_loglang'] = "Logbuchsprache";
$LANG['admin_config_loglang_comment'] = "Diese Einstellung bestimmt die Sprache der Logbucheintr&auml;ge.";

/**
 * Edit calendar page
 */
$LANG['err_decl_manager_only'] = "' kann nur von Managern gesetzt bzw. geaendert werden.";
$LANG['abs_info_approval_required'] = "Dieser Abwesenheitstyp erfordert Best&auml;tigung eines Managers. ";
$LANG['abs_info_manager_only'] = "Dieser Abwesenheitstyp kann nur vom Managern gesetzt bzw. ge&auml;ndert werden. ";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.006
 */

/**
 * Config page
 */
$LANG['admin_config_hideManagerOnlyAbsences'] = 'Management Abwesenheiten verbergen';
$LANG['admin_config_hideManagerOnlyAbsences_comment'] = 'Abwesenheitstypen k&ouml;nnen als "Nur Management" markiert werden, so dass nur Manager und Direktoren sie editieren k&ouml;nnen. 
Diese Abwesenheiten werden normalen Benutzern angezeigt, sie k&ouml;nnen sie aber nicht editieren. Mit diesem Schalter k&ouml;nnen sie die Anzeige f&uuml;r normale Benutzer verbergen.';
$LANG['admin_config_presenceBase'] = 'Basis der Anwesenheitsstatistik';
$LANG['admin_config_presenceBase_comment'] = 'Die Statistikseite errechnet auch Anwesenheitstage pro Monat. Hier kann eingestellt werden, ob sich diese
      Berechnung auf Monatstage oder Arbeitstage beziehen soll. Beispiel "Arbeitstage": Wenn ein Nutzer den ganzen Juni anwesend ist, sind das 20 
      Anwesenheitstage weil der Juni 20 Arbeitstage hat. Es w&auml;ren 30 basierend auf "Monatstage".';
$LANG['admin_config_presenceBase_calendar'] = 'Monatstage';
$LANG['admin_config_presenceBase_business'] = 'Arbeitstage';

/**
 * Statistics Page
 */
$LANG['stat_days'] = 'Tage';

/**
 * Profile Page
 */
$LANG['ut_assistant'] = 'Assistent';

/**
 * Permission scheme page
 */
$LANG['perm_col_assistant'] = "Assistent";
$LANG['perm_col_assistant_tt'] = "Assistenten haben diese Berechtigung.";

/**
 * Status Bar
 */
$LANG['status_ut_assistant'] = "Assistent";

/**
 * Admin Pages
 */
$LANG['icon_assistant'] = 'Assistent';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.008
 */

/**
 * Month Dialog
 */
$LANG['month_global_daynote'] = 'Globale Tagesnotiz';
$LANG['month_personal_daynote'] = 'Pers&ouml;nliche Tagesnotiz';


/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.009
 */

/**
 * Configuration Page
 */
$LANG['admin_config_emailnopastnotifications'] = 'Keine E-Mail Benachrichtigungen f&uuml;r Vergangenheit';
$LANG['admin_config_emailnopastnotifications_comment'] =
'Aktivierung/Deaktivierung von E-Mail Benachrichtigungen f&uuml;r Kalender&auml;nderungen die komplett in der Vergangenheit liegen. '.
'Diese Funktion kann n&uuml;tzlich sein, wenn der Kalender "aufger&auml;umt" wird. Wenn aber nur eine &Auml;nderung von heute oder neuer ist, '.
'werden die E-Mails gesendet.';

$LANG['admin_config_user_search'] = 'Nutzer Suchfeld Anzeigen';
$LANG['admin_config_user_search_comment'] =
'Aktivierung/Deaktivierung eines Suchfelds in der Kalenderanzeige, mit dem einzelne Nutzer gesucht werden k&ouml;nnen.';

$LANG['admin_config_avatarmaxsize'] = 'Avatar Maximale Dateigr&ouml;&szlig;e';
$LANG['admin_config_avatarmaxsize_comment'] =
'Bestimmt die maximale Dateigr&ouml;&szlig;e f&uuml;r Avatar Dateien in Bytes.';

/**
 * Calendar page
 */
$LANG['cal_user_search'] = 'Nutzer';

/**
 * Absence type page
 */
$LANG['abs_counts_as'] = 'Z&auml;hlt als';
$LANG['abs_counts_as_desc'] = 'Hier kann ausgew&auml;hlt werden, ob die genommenen Tage diese Abwesenheitstyps gegen die Erlaubnis eines anderen Typs z&auml;hlen. ' . 
'Wenn ein anderer Typ gew&auml;hlt wird, wird die Erlaubnis diese Typs hier nicht in Betracht gezogen, nur die des anderen Typs.<br> ' .
'Beispiel: "Urlaub Halbtag" mit Faktor z&auml;hlt gegen die Erlaubnis des Typs "Urlaub".';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.011
 */

/**
 * Declination Page
 */
$LANG['decl_applyto'] = 'Ablehnung anwenden bei';
$LANG['decl_applyto_comment'] =
'Hier kann eingestellt werden, ob Ablehnung nur bei normalen Nutzern gepr&uuml;ft wird oder auch bei Managern und Direktoren. Bei Administratoren wird Ablehnung nicht gepr&uuml;t.';
$LANG['decl_applyto_regular'] = 'Nur bei normalen Nutzern';
$LANG['decl_applyto_all'] = 'Bei allen Nutzern (au&szlig;er Administratoren)';

/**
 * User list page
 */
$LANG['tab_active_users'] = 'Aktive Benutzer';
$LANG['tab_archived_users'] = 'Archivierte Benutzer';
$LANG['select_all'] = 'Alle ausw&auml;hlen';
$LANG['btn_delete_selected'] = 'Auswahl l&ouml;schen';
$LANG['btn_archive_selected'] = 'Auswahl archivieren';
$LANG['btn_restore_selected'] = 'Auswahl wiederherstellen';
$LANG['btn_reset_password_selected'] = 'Auswahl Passwort zur&uuml;cksetzen';
$LANG['user_archive_confirm'] = 'Sollen die ausgew&auml;hlten Nutzer archiviert werden?';
$LANG['user_restore_confirm'] = 'Sollen die ausgew&auml;hlten Nutzer wiederhergestellt werden?';
$LANG['confirmation_success'] = 'Erfolgreich';
$LANG['confirmation_failure'] = 'Problem';
$LANG['confirmation_delete_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden gel&ouml;scht.';
$LANG['confirmation_archive_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden archiviert.';
$LANG['confirmation_archive_selected_users_failed'] = 'Ein oder mehr Benutzer existieren bereits im Archive. Das kann der gleiche Benutzer oder einer mit selbem Benutzernamen sein.<br>Bitte l&ouml;sche diese archivierten Benutzer zuerst.';
$LANG['confirmation_restore_selected_users'] = 'Die ausgew&auml;hlten Nutzer wurden wiederhergestellt.';
$LANG['confirmation_restore_selected_users_failed'] = 'Ein oder mehr Benutzer existieren bereits als aktive Benutzer. Das kann der gleiche Benutzer oder einer mit selbem Benutzernamen sein.<br>Bitte l&ouml;sche diese aktiven Benutzer zuerst.';
$LANG['confirmation_reset_password_selected'] = 'Die Passw&ouml;rter der ausgew&auml;hlten Nutzer wurden zur&uuml;ckgesetzt und eine entsprechende e-Mail an sie versendet.';

/**
 * Absence list page
 */
$LANG['abs_list_title'] = 'Abwesenheitstypen';
$LANG['abs_counts_as'] = 'Z&auml;hlt als';
$LANG['confirmation_delete_selected_absences'] = 'Die ausgew&auml;hlten Abwesenheitstypen wurden gel&ouml;scht.';
$LANG['abs_delete_confirm'] = 'Bist du sicher, dass du die ausgew&auml;hlten Abwesenheitstypen l&ouml;schen willst?';
$LANG['btn_abs_list'] = 'Liste anzeigen';

/**
 * Database Maintenance Page
 */
$LANG['admin_dbmaint_tab_cleanup'] = "Aufr&auml;umen";
$LANG['admin_dbmaint_tab_delete'] = "L&ouml;schen";
$LANG['admin_dbmaint_tab_export'] = "Exportieren";
$LANG['admin_dbmaint_tab_restore'] = "Wiederherstellen";
$LANG['admin_dbmaint_cleanup_note'] = 'Hinweis: Der Datenbank Cleanup l&ouml;scht keine archivierten Datens&auml;tze.';
$LANG['admin_dbmaint_cleanup_success'] = "All Aufr&auml;arbeiten wurden durchgef&uuml;hrt.";
$LANG['admin_dbmaint_del_chkArchive'] = 'Archiv Tabellen leeren';
$LANG['admin_dbmaint_del_confirm_popup'] = "Die ausgew&auml;hlten Daten wurden gel&ouml;scht.";

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.011
 */

/**
 * Regions page
 */
$LANG['region_ical_in'] = '" importiert als neue Region: ';

/**
 * Messages
 */
$LANG['information'] = 'TeamCal Pro Information';
$LANG['success'] = 'TeamCal Pro Erfolg';
$LANG['warning'] = 'TeamCal Pro Warnung';
$LANG['error'] = 'TeamCal Pro Fehler';
$LANG['err_avatar_upload'] = 'Beim Hochladen des Avatar ist ein Fehler aufgetreten.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.012
 */

/**
 * Regions page
 */
$LANG['region_ical_into_region'] = 'iCal in bestehende Region importieren';
$LANG['region_ical_select_region'] = 'W&auml;hle eine Region, in die die iCal Daten importiert werden sollen.';
$LANG['region_ical_in_existing'] = '" importiert in bestehende Region: ';
$LANG['msg_ical_import_caption'] = 'iCal Import';
$LANG['msg_ical_import_text'] = 'iCal Datei "';
$LANG['msg_region_merge_text'] = 'Diese Regionen wurden verschmolzen: ';

/**
 * Config page
 */
$LANG['admin_config_userregion'] = 'Regionale Feiertage pro User anzeigen';
$LANG['admin_config_userregion_comment'] =
'Mit dieser Option zeigt der Kalender in jeder Nutzerzeile die regionalen Feiertage der Region an, die in den Optionen des Nutzers eingestellt ist. Diese Feiertage k&ouml;nnen sich von 
den globalen regionalen Feiertagen unterscheiden, die im Kopf des Kalenders angezeigt werden. Diese Option bietet eine bessere Sicht auf die unterschiedlichen regionalen Feiertage  
unterschiedlicher Nutzer. Die Anzeige mag dabei aber auch un&uuml;bersichtlicher werden, je nach Anzahl Nutzer und Regionen. Probier es aus.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.016
 */

/**
 * Error messages
 */
$LANG['err_input_group_update'] = 'Du musst mindestens einen Namen f&uuml;r die Gruppe angeben.';

/**
 * Config page
 */
$LANG['admin_config_showRangeInput'] = 'Zeitraum Eingabe anzeigen';
$LANG['admin_config_showRangeInput_comment'] = 'Hiermit kann die Zeitraumeingabe im Kalenderbearbeitungsdialog ein- bzw. ausgeblendet werden.';
$LANG['admin_config_showRecurringInput'] = 'Wiederkehrende Eingabe anzeigen';
$LANG['admin_config_showRecurringInput_comment'] = 'Hiermit kann die Wiederkehrende Eingabe im Kalenderbearbeitungsdialog ein- bzw. ausgeblendet werden.';
$LANG['admin_config_showCommentReason'] = 'Kommentar/Begr&uuml;ndung anzeigen';
$LANG['admin_config_showCommentReason_comment'] = 'Hiermit kann die Kommentar/Begr&uuml;ndung Eingabe im Kalenderbearbeitungsdialog ein- bzw. ausgeblendet werden.';

/**
 * Absence page
 */
$LANG['abs_admin_allowance'] = 'Admin Kontingent';
$LANG['abs_admin_allowance_desc'] = 'Hiermit kann eingestellt werden, dass nur der Admin die erlaubte Anzahl dieses Typs im Nutzerprofilreiter "Abwesenheiten" &auml;ndern kann.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.017
 */

/**
 * Absence page
 */
$LANG['abs_bgtransparent'] = 'Hintergrund transparent';
$LANG['abs_bgtransparent_desc'] = 'Wenn diese Option gew&auml;hlt ist, wird die Hintergrundfarbe ignoriert.';

/**
 * Common
 */
$LANG['default']='Standard';

/**
 * Config page
 */
$LANG['admin_config_appLogo'] = 'Applikationslogo';
$LANG['admin_config_appLogo_comment'] = 'Hier kann ein Logo aus dem "img" Ordner des aktuellen Themes ausgew&auml;hlt werden. Standard ist "logo.gif", das mit TeamCal Pro mitkommt.  
Wenn ein eigenes Logo erstellt wird und in das "img" Verzeichnis des Theme Ordners kopiert wird, kann es hier ausgew&auml;hlt werden. Das Standard Logo hat eine Gr&ouml;&szlig;e von 264 x 55 Pixeln.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.018
 */

/**
 * Config page
 */
$LANG['admin_config_charset'] = 'Zeichensatz';
$LANG['admin_config_charset_comment'] = 'Hier kann der HTML Zeichensatz eingestellt werden, um beispielsweise Umlaute in Namen richtig anzuzeigen. Der Standard Zeichensatz is "UTF-8".
      Dieser kann hier in "ISO-8859-1" ge&auml;ndert werden.';

/**
 * Error page
 */
$LANG['err_not_authorized_login'] = 'Wenn du ein Benutzerkonto hast, versuch es erneut mit dem folgenden Button.';

/**
 * ============================================================================
 * Added in TeamCal Pro 3.6.019
 */

/**
 * Password Check
 */
$LANG['pwchk_username'] = 'Es muss ein Username angegeben werden.<br>';
$LANG['pwchk_confirm'] = 'Das neue Passwort oder die Wiederholung fehlt.<br>';
$LANG['pwchk_mismatch'] = 'Das neue Passwort oder die Wiederholung stimmen nicht &uuml;berein.<br>';
$LANG['pwchk_minlength'] = 'Das Passwort muss mindestens ' . $LC->readConfig("pwdLength") . ' Zeichen lang sein.<br>';
$LANG['pwchk_notusername'] = 'Das Passwort darf nicht den Usernamen enthalten.<br>';
$LANG['pwchk_notusername_backwards'] = 'Das Passwort darf nicht den Usernamen r&uuml;ckw&auml;ts enthalten.<br>';
$LANG['pwchk_notold'] = 'Das neue Passwort darf nicht das alte sein.<br>';
$LANG['pwchk_number'] = 'Dass Passwort muss eine Zahl enthalten.<br>';
$LANG['pwchk_lower'] = 'Das Passwort muss einen Kleinbuchstaben enthalten.<br>';
$LANG['pwchk_upper'] = 'Das Passwort muss einen Gro&szlig;buchstaben enthalten.<br>';
$LANG['pwchk_punctuation'] = 'Das Passwort muss Interpunktionszeichen enthalten.<br>';
?>