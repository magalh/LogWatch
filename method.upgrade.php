<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
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

// Upgrade to 3.0.1 - Remove deprecated ModuleTracker class file
if (version_compare($current_version, '3.0.1') < 0) {
    $tracker_file = cms_join_path($this->GetModulePath(), 'lib', 'class.ModuleTracker.php');
    if (is_file($tracker_file)) {
        @unlink($tracker_file);
    }
}

