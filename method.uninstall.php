<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$dict = NewDataDictionary($db);

$sqlarray = $dict->DropTableSQL(cms_db_prefix() . 'module_logwatch_hidden');
$dict->ExecuteSQLArray($sqlarray);
?>