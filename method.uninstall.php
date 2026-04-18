<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------

if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(LogWatch::MANAGE_PERM);
$this->RemovePermission(LogWatch::EXPORT_LOGS);

$this->RemovePreference();

// Drop hidden errors table
$db = $this->GetDb();
$dict = NewDataDictionary($db);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_logwatch_hidden');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_logwatch');
$dict->ExecuteSQLArray($sqlarray);

?>
