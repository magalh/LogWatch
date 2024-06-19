<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

$default_logsettings = 'E_ERROR,E_NOTICE,E_WARNING';
$this->SetPreference('logsettings', $default_logsettings);

?>