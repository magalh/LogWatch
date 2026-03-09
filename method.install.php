<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------

if( !defined('CMS_VERSION') ) exit;

#Set Permission
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');
$this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');

$default_logsettings = 'E_ALL';
$this->RegisterEvents();
$this->SetPreference('logsettings', $default_logsettings);

// Set default log source to first available
$available_logs = LogWatch::detectAvailableLogFiles();
if (!empty($available_logs)) {
    $first_log = array_keys($available_logs)[0];
    $this->SetPreference('log_source', $first_log);
}

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
\LogWatch\ModuleTracker::track($this->GetName(), 'install', CMS_VERSION, $this->GetVersion());

?>