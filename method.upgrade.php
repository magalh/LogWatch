<?php
if (!isset($gCms)) exit;

$db = $this->GetDb();
$dict = NewDataDictionary($db);

$current_version = $oldversion;
$taboptarray = ['mysql' => 'ENGINE=InnoDB'];

// Upgrade to 2.2.0 - Add hidden errors table (if upgrading from older version)
if( version_compare($current_version, '2.1.0') < 0 ) {
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
    
    $sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_logwatch_hidden', $flds, $taboptarray);
    $dict->ExecuteSQLArray($sqlarray);
    
    $sqlarray = $dict->CreateIndexSQL('idx_error_hash', cms_db_prefix() . 'module_logwatch_hidden', 'error_hash', ['UNIQUE']);
    $dict->ExecuteSQLArray($sqlarray);
}

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
\LogWatch\ModuleTracker::track($this->GetName(), 'upgrade', CMS_VERSION, $this->GetVersion());


