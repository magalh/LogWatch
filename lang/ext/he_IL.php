<?php
$lang['admindescription'] = 'מעקב ונתח שגיאות PHP ויומני שרת בממשק ידידותי למשתמש';
$lang['ask_uninstall'] = 'האם אתה בטוח שברצונך להסיר את מודול LogWatch?';
$lang['custom_error_handler'] = 'מטפל שגיאות מותאם אישית';
$lang['custom_error_handler_desc'] = 'הפעל מטפל שגיאות PHP מובנה';
$lang['custom_error_handler_help'] = 'לוכד שגיאות PHP ישירות וכותב אותן לקובץ יומן מקומי. שימושי כאשר יומני שגיאות השרת אינם נגישים (אירוח משותף, הרשאות מוגבלות).';
$lang['date'] = 'תאריך';
$lang['description'] = 'LogWatch מסייע למפתחים לפתור שגיאות PHP על ידי מתן תצוגה נקייה ומאורגנת של יומני שגיאות השרת. עקוב אחר שגיאות קטלניות, אזהרות, הודעות ופונקציות מיושנות ישירות מלוח הניהול של CMS שלך.';
$lang['error_hidden'] = 'שגיאה מסומנת כקבועה ומוסתרת מהיומנים';
$lang['error_hide_failed'] = 'הסתרת השגיאה נכשלה';
$lang['error_log_file_not_found'] = 'שגיאת קובץ יומן!';
$lang['error_log_file_manual'] = 'לא ניתן לטעון את קובץ היומן הידני: %s';
$lang['error_log_file_selected'] = 'לא ניתן לטעון את קובץ היומן שנבחר: %s';
$lang['error_unhidden'] = 'שגיאה משוחזרת לתצוגת יומנים';
$lang['error_unhide_failed'] = 'לא הצליחה לבטל את ההסתרה של השגיאה';
$lang['export_csv'] = 'ייצוא CSV';
$lang['file'] = 'קובץ';
$lang['filter_error_types_desc'] = 'בחר סוגי השגיאות שיוצגו בתצוגת היומנים:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'קו';
$lang['log_source'] = 'מקור יומן';
$lang['logwatch_pro_status'] = 'LogWatch סטטוס פרו';
$lang['manual_log_path'] = 'נתיב קובץ יומן ידני';
$lang['manual_log_path_desc'] = 'הזן את נתיב השרת המלא לקובץ יומן השגיאות שלך';
$lang['message'] = 'הודעה';
$lang['pro_disabled'] = 'נכה';
$lang['pro_disabled_desc'] = 'תכונות הפרימיום מושבתות כעת';
$lang['pro_enabled'] = 'מופעל';
$lang['pro_enabled_desc'] = 'תכונות פרימיום פעילות (התראות, ניתוח, אינטגרציות)';
$lang['prompt_go'] = 'לכו';
$lang['prompt_page'] = 'עמוד';
$lang['settings_saved'] = 'ההגדרות נשמרו בהצלחה';
$lang['tab_filters'] = 'פילטרים';
$lang['tab_hidden'] = 'שגיאות נסתרות';
$lang['tab_logs'] = 'יומנים';
$lang['tab_premium'] = 'פרימיום';
$lang['tab_settings'] = 'הגדרות';
$lang['type'] = 'סוג';
$lang['help_general'] = 'כללי';
$lang['help_features'] = 'תכונות';
$lang['help_configuration'] = 'תצורה';
$lang['help_pro_features'] = 'תכונות פרו';
$lang['help_upgrade'] = 'שדרג ל- Pro';
$lang['help_troubleshooting'] = 'פתרון בעיות';
$lang['error_log_file_reasons'] = <<<EOT
זה יכול להיות בגלל:
<ul>
 <li>הקובץ אינו קיים במיקום שצוין</li>
 <li>הרשאות קריאה לא מספיקות עבור שרת האינטרנט</li>
 <li>נתיב הקובץ שגוי או השתנה</li></ul>
בחר מקור יומן אחר או בדוק את הרשאות הקובץ.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>לא זוהו מקורות יומן!</strong> <br/>
LogWatch לא הצליח לזהות באופן אוטומטי קבצי יומן שגיאות קריאים בשרת שלך. זה יכול לקרות באירוח משותף או כאשר קבצי יומן נמצאים במיקומים שאינם סטנדרטיים. <br/>
אנא השתמש באפשרות נתיב יומן רישום ידני שלהלן כדי לציין את הנתיב המלא לקובץ יומן השגיאות שלך.
EOT;
?>