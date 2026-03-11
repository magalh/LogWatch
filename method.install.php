<?php
if (!isset($gCms)) exit;

$db = $this->GetDb();
$dict = NewDataDictionary($db);

// Create hidden errors table
$flds = "
    id I AUTO KEY,
    error_hash C(32) NOTNULL,
    file_path C(255),
    line_number I,
    error_message X NOTNULL,
    hidden_by I NOTNULL,
    hidden_date T NOTNULL,
    notes X
";

$taboptarray = ['mysql' => 'ENGINE=InnoDB'];
$sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_logwatch_hidden', $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->CreateIndexSQL('idx_error_hash', cms_db_prefix() . 'module_logwatch_hidden', 'error_hash', ['UNIQUE']);
$dict->ExecuteSQLArray($sqlarray);

// Create permissions
$this->CreatePermission(LogWatch::MANAGE_PERM, 'Manage LogWatch');
$this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');
$this->CreatePermission(LogWatch::CLEAR_LOGS, 'Clear Logs');

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
\LogWatch\ModuleTracker::track($this->GetName(), 'install', CMS_VERSION, $this->GetVersion());
