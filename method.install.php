<?php
if (!defined('CMS_VERSION')) exit;

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
?>