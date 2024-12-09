<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

$default_logsettings = 'E_ERROR,E_WARNING,E_PARSE,E_NOTICE,E_USER_ERROR,E_USER_WARNING';
$this->RegisterEvents();
$this->SetPreference('logsettings', $default_logsettings);

?>