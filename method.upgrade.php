<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if (!defined('CMS_VERSION')) exit;

if( version_compare($oldversion,'1.4.0') < 0 ) {
    $this->CreatePermission(LogWatch::CLEAR_LOGS, 'Clear logs');
    $this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');
}

if( version_compare($oldversion,'2.0.0') < 0 ) {
    // Remove clear_logs permission as it's no longer needed in 2.0
    $this->RemovePermission(LogWatch::CLEAR_LOGS);
    
    // Update default settings for new error types
    $this->SetPreference('logsettings', 'E_ALL');
    
    // Set default log source to first available
    $available_logs = LogWatch::detectAvailableLogFiles();
    if (!empty($available_logs)) {
        $first_log = array_keys($available_logs)[0];
        $this->SetPreference('log_source', $first_log);
    }
}

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
ModuleTracker::track($this->GetName(), 'upgrade', CMS_VERSION, $this->GetVersion());

?>
