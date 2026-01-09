<?php
#a
$lang['admin_save'] = "Save";
$lang['admindescription'] = 'Monitor and analyze PHP errors and server logs in a user-friendly interface';
$lang['ask_uninstall'] = 'Are you sure you want to uninstall the LogWatch module?';

#d
$lang['date'] = 'Date';
$lang['description'] = 'LogWatch helps developers troubleshoot PHP errors by providing a clean, organized view of server error logs. Monitor Fatal errors, Warnings, Notices, and Deprecated functions directly from your CMS admin panel.';

#e
$lang['error_log_file_not_found'] = 'Log file error!';
$lang['error_log_file_manual'] = 'The manual log file could not be loaded: %s';
$lang['error_log_file_selected'] = 'The selected log file could not be loaded: %s';
$lang['error_log_file_reasons'] = <<<EOT
This may be due to:
<ul>
    <li>File does not exist at the specified location</li>
    <li>Insufficient read permissions for the web server</li>
    <li>File path is incorrect or has changed</li>
</ul>
Please select a different log source or check the file permissions.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>No log sources detected!</strong><br/>
LogWatch could not automatically detect any readable error log files on your server. This can happen on shared hosting or when log files are in non-standard locations.<br/>
Please use the manual log path option below to specify the full path to your error log file.
EOT;
$lang['export_csv'] = 'Export CSV';

#f
$lang['file'] = 'File';
$lang['filter_error_types_desc'] = 'Select which error types to display in the logs view:';
$lang['friendlyname'] = 'LogWatch';

#g
$lang['get_started'] = "Configure";

#l
$lang['line'] = 'Line';
$lang['log_source'] = 'Log Source';

#m
$lang['manual_log_path'] = 'Manual log file path';
$lang['manual_log_path_desc'] = 'Enter the full server path to your error log file';
$lang['message'] = 'Message';

#p
$lang['prompt_go'] = 'Go';
$lang['prompt_page'] = 'Page';

#s
$lang['settings_saved'] = 'Settings saved successfully';

#t
$lang['type'] = 'Type';
?>
