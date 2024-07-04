<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

$default_logsettings = 'E_ALL';
$this->RegisterEvents();
$this->SetPreference('logsettings', $default_logsettings);

?>