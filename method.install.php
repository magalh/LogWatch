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

// Create hidden errors table
$db = $this->GetDb();
$dict = NewDataDictionary($db);

$taboptarray = array('mysql' => 'ENGINE=InnoDB', 'mysqli' => 'ENGINE=InnoDB');

$flds = "
	id I KEY AUTO,
	error_hash C(64) NOTNULL,
	file_path C(500),
	line_number I,
	error_message X,
	hidden_by I,
	hidden_date T,
	notes X
";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_logwatch_hidden', $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// Create unique index on error_hash
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix() . 'module_logwatch_hidden_idx', cms_db_prefix() . 'module_logwatch_hidden', 'error_hash', array('UNIQUE'));
$dict->ExecuteSQLArray($sqlarray);

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
\LogWatch\ModuleTracker::track($this->GetName(), 'install', CMS_VERSION, $this->GetVersion());

?>
