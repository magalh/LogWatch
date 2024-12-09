<?php
if( !defined('CMS_VERSION') ) exit;

#Set Permission
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');
$this->CreatePermission(LogWatch::CLEAR_LOGS, 'Clear logs');
$this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');

$default_logsettings = 'E_ERROR,E_WARNING,E_PARSE,E_NOTICE,E_USER_ERROR,E_USER_WARNING';
$this->RegisterEvents();
$this->SetPreference('logsettings', $default_logsettings);



?>