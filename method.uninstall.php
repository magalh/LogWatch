<?php
if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(LogWatch::MANAGE_PERM);
// Remove all preferences for this module
$this->RemovePreference();
$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( CMS_DB_PREFIX.'module_logwatch');
$dict->ExecuteSQLArray($sqlarray);
?>